import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import path from 'path';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', '@zoho/app.js', , '@zohoStyle/app.css'],
            refresh: true,
        }),
    vue({ // 2. Initialize the plugin
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        tailwindcss(),
    ],
    resolve: {
        alias: {
            'vue': 'vue/dist/vue.esm-bundler.js', // 3. Helpful alias for Blade templates
            '@zoho': path.resolve(__dirname, 'vendor/winston86/zoho-deals/src/resources/js'),
            '@zohoStyle': path.resolve(__dirname, 'vendor/winston86/zoho-deals/src/resources/css'),
            'axios': path.resolve(__dirname, 'node_modules/axios'),
        },
    preserveSymlinks: true
    },
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    fs: {
            // This allows Vite to serve files from the symlinked vendor directory
            allow: ['..', './', '@zoho'] 
        }
    },
});
