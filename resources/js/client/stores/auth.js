import { defineStore } from 'pinia';
import api from '../services/api';
import axios from 'axios';
import router from '../router';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: JSON.parse(localStorage.getItem('user')) || null,
        token: localStorage.getItem('access_token') || null,
    }),

    getters: {
        isLoggedIn: (state) => !!state.token,
    },

    actions: {
        //Watch changes in the auth state and sync to LocalStorage
        setupWatcher() {
            this.$subscribe(
                (mutation, state) => {
                    console.log('Pinia changed, syncing to LocalStorage...');
                    if (state.token) {
                        localStorage.setItem('access_token', state.token);
                    } else {
                        localStorage.removeItem('access_token');
                    }

                    if (state.user) {
                        localStorage.setItem('user', JSON.stringify(state.user));
                    } else {
                        localStorage.removeItem('user');
                    }
                },
                {
                    detached: true,
                },
            );
        },

        //Try to restore auth state on app startup
        async bootstrapAuth() {
            if (this.token) {
                return true;
            }
            const savedUser = localStorage.getItem('user');
            if (!savedUser) {
                return false;
            }
            //If don't have token, try to refresh it
            const refreshed = await this.silentRefresh();

            if (!refreshed) {
                this.user = null;
            }
            return refreshed;
        },

        //Login with credentials and store token/user on success
        async login(credentials) {
            const response = await api.post('/login', credentials);
            if (response.data.success) {
                const { access_token, user } = response.data.data;
                this.token = access_token;
                this.user = user;
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

                return true;
            } catch (error) {
                this.user = null;
                this.token = null;
                return false;
            }
        },
        async logout() {
            try {
                await api.post('/logout');
            } finally {
                this.user = null;
                this.token = null;
            }
        },
    },
});
