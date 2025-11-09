import 'dotenv/config';
import registerAll from './eleventy/index.js';
import returns from './eleventy/returns.js';

export default function(eleventyConfig) {
  registerAll(eleventyConfig);
  return returns;
}
