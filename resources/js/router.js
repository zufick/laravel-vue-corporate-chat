import Vue from 'vue';
import VueRouter from 'vue-router';

import Dashboard from './pages/chat/Layout.vue';
import Login from './pages/Login.vue';
import Register from './pages/Register.vue';

Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    linkExactActiveClass: 'active',
    routes: [
        {
            path: '/',
            name: 'dashboard',
            component: Dashboard
        },
        {
            path: '/login',
            name: 'login',
            component: Login
        },
        {
            path: '/register',
            name: 'register',
            component: Register
        },
    ],
});

export default router;
