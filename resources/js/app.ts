import './bootstrap';
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { createPinia } from 'pinia';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import Toast from '@/components/modals/Toast.vue';

createInertiaApp({
    resolve: (name) => resolvePageComponent(`./pages/${name}.vue`, import.meta.glob('./pages/**/*.vue') as any),
    setup({ el, App, props, plugin }) {
        const root = document.createElement('div');
        el.parentNode?.insertBefore(root, el.nextSibling);

        const app = createApp({ render: () => h(App, props) });
        const pinia = createPinia();
        app.use(pinia);
        app.use(plugin);
        app.mount(el);

        // Монтируем Toast контейнер
        const toastApp = createApp(Toast);
        toastApp.mount(root);

        import('./stores/theme').then(({ useThemeStore }) => {
            const themeStore = useThemeStore();
            themeStore.initTheme();
        });
    },
}); 