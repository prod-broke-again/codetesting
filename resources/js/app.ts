import './bootstrap';
import { createApp } from 'vue';
import { createPinia } from 'pinia';
import { createRouter, createWebHistory, type RouteRecordRaw } from 'vue-router';
import App from './App.vue';

// Создание приложения
const app = createApp(App);

// Настройка Pinia для управления состоянием
const pinia = createPinia();
app.use(pinia);

// Определение маршрутов с типизацией
const routes: RouteRecordRaw[] = [
    {
        path: '/',
        name: 'home',
        component: () => import('./pages/Home.vue')
    },
    {
        path: '/code/:hash',
        name: 'code-view',
        component: () => import('./pages/CodeView.vue')
    },
    {
        path: '/create',
        name: 'code-create',
        component: () => import('./pages/CodeCreate.vue')
    }
];

// Настройка Vue Router
const router = createRouter({
    history: createWebHistory(),
    routes
});
app.use(router);

// Монтирование приложения
app.mount('#app'); 