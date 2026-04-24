import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '../stores/auth';

const routes = [
    {
        path: '/',
        name: 'Home',
        component: () => import('@client/pages/Home.vue'),
    },
    {
        path: '/login',
        name: 'Login',
        component: () => import('@client/pages/Login.vue'),
        meta: { guestOnly: true },
    },
    {
        path: '/register',
        name: 'Register',
        component: () => import('@client/pages/Register.vue'),
        meta: { guestOnly: true },
    },
    {
        path: '/verify-otp',
        name: 'VerifyOTP',
        component: () => import('@client/pages/VerifyOTP.vue'),
    },
    // {
    //     path: '/profile',
    //     name: 'Profile',
    //     component: () => import('@client/pages/Profile.vue'),
    //     meta: { requiresAuth: true },
    // },
];

const router = createRouter({
    history: createWebHistory('/'),
    routes,
});

router.beforeEach((to, from, next) => {
    const authStore = useAuthStore();
    const isAuthenticated = authStore.isLoggedIn;

    if (to.meta.requiresAuth && !isAuthenticated) {
        next({ name: 'Login' });
    }
    else if (to.meta.guestOnly && isAuthenticated) {
        next({ name: 'Home' });
    }
    else {
        next();
    }
});

export default router;
