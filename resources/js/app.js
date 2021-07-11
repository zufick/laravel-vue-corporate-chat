import Vue from "vue";
require('./bootstrap');
window.Vue = require('vue').default;
import Vuetify from "vuetify";
import router from './router';
import App from './pages/Layout.vue';
import store from './store';
import {Vuelidate} from "vuelidate";

/**
 * Активация библиотеки интерфейсов Vuetify
 */
Vue.use(Vuetify);
Vue.use(Vuelidate);


/**
 * Создание Vue приложения
 * @type {Vue | CombinedVueInstance<Vue, object, object, {store: {userCheckInitialized: boolean, saveToken, (*=): void, user: {}, authorize, (): Promise<void>}}, Record<never, any>>}
 */
const app = new Vue({
    router,
    vuetify: new Vuetify({theme: { dark: localStorage.getItem('dark-theme') === 'true' }}),
    el: '#app',
    render: h => h(App),
    computed: {
        /**
         * Хранилище для проверки в devtools
         * @returns {{userCheckInitialized: boolean, saveToken(*=): void, user: {}, authorize(): Promise<void>}}
         */
        store(){
            return store;
        }
    },
});

window.app = app;
