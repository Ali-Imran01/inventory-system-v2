import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';
import tailwindcss from '@tailwindcss/vite'; // or your specific tailwind plugin

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.jsx'],
            refresh: true,
        }),
        react(),
        tailwindcss(),
    ],
    server: {
        host: '0.0.0.0', 
        hmr: {
            // USE THE NEW VITE TUNNEL URL HERE
            host: 'YOUR-NEW-VITE-NGROK-URL.ngrok-free.app', 
            protocol: 'wss',
            clientPort: 443 // Force it to use standard secure port
        },
    },
});