import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
<<<<<<< HEAD
                'resources/css/leave-requests.css',
                'resources/js/app.js',
                'resources/js/leave-requests.js',
=======
                'resources/js/app.js',
                'resources/js/schedule.js',
>>>>>>> origin/feat/fe-schedule-viphou
            ],
            refresh: true,
            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                }),
            ],
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
