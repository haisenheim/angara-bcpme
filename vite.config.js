import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // 'resources/css/app.scss',
                'resources/js/app.js',
                // Refonte Inertia (Vue 3 + Tailwind) — cohabite avec les écrans Blade.
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
