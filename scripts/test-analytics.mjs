import assert from "node:assert/strict";
import { readFileSync } from "node:fs";
import vm from "node:vm";

const html = readFileSync(new URL("../dist/index.html", import.meta.url), "utf8");
const scripts = [...html.matchAll(/<script\b[^>]*>([\s\S]*?)<\/script>/g)];
const analytics = scripts.filter((match) => match[1].includes("allow_google_signals"));
assert.equal(analytics.length, 1);

for (const hostname of ["inbes.jp", "inbes-dev.pages.dev", "127.0.0.1"]) {
  const tags = [];
  const window = {};
  vm.runInNewContext(analytics[0][1], {
    window,
    URL,
    location: { hostname, href: `https://${hostname}/contact/business/?email=test%40example.invalid#details` },
    document: {
      referrer: "https://example.invalid/path?private=value#fragment",
      createElement: () => ({}),
      head: { appendChild: (tag) => tags.push(tag) }
    }
  });
  if (hostname !== "inbes.jp") {
    assert.equal(tags.length, 0);
    assert.equal(window.dataLayer, undefined);
    continue;
  }
  assert.equal(tags.length, 1);
  assert.match(tags[0].src, /^https:\/\/www\.googletagmanager\.com\/gtag\/js\?id=G-[A-Z0-9]+$/);
  const config = [...window.dataLayer[1]];
  assert.equal(config[0], "config");
  assert.equal(config[2].page_location, "https://inbes.jp/contact/business/");
  assert.equal(config[2].page_referrer, "https://example.invalid/path");
  assert.equal(config[2].allow_google_signals, false);
  assert.equal(config[2].allow_ad_personalization_signals, false);
  assert.deepEqual(Object.keys(config[2]).sort(), [
    "allow_ad_personalization_signals", "allow_google_signals", "page_location", "page_referrer"
  ]);
}
console.log("Analytics checks passed: production-only, one config, sanitized URLs, no form values.");
