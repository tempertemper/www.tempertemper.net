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
    const imageFilesNested = await Promise.all(
      IMAGE_SOURCE_DIRS.map((dirPath) => collectImages(dirPath))
    );
    const imageFiles = imageFilesNested.flat();

    await Promise.all(imageFiles.map(async (imagePath) => {
      const relativePath = path.relative("src/img", imagePath);
      const outputDir = path.join("dist/assets/img", path.dirname(relativePath));
      const urlPath = path.posix.join(
        "/assets/img",
        path.dirname(relativePath).split(path.sep).join(path.posix.sep)
      );
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
    }));

    if (imageFiles.length > 0) {
      console.log("✓ Image formats → dist/assets/img");
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
