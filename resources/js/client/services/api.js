import axios from 'axios';
import { useAuthStore } from '../stores/auth';

const api = axios.create({
    baseURL: '/api/v1',
    headers: {
        Accept: 'application/json',
    },
});

const authChannel = new BroadcastChannel('auth_sync_channel');

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

            try {
                const newToken = await navigator.locks.request('refresh_token_lock', async () => {
                    const store = useAuthStore();

                    if (store.token && store.token !== originalRequest.headers.Authorization?.split(' ')[1]) {
                        return store.token;
                    }

                    const res = await axios.post(
                        '/refresh',
                        {},
                        {
                            baseURL: '/api/v1',
                            withCredentials: true,
                        },
                    );

                    const refreshedToken = res.data.data.access_token;

                    store.setToken(refreshedToken);
                    authChannel.postMessage({ type: 'TOKEN_REFRESHED', token: refreshedToken });

                    return refreshedToken;
                });
                originalRequest.headers.Authorization = `Bearer ${newToken}`;
                return api(originalRequest);
            } catch (refreshError) {
                const store = useAuthStore();
                store.forceLogout();
                authChannel.postMessage({ type: 'LOGOUT' });
                return Promise.reject(refreshError);
            }
        }

        return Promise.reject(error);
    },
);

export default api;
