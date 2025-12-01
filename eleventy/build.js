import { execSync } from "node:child_process";
import * as esbuild from "esbuild";

export default function(config) {

  // Build JavaScript
  config.on("eleventy.before", async () => {
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
  });
}
