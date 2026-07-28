/** @type {import('tailwindcss').Config} */
export default {
  content: ["./src/**/*.{astro,html,js,jsx,md,mdx,svelte,ts,tsx,vue}"],
  theme: {
    screens: {
      sm: "640px",
      md: "768px",
      lg: "1024px",
      xl: "1280px"
    },
    extend: {
      colors: {
        ink: "#111111",
        sub: "#5A5A5A",
        muted: "#888888",
        line: "#D9D9D9",
        paper: "#FFFFFF",
        soft: "#F5F5F3",
        primary: "#C8101E",
        "primary-dark": "#990D17",
        "primary-pale": "#F7E8EA",
        success: "#1F684B",
        "success-pale": "#EAF4EF",
        warning: "#805400",
        "warning-pale": "#FFF4D8",
        error: "#A51D2A",
        "error-pale": "#FBEAEC"
      },
      fontFamily: {
        sans: [
          "\"hiragino-kaku-gothic-pron\"",
          "\"Hiragino Kaku Gothic ProN\"",
          "\"Hiragino Sans\"",
          "\"Yu Gothic\"",
          "YuGothic",
          "Meiryo",
          "sans-serif"
        ],
        latin: ["\"neue-haas-grotesk-text\"", "\"Helvetica Neue\"", "Arial", "sans-serif"],
        product: ["\"urw-din\"", "\"DIN Alternate\"", "Arial", "sans-serif"]
      },
      maxWidth: {
        page: "1200px"
      },
      borderRadius: {
        sm: "4px",
        DEFAULT: "4px",
        md: "8px",
        lg: "12px"
      },
      boxShadow: {
        soft: "0 16px 40px rgba(17, 17, 17, 0.07)"
      }
    }
  },
  plugins: []
};
