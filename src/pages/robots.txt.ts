import { withBase } from "@/utils/paths";

export const GET = ({ site, url }: { site?: URL; url: URL }) => {
  const origin = site ?? url;
  const preventIndexing = import.meta.env.PUBLIC_DEPLOY_ENV !== undefined && import.meta.env.PUBLIC_DEPLOY_ENV !== "production";
  const body = preventIndexing
    ? "User-agent: *\nDisallow: /\n"
    : `User-agent: *\nAllow: /\n\nSitemap: ${new URL(withBase("/sitemap.xml"), origin).href}\n`;

  return new Response(body, {
    headers: { "Content-Type": "text/plain; charset=utf-8" }
  });
};
