import Vue from 'vue';
import VueRouter from 'vue-router';

import Dashboard from './pages/chat/Layout.vue';
import Login from './pages/Login.vue';
import Register from './pages/Register.vue';

import store from './store';
import middlewarePipeline from "./middlewarePipeline";
import auth from './middleware/auth'
import guest from './middleware/guest'

Vue.use(VueRouter);

/**
 * Маршруты приложения
 * @type {VueRouter}
 */
const router = new VueRouter({
    mode: 'history',
    linkExactActiveClass: 'active',
    routes: [
        {
            path: '/',
            name: 'dashboard',
            meta: { middleware: [auth] },
            component: Dashboard,
        },
        {
            path: '/login',
            name: 'login',
            meta: { middleware: [guest] },
            component: Login
        },
        {
            path: '/register',
            name: 'register',
            meta: { middleware: [guest] },
            component: Register
        },
    ],
});

/**
 * Проверка начальной инициализации пользователя и применение промежуточного ПО
 */
router.beforeEach(async (to, from, next) => {
    if(!store.userCheckInitialized){
        await store.authorize();
    }

    const middleware = to.meta.middleware;
    const context = { to, from, next, store };

    if (!middleware) {
        return next();
    }

    middleware[0]({
        ...context,
        next: middlewarePipeline(context, middleware, 1),
    });
});


export default router;
