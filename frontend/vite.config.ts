import { defineConfig, loadEnv } from 'vite'
import { createHtmlPlugin } from 'vite-plugin-html'
import vueDevTools from 'vite-plugin-vue-devtools'
import vue from '@vitejs/plugin-vue'
import vuetify from 'vite-plugin-vuetify'
import path from 'path'

export default defineConfig(({ mode }) => {
    // wczytujemy env dla danego trybu
    const env = loadEnv(mode, process.cwd(), '')
    const isDev = mode !== 'production'

    return {
        plugins: [vue(), vuetify(), vueDevTools(), createHtmlPlugin({})],
        resolve: {
            alias: { '@': path.resolve(__dirname, './src') }
        },
        // proxy tylko w dev; target bierzemy z VITE_API_URL (bez końcowego /, bo i tak proxujemy /api)
        server: isDev
            ? {
                proxy: {
                    '/api': {
                        target: env.VITE_API_URL?.replace(/\/api\/?$/, '') || 'http://localhost:8080',
                        changeOrigin: true
                    }
                }
            }
            : undefined,
        build: { outDir: 'dist' }
    }
})
