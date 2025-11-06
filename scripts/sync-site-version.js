import { readFile, writeFile } from 'node:fs/promises';
import pkg from '../package.json' with { type: 'json' };

const sitePath = new URL('../src/site/_data/site.js', import.meta.url);
const version = pkg.version;

let content = await readFile(sitePath, 'utf8');

// replace the existing version value (single or double quotes)
content = content.replace(/version:\s*['"][^'"]+['"]/, `version: '${version}'`);

await writeFile(sitePath, content, 'utf8');

console.log(`site.js updated to ${version}`);
