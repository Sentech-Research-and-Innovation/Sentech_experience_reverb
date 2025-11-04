import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';
import { fileURLToPath } from 'url';

// Helper to correctly define __dirname equivalent in an ES Module context
const __dirname = path.dirname(fileURLToPath(import.meta.url));

export default defineConfig({
    // FIX 1: Set root to 'public'
    root: 'public', 

    plugins: [
        laravel({
            input: ["resources/sass/app.scss", "resources/js/app.js"],
            ssr: 'resources/js/ssr.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    
    // FIX 2: SASS INCLUDE PATHS (General SASS Module Resolution)
    css: {
        preprocessorOptions: {
            scss: {
                // Allows SASS to resolve 'jsvectormap' and '~bootstrap' imports internally
                includePaths: ['node_modules'],
            },
        },
    },
    
    resolve: {
        alias: {
            // FIX 3: RESOURCE FOLDER ALIAS (Resolves ENOENT on resources/js/Pages)
            '@': path.resolve(__dirname, 'resources/js'),
            '~sweetalert2': path.resolve(__dirname, 'node_modules/sweetalert2'),
            '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
	     // 🛑 CRITICAL FIX: Re-add the specific SCSS path alias for vuevectormap 🛑
            'vuevectormap/src/scss/vuevectormap.scss': path.resolve(__dirname, 'node_modules/vuevectormap/src/vuevectormap.scss'),

            // 🛑 CRITICAL FIX: Re-add the specific CSS path alias for jsvectormap 🛑
            //'jsvectormap': path.resolve(__dirname, 'node_modules/jsvectormap/dist/jsvectormap.css'),
            // 🛑 FIX 4: VUEVECTORMAPP ALIAS (Using your corrected path) 🛑
            //'vuevectormap/src/scss/vuevectormap.scss': path.resolve(__dirname, 'node_modules/vuevectormap/src/vuevectormap.scss'),

            // 🛑 CRITICAL FIX: The conflicting jsvectormap alias has been REMOVED. 🛑
        }
    },
    
    ssr: {
        noExternal: ['@inertiajs/server'],
    }
});
