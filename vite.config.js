import { defineConfig } from "vite";
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";
import react from "@vitejs/plugin-react";
import path from "path";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js', 
                "resources/js/app.tsx",
                'resources/css/filament/admin/theme.css'],
            refresh: [
                `resources/views/**/*`
            ],
        }),
        react(),
        tailwindcss(),
    ],
    resolve: {
        alias: {
            "@": path.resolve(__dirname, "resources/js"),
        },
    },
    server: {
        cors: true,
        watch: {
            ignored: [
                "**/storage/framework/views/**",
                "**/resources/jsBAK/**",
                "**/*.bak",
                "**/* copy.*",
            ],
        },
    },
    envPrefix: ["VITE_", "APP_", "DB_"],
    esbuild: {
        jsx: "automatic",
    },
});