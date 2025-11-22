import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import Toast from 'vue-toastification';
import 'vue-toastification/dist/index.css';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(Toast, {
                timeout: 3000,
                position: 'top-right',
            })
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

import { router } from '@inertiajs/vue3';
import { useToast } from 'vue-toastification';

const toast = useToast();

router.on('success', (event) => {
    const message = event.detail?.page?.props?.flash?.message;
    if (message) {
        toast.success(message);
    }
});

router.on('error', (event) => {
    const errorMessage = event.detail?.page?.props?.flash?.error;
    if (errorMessage) {
        toast.error(errorMessage);
    }
});
