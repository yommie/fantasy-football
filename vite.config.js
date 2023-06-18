import vue from '@vitejs/plugin-vue'
import symfonyPlugin from "vite-plugin-symfony"

import { defineConfig } from "vite"

export default defineConfig({
    plugins: [
        vue(),
        symfonyPlugin()
    ],
    root: ".",
    base: "/build/",
    publicDir: false,
    build: {
        manifest: true,
        emptyOutDir: true,
        assetsDir: "",
        outDir: "./public/build",
        rollupOptions: {
            input: {
                app: "./assets/app.js"
            },
        },
        server: {
            port: 13714
        }
    }
})
