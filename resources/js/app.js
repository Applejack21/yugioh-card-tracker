import 'vue-toastification/dist/index.css';
import '../css/app.css';
import './bootstrap';

import AppLayout from '@/Layouts/AppLayout.vue';
import { createInertiaApp, Link } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import Toast from 'vue-toastification';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
	title: (title) => `${title} - ${appName}`,
	resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
	setup({ el, App, props, plugin }) {
		return createApp({ render: () => h(App, props) })
			.use(plugin)
			.use(Toast)
			.mixin({
				methods: {
					route
				},
				components: {
					AppLayout,
					Link,
				},
			})
			.mount(el);
	},
	progress: {
		color: '#e8eaed',
	},
});
