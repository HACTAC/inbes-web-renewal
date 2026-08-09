import { withBase } from "@/utils/paths";

const routes = [
  "/",
  "/products/",
  "/services/",
  "/process/",
  "/works/",
  "/company/",
  "/contact/",
  "/contact/business/",
  "/contact/support/",
  "/privacy/",
  "/cookies/"
];

export const GET = ({ site, url }: { site?: URL; url: URL }) => {
  const origin = site ?? url;
  const urls = routes
    .map((route) => `  <url><loc>${new URL(withBase(route), origin).href}</loc></url>`)
    .join("\n");
  const body = `<?xml version="1.0" encoding="UTF-8"?>\n<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">\n${urls}\n</urlset>`;

  return new Response(body, {
    headers: { "Content-Type": "application/xml; charset=utf-8" }
  });
};
