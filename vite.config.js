import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    build: {
        outDir: './public/js/modules',
        emptyOutDir: true,
        manifest: true,
    },
    plugins: [
        laravel({
            input: [
               // 'resources/css/app.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});
