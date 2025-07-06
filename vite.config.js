import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.scss',
                'resources/css/modern.style.css',
                'resources/css/wizard.css',
                'resources/js/app.js',
                '@jquery/dist/jquery.min.js',
                'resources/js/form-wizard.js',
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
            '~bootstrap-icons': path.resolve(__dirname, 'node_modules/bootstrap-icons'),
            '@jquery': path.resolve(__dirname, "node_modules/jquery")
        }
    },
});
