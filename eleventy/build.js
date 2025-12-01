import fs from "node:fs";
import * as esbuild from "esbuild";
import * as sass from "sass";

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

  // Build non-Critical CSS
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
  });
}
