import assert from "node:assert/strict";
import { createServer } from "node:http";
import { readFile } from "node:fs/promises";
import { resolve, extname } from "node:path";
import { pathToFileURL } from "node:url";

const playwrightPath = process.env.PLAYWRIGHT_MODULE;
assert(playwrightPath, "Set PLAYWRIGHT_MODULE to an installed Playwright module");
const { chromium } = await import(pathToFileURL(resolve(playwrightPath)).href);
const root = resolve("dist");
const server = createServer(async (req, res) => {
  const url = new URL(req.url, "http://localhost");
  const path = resolve(root, "." + (url.pathname.endsWith("/") ? url.pathname + "index.html" : url.pathname));
  if (!path.startsWith(root + "/")) { res.writeHead(403).end(); return; }
  try {
    const bytes = await readFile(path);
    const types = { ".html": "text/html; charset=utf-8", ".js": "text/javascript", ".css": "text/css", ".svg": "image/svg+xml", ".png": "image/png", ".webp": "image/webp" };
    res.writeHead(200, { "Content-Type": types[extname(path)] ?? "application/octet-stream" });
    res.end(bytes);
  } catch { res.writeHead(404).end(); }
});
await new Promise((done) => server.listen(0, "127.0.0.1", done));
const origin = `http://127.0.0.1:${server.address().port}`;
let browser;
let checks = 0;
try {
  browser = await chromium.launch({ channel: "chrome", headless: true });
  for (const width of [390, 1440]) {
    for (const scenario of ["success", "rejected", "unavailable", "uncertain", "smtp-uncertain"]) {
      const page = await browser.newPage({ viewport: { width, height: 900 } });
      const errors = [];
      page.on("pageerror", (error) => errors.push(error.message));
      let posts = 0;
      await page.route("**/*", async (route) => {
        if (!route.request().url().startsWith(origin + "/")) { await route.abort(); return; }
        if (new URL(route.request().url()).pathname !== "/contact/send.php") { await route.continue(); return; }
        if (scenario === "unavailable") { await route.fulfill({ status: 503, json: { ok: false } }); return; }
        if (route.request().method() === "GET") { await route.fulfill({ json: { ok: true, token: "a".repeat(64) } }); return; }
        posts++;
        if (scenario === "uncertain") { await route.abort(); return; }
        if (scenario === "smtp-uncertain") { await route.fulfill({ status: 503, json: { ok: false, uncertain: true } }); return; }
        await route.fulfill({ status: scenario === "rejected" ? 422 : 200, json: scenario === "rejected" ? { ok: false } : { ok: true, replySent: false } });
      });
      await page.goto(origin + "/contact/business/");
      const button = page.locator('[data-contact-form] button[type="submit"]');
      if (scenario === "unavailable") {
        await page.locator('[data-state="error"]').waitFor({ state: "visible" });
        assert(await button.isDisabled());
        assert.equal(posts, 0);
      } else {
        await button.waitFor();
        await page.waitForFunction(() => !document.querySelector('[data-contact-form] button').disabled);
        await page.locator('[name="name"]').fill("Test");
        await page.locator('[name="email"]').fill("test@example.invalid");
        await page.locator('[name="message"]').fill("Test only; no real SMTP.");
        await page.locator('[name="privacy"]').check();
        await button.click();
        await page.locator(`[data-state="${scenario === "success" ? "success" : "error"}"]`).waitFor({ state: "visible" });
        assert.equal(posts, 1);
        if (["success", "uncertain", "smtp-uncertain"].includes(scenario)) assert(await button.isDisabled());
        else await page.waitForFunction(() => !document.querySelector('[data-contact-form] button').disabled);
      }
      assert.deepEqual(errors, []);
      assert(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth));
      checks++;
      await page.close();
    }
  }
  console.log(JSON.stringify({ checks, passed: true, realMailSent: false }));
} finally {
  await browser?.close();
  await new Promise((done) => server.close(done));
}
