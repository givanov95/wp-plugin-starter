import { defineConfig } from "vite";
import vue from "@vitejs/plugin-vue";
import { fileURLToPath, URL } from "node:url";

/**
 * Build outputs to dist/ with manifest.json, which the PHP
 * PluginServiceProvider reads to enqueue hashed assets in production.
 *
 * In dev (`npm run dev`), a `.vite-dev` flag file in the plugin root
 * tells PHP to load from the dev server instead.
 */
export default defineConfig({
	plugins: [vue()],
	resolve: {
		alias: {
			"@": fileURLToPath(new URL("./assets/js", import.meta.url)),
		},
	},
	server: {
		host: "localhost",
		port: 5173,
		strictPort: true,
		cors: true,
	},
	build: {
		manifest: true,
		outDir: "dist",
		emptyOutDir: true,
		rollupOptions: {
			input: "assets/js/main.ts",
		},
	},
});
