import './bootstrap';
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { createPinia } from 'pinia';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

createInertiaApp({
    resolve: (name) => resolvePageComponent(`./pages/${name}.vue`, import.meta.glob('./pages/**/*.vue') as any),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });
        
        // Настройка Pinia для управления состоянием
        const pinia = createPinia();
        app.use(pinia);
        
        app.use(plugin);
        app.mount(el);
        
        // Инициализируем тему после монтирования приложения
        import('./stores/theme').then(({ useThemeStore }) => {
            const themeStore = useThemeStore();
            themeStore.initTheme();
        });
    },
}); 