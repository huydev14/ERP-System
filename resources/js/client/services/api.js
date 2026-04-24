import axios from 'axios';
import { useAuthStore } from '../stores/auth';

const api = axios.create({
    baseURL: '/api/v1',
    headers: {
        Accept: 'application/json',
    },
});

api.interceptors.request.use((config) => {
    const store = useAuthStore();
    if (store.token) {
        config.headers.Authorization = `Bearer ${store.token}`;
    }
    return config;
});

api.interceptors.response.use(
    (response) => response,
    async (error) => {
        const originalRequest = error.config;

        if (error.response?.status === 401 && !originalRequest._retry && originalRequest.url !== '/refresh') {
            originalRequest._retry = true; 
            const store = useAuthStore();

            try {
                const res = await axios.post(
                    '/refresh',
                    {},
                    {
                        baseURL: '/api/v1',
                        withCredentials: true,
                    },
                );

                const { access_token, user } = res.data.data;

                store.token = access_token;
                store.user = user;

                localStorage.setItem('access_token', access_token);
                localStorage.setItem('user', JSON.stringify(user));

                originalRequest.headers.Authorization = `Bearer ${access_token}`;
                return api(originalRequest);
            } catch (refreshError) {
                store.forceLogout();
                return Promise.reject(refreshError);
            }
        }

        return Promise.reject(error);
    },
);
export default api;
