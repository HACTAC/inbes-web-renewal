import { defineConfig } from "astro/config";
import tailwind from "@astrojs/tailwind";

export default defineConfig({
  site: process.env.PUBLIC_SITE_URL,
  base: process.env.PUBLIC_BASE_PATH ?? "/",
  integrations: [
    tailwind({
      applyBaseStyles: false
    })
  ]
});
