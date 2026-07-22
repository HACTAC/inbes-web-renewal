import { mkdir, readFile, writeFile } from "node:fs/promises";
import path from "node:path";

const icons = [
  "arrow-right",
  "arrow-up-right",
  "battery-charging",
  "car",
  "check",
  "check-circle",
  "computer",
  "download",
  "info-circle",
  "media-image",
  "nav-arrow-left",
  "nav-arrow-right",
  "package",
  "plus",
  "search",
  "shield-check",
  "warning-triangle",
  "xmark",
  "xmark-circle",
];

const sourceDirectory = path.resolve("node_modules/iconoir/icons/regular");
const outputDirectory = path.resolve("design-system/site/assets/icons");
const outputFile = path.join(outputDirectory, "iconoir.svg");

const symbols = await Promise.all(
  icons.map(async (name) => {
    const source = await readFile(path.join(sourceDirectory, `${name}.svg`), "utf8");
    const content = source.match(/<svg[^>]*>([\s\S]*?)<\/svg>/)?.[1]?.trim();

    if (!content) throw new Error(`Could not parse Iconoir icon: ${name}`);

    return `  <symbol id="${name}" viewBox="0 0 24 24">\n${content}\n  </symbol>`;
  }),
);

const sprite = `<!-- Iconoir 7.11.0 / MIT License / https://iconoir.com -->
<svg xmlns="http://www.w3.org/2000/svg">
${symbols.join("\n")}
</svg>
`;

await mkdir(outputDirectory, { recursive: true });
await writeFile(outputFile, sprite);

console.log(`Generated ${icons.length} Iconoir symbols: ${outputFile}`);
