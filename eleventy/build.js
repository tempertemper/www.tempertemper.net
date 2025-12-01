import fs from "node:fs";
import * as esbuild from "esbuild";
import * as sass from "sass";
import { exec as _exec } from "node:child_process";
import { promisify } from "node:util";

const exec = promisify(_exec);

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
    const result = sass.compile("src/scss/critical.scss", {
      loadPaths: ["src/scss"],
      style: "compressed"
    });
    fs.writeFileSync("src/site/_includes/critical.css", result.css);
    console.log("✓ Critical CSS → src/site/_includes/critical.css");
  });

  // Build non-Critical CSS and search index
  config.on("eleventy.after", async () => {
    const result = sass.compile("src/scss/non-critical.scss", {
      loadPaths: ["src/scss"],
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
