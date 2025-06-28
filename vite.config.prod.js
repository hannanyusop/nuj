import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/css/custom.css',
                'resources/js/app.js',
                'resources/css/fontawesome.css'
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    resolve: {
        alias: {
            '@': '/resources/js',
        },
    },
    build: {
        // Production optimizations
        minify: 'terser',
        sourcemap: false,
        rollupOptions: {
            output: {
                manualChunks: {
                    vendor: ['alpinejs'],
                },
            },
        },
        // Optimize CSS
        cssCodeSplit: true,
        // Optimize assets
        assetsInlineLimit: 4096,
        // Generate manifest
        manifest: true,
    },
    // Optimize for production
    optimizeDeps: {
        include: ['alpinejs'],
    },
}); 