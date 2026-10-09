import { defineConfig } from "astro/config";
import tailwind from "tailwindcss";
import autoprefixer from "autoprefixer";

export default defineConfig({
  site: process.env.PUBLIC_SITE_URL,
  base: process.env.PUBLIC_BASE_PATH ?? "/",
  compressHTML: true,
  vite: {
    build: { cssMinify: "esbuild" },
    css: {
      postcss: {
        plugins: [tailwind(), autoprefixer()]
      }
    }
  }
});
