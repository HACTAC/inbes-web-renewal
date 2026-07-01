/** @type {import('tailwindcss').Config} */
export default {
  content: ["./src/**/*.{astro,html,js,jsx,md,mdx,svelte,ts,tsx,vue}"],
  theme: {
    extend: {
      colors: {
        ink: "#1f272d",
        sub: "#5c6770",
        line: "#dfe4e7",
        paper: "#f6f7f4",
        soft: "#edf2f0",
        blue: "#185a7d",
        green: "#276b5e",
        gold: "#b98a2f"
      },
      fontFamily: {
        sans: [
          "-apple-system",
          "BlinkMacSystemFont",
          "\"Hiragino Sans\"",
          "\"Yu Gothic\"",
          "YuGothic",
          "Meiryo",
          "sans-serif"
        ]
      },
      boxShadow: {
        soft: "0 18px 48px rgba(31, 39, 45, 0.08)"
      }
    }
  },
  plugins: []
};
