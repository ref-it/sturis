import {
    defineConfig
} from 'vite';
import laravel from 'laravel-vite-plugin';
import { viteStaticCopy } from 'vite-plugin-static-copy';
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/css/theme.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
                viteStaticCopy({
            targets: [
                {
                src: 'node_modules/leaflet/dist/images/*',
                dest: 'assets/images',
                },
            ],
        }),
    ],
    server: {
        cors: true,
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
