import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // Application Inertia (Vue 3 + Tailwind).
                'resources/js/inertia/app.js',
            ],
            // Sortie historique conservée : public/js/modules (manifest versionné).
            buildDirectory: 'js/modules',
            refresh: true,
        }),
        vue({
            template: { transformAssetUrls: { base: null, includeAbsolute: false } },
        }),
        tailwindcss(),
    ],
    resolve: {
        alias: { '@': '/resources/js/inertia' },
    },
});
