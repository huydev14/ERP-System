import { defineStore } from 'pinia';
import api from '../services/api';
import axios from 'axios';
import router from '../router';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: JSON.parse(localStorage.getItem('user')) || null,
        token: localStorage.getItem('access_token' || null),
    }),

    getters: {
        isLoggedIn: (state) => !!state.token,
    },

    actions: {
        async login(credentials) {
            const response = await api.post('/login', credentials);

            if (response.data.success) {
                const { access_token, user } = response.data.data;

                this.token = access_token;
                this.user = user;

                localStorage.setItem('access_token', access_token);
                localStorage.setItem('user', JSON.stringify(user));

                api.defaults.headers.common['Authorization'] = `Bearer ${access_token}`;
            }
            return response.data;
        },

        async silentRefresh() {
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

                this.token = access_token;
                this.user = user;

                localStorage.setItem('access_token', access_token);
                localStorage.setItem('user', JSON.stringify(user));

                return true;
            } catch (error) {
                this.forceLogout();
                return false;
            }
        },

        async logout() {
            try {
                await api.post('/logout');
            } finally {
                this.forceLogout();
                router.push({ name: 'Login' });
            }
        },

        forceLogout() {
            this.user = null;
            this.token = null;

            localStorage.removeItem('access_token');
            localStorage.removeItem('user');

            delete api.defaults.headers.common['Authorization'];
            router.push({ name: 'Login' });
        },
    },
});
