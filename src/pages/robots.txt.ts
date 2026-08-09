import { withBase } from "@/utils/paths";

export const GET = ({ site, url }: { site?: URL; url: URL }) => {
  const origin = site ?? url;
  const body = `User-agent: *\nAllow: /\n\nSitemap: ${new URL(withBase("/sitemap.xml"), origin).href}\n`;

  return new Response(body, {
    headers: { "Content-Type": "text/plain; charset=utf-8" }
  });
};
