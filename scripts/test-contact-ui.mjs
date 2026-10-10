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
  browser = await chromium.launch({ ...(process.env.CHROMIUM_EXECUTABLE ? { executablePath: process.env.CHROMIUM_EXECUTABLE, args: ["--no-sandbox"] } : { channel: "chrome" }), headless: true });
  for (const width of [390, 1440]) {
    for (const scenario of ["success", "expired-initial-token", "preflight-unavailable", "preflight-network-failed", "double-submit", "rejected", "unavailable", "uncertain", "smtp-uncertain", ...(process.env.TEST_TURNSTILE === "true" ? ["challenge-failed", "challenge-expired", "challenge-expired-during-preflight", "verification-rejected", "verification-unavailable", "retry-success", "config-mismatch", "script-unavailable"] : [])]) {
      const page = await browser.newPage({ viewport: { width, height: 900 } });
      const errors = [];
      page.on("pageerror", (error) => errors.push(error.message));
      let posts = 0;
      let gets = 0;
      const requests = [];
      await page.route("**/*", async (route) => {
        if (route.request().url().startsWith("https://challenges.cloudflare.com/turnstile/v0/api.js")) {
          if (scenario === "script-unavailable") { await route.abort(); return; }
          await route.fulfill({ contentType: "text/javascript", body: `
            window.turnstile = {
              render(element, options) {
                window.__challengeOptions = options;
                if (${JSON.stringify(scenario)} === "challenge-failed") setTimeout(() => options["error-callback"](), 0);
                else setTimeout(() => options.callback("test-verification-token"), 0);
                return "test-widget";
              },
              reset() { setTimeout(() => window.__challengeOptions.callback("test-verification-token"), 0); }
            };
          ` }); return;
        }
        if (!route.request().url().startsWith(origin + "/")) { await route.abort(); return; }
        if (new URL(route.request().url()).pathname !== "/contact/send.php") { await route.continue(); return; }
        if (["unavailable", "challenge-failed", "config-mismatch", "script-unavailable"].includes(scenario)) { await route.fulfill({ status: 503, json: { ok: false } }); return; }
        if (route.request().method() === "GET") {
          gets++;
          requests.push("GET");
          if (gets === 2 && scenario === "preflight-unavailable") { await route.fulfill({ status: 503, json: { ok: false } }); return; }
          if (gets === 2 && scenario === "preflight-network-failed") { await route.abort(); return; }
          if (gets === 2 && scenario === "challenge-expired-during-preflight") {
            await page.evaluate(() => window.__challengeOptions["expired-callback"]());
          }
          await route.fulfill({ json: { ok: true, token: (gets === 1 ? "a" : "b").repeat(64), turnstileRequired: process.env.TEST_TURNSTILE === "true" && scenario !== "config-mismatch" } }); return;
        }
        posts++;
        requests.push("POST");
        // Simulate the server rejecting the old initial token after its TTL.
        // Every accepted POST must carry the fresh token fetched while locked.
        assert(route.request().postData().includes("b".repeat(64)));
        if (process.env.TEST_TURNSTILE === "true") assert(route.request().postData().includes("test-verification-token"));
        if (["verification-rejected", "verification-unavailable"].includes(scenario)) {
          await route.fulfill({ status: scenario === "verification-rejected" ? 403 : 503, json: { ok: false, uncertain: false, code: scenario.replace("-", "_") } }); return;
        }
        if (scenario === "retry-success" && posts === 1) { await route.fulfill({ status: 422, json: { ok: false } }); return; }
        if (scenario === "uncertain") { await route.abort(); return; }
        if (scenario === "smtp-uncertain") { await route.fulfill({ status: 503, json: { ok: false, uncertain: true } }); return; }
        await route.fulfill({ status: scenario === "rejected" ? 422 : 200, json: scenario === "rejected" ? { ok: false } : { ok: true, replySent: false } });
      });
      await page.goto(origin + (process.env.TEST_FORM_PATH ?? "/contact/business/"));
      const button = page.locator('[data-contact-form] button[type="submit"]');
      if (["unavailable", "challenge-failed", "config-mismatch", "script-unavailable"].includes(scenario)) {
        await page.locator('[data-state="error"]').waitFor({ state: "visible" });
        assert(await button.isDisabled());
        assert.equal(posts, 0);
      } else {
        await button.waitFor();
        await page.waitForFunction(() => !document.querySelector('[data-contact-form] button').disabled);
        if (scenario === "challenge-expired") {
          await page.evaluate(() => window.__challengeOptions["expired-callback"]());
          assert(await button.isDisabled());
          await page.evaluate(() => window.__challengeOptions.callback("test-verification-token"));
          await page.waitForFunction(() => !document.querySelector('[data-contact-form] button').disabled);
        }
        await page.locator('[name="name"]').fill("Test");
        await page.locator('[name="email"]').fill("test@example.invalid");
        await page.locator('[name="message"]').fill("Test only; no real SMTP.");
        await page.locator('[name="privacy"]').check();
        if (scenario === "double-submit") {
          await page.evaluate(() => {
            const form = document.querySelector('[data-contact-form]');
            form.dispatchEvent(new Event("submit", { cancelable: true }));
            form.dispatchEvent(new Event("submit", { cancelable: true }));
          });
        } else await button.click();
        const succeeds = ["success", "expired-initial-token", "double-submit", "challenge-expired"].includes(scenario);
        await page.locator(`[data-state="${succeeds ? "success" : "error"}"]`).waitFor({ state: "visible" });
        const noPost = ["preflight-unavailable", "preflight-network-failed", "challenge-expired-during-preflight"].includes(scenario);
        assert.equal(posts, noPost ? 0 : 1);
        assert.deepEqual(requests.slice(0, noPost ? 2 : 3), noPost ? ["GET", "GET"] : ["GET", "GET", "POST"]);
        if (noPost) {
          assert.equal(gets, 2);
          assert((await page.locator('[data-state="error"]').innerText()).includes("送信していません"));
        }
        if (succeeds || ["uncertain", "smtp-uncertain", "challenge-expired-during-preflight"].includes(scenario)) assert(await button.isDisabled());
        else await page.waitForFunction(() => !document.querySelector('[data-contact-form] button').disabled);
      }
      if (scenario === "retry-success") {
        await button.click();
        await page.locator('[data-state="success"]').waitFor({ state: "visible" });
        assert.equal(posts, 2);
        assert(await button.isDisabled());
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
