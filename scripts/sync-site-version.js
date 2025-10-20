const fs = require("fs");

const pkg = JSON.parse(fs.readFileSync("package.json", "utf8"));
const version = pkg.version;

const sitePath = "src/site/_data/site.js";
let content = fs.readFileSync(sitePath, "utf8");

// replace the existing version value
content = content.replace(/"version":\s*"[^"]+"/, `"version": "${version}"`);

fs.writeFileSync(sitePath, content, "utf8");
console.log(`site.js updated to ${version}`);
