import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: '0.0.0.0',  // Allows access from other devices
        port: 5173,        // Keeps Vite on a fixed port
        strictPort: true,  // Ensures Vite doesn't change ports
        hmr: {
            host: 'localhost', // Replace this with your actual local IP
        }
    }
});
