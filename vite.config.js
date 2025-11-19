import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/assets/sass/app.scss',
                'resources/assets/js/app.js'
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            // Force all modules to use the same jquery version
            'jquery': path.resolve(__dirname, 'node_modules/jquery/dist/jquery.js'),
            // Add common aliases
            '@': path.resolve(__dirname, '/resources/assets/js'),
        }
    },
    build: {
        sourcemap: true,
        manifest: true,
        rollupOptions: {
            output: {
                manualChunks: {
                    // Extract vendor libraries into a separate chunk
                    vendor: [
                        'jquery',
                        'popper.js',
                        'bootstrap',
                        '@fortawesome/fontawesome-svg-core',
                        '@fortawesome/free-solid-svg-icons',
                        '@fortawesome/free-regular-svg-icons',
                        '@fortawesome/free-brands-svg-icons',
                        'sortablejs',
                        'bootstrap-datepicker',
                        'bootstrap4-tagsinput',
                        'trix',
                        'corejs-typeahead',
                    ]
                }
            }
        }
    },
    define: {
        // Make jQuery available globally
        'global': 'globalThis',
    }
});