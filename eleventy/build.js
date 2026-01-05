import fs from "node:fs";
import path from "node:path";
import * as esbuild from "esbuild";
import * as sass from "sass";
import { exec as _exec } from "node:child_process";
import { promisify } from "node:util";
import Image from "@11ty/eleventy-img";

const exec = promisify(_exec);

const IMAGE_EXTENSIONS = new Set([".jpg", ".jpeg", ".png"]);
const IMAGE_SOURCE_DIRS = [
  "src/img/blog",
  "src/img/case-studies",
  "src/img/resources"
];
const IMAGE_CACHE_PATH = ".cache/eleventy-img-manifest.json";

async function readImageCache() {
  try {
    const raw = await fs.promises.readFile(IMAGE_CACHE_PATH, "utf8");
    return JSON.parse(raw);
  } catch (error) {
    if (error.code === "ENOENT") {
      return { images: {} };
    }
    throw error;
  }
}

async function writeImageCache(cache) {
  await fs.promises.mkdir(path.dirname(IMAGE_CACHE_PATH), { recursive: true });
  await fs.promises.writeFile(
    IMAGE_CACHE_PATH,
    JSON.stringify(cache, null, 2)
  );
}

async function collectImages(dirPath) {
  try {
    const entries = await fs.promises.readdir(dirPath, { withFileTypes: true });
    const files = await Promise.all(entries.map(async (entry) => {
      const entryPath = path.join(dirPath, entry.name);
      if (entry.isDirectory()) {
        return collectImages(entryPath);
      }
      const extension = path.extname(entry.name).toLowerCase();
      return IMAGE_EXTENSIONS.has(extension) ? [entryPath] : [];
    }));
    return files.flat();
  } catch (error) {
    if (error.code === "ENOENT") {
      return [];
    }
    throw error;
  }
}

export default function(config) {

  // Build inline includes
  config.on("eleventy.before", async () => {

    // Javascript
    await esbuild.build({
      entryPoints: ["src/js/scripts.js"],
      bundle: true,
      minify: true,
      sourcemap: false,
      outfile: "src/site/_includes/scripts.js",
      format: "iife",
      logLevel: "silent"
    });
    console.log("✓ JS bundled → src/site/_includes/scripts.js");

    // CSS (Critical)
    const result = sass.compile("src/css/critical.scss", {
      loadPaths: ["src/css"],
      style: "compressed"
    });
    fs.writeFileSync("src/site/_includes/critical.css", result.css);
    console.log("✓ Critical CSS → src/site/_includes/critical.css");

    // CSS (Sponsor)
    const sponsorResult = sass.compile("src/css/sponsor.scss", {
      loadPaths: ["src/css"],
      style: "compressed"
    });
    fs.writeFileSync("src/site/_includes/sponsor.css", sponsorResult.css);
    console.log("✓ Sponsor CSS → src/site/_includes/sponsor.css");

    // Static image variants for content directories
    const imageCache = await readImageCache();
    const imageFilesNested = await Promise.all(
      IMAGE_SOURCE_DIRS.map((dirPath) => collectImages(dirPath))
    );
    const imageFiles = imageFilesNested.flat();

    const processedImages = await Promise.all(imageFiles.map(async (imagePath) => {
      const relativePath = path.relative("src/img", imagePath);
      const outputDir = path.join("dist/assets/img", path.dirname(relativePath));
      const urlPath = path.posix.join(
        "/assets/img",
        path.dirname(relativePath).split(path.sep).join(path.posix.sep)
      );
      const cacheKey = relativePath.split(path.sep).join(path.posix.sep);
      const sourceStat = await fs.promises.stat(imagePath);
      const cached = imageCache.images[cacheKey];
      const extension = path.extname(imagePath);
      const name = path.basename(imagePath, extension);
      const webpPath = path.join(outputDir, `${name}.webp`);
      const avifPath = path.join(outputDir, `${name}.avif`);

      const outputsExist = await Promise.all([
        fs.promises
          .stat(webpPath)
          .then(() => true)
          .catch(() => false),
        fs.promises
          .stat(avifPath)
          .then(() => true)
          .catch(() => false)
      ]).then((results) => results.every(Boolean));

      const cacheHit = cached
        && cached.mtimeMs === sourceStat.mtimeMs
        && cached.size === sourceStat.size;

      if (!cacheHit || !outputsExist) {
        await Image(imagePath, {
          formats: ["avif", "webp"],
          widths: [null],
          outputDir,
          urlPath,
          filenameFormat: (id, src, width, format) => {
            const extension = path.extname(src);
            const name = path.basename(src, extension);
            return `${name}.${format}`;
          }
        });
      }

      imageCache.images[cacheKey] = {
        mtimeMs: sourceStat.mtimeMs,
        size: sourceStat.size
      };

      return !cacheHit || !outputsExist;
    }));

    if (imageFiles.length > 0) {
      const processedCount = processedImages.filter(Boolean).length;
      if (processedCount > 0) {
        console.log("✓ Image formats → dist/assets/img");
      } else {
        console.log("✓ Image formats skipped (no changes)");
      }
    }

    if (imageFiles.length > 0) {
      await writeImageCache(imageCache);
    }
  });


  // Build non-Critical CSS and search index
  config.on("eleventy.after", async () => {
    const result = sass.compile("src/css/non-critical.scss", {
      loadPaths: ["src/css"],
      style: "compressed"
    });
    fs.mkdirSync("dist/assets/css", { recursive: true });
    fs.writeFileSync(
      "dist/assets/css/non-critical.css",
      result.css
    );
    console.log("✓ Non-critical CSS → dist/assets/css/non-critical.css");

    // Build search index
    await exec('npx pagefind --site dist --glob "**/*.html"');
    console.log("✓ Search index → dist/pagefind");
  });
}
