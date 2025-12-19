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
            refresh: false,
            buildDirectory: 'build',
        }),
    ],
    resolve: {
        alias: {
            'jquery': path.resolve(__dirname, 'node_modules/jquery/dist/jquery.js'),
        }
    },
    css: {
        devSourcemap: true,
    },
    optimizeDeps: {
        include: ['jquery', 'corejs-typeahead'],
    },
    build: {
        sourcemap: true,
        minify: false,
        manifest: 'manifest.json',
        outDir: 'public/build',
        emptyOutDir: true,
        commonjsOptions: {
            include: [/corejs-typeahead/, /node_modules/],
        },
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
                },
            entryFileNames: 'assets/[name]-[hash].js',
            chunkFileNames: 'assets/[name]-[hash].js',
            assetFileNames: 'assets/[name]-[hash].[ext]'
            }
        }
    },
});