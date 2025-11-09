// eleventy/index.js
import registerFilters from './filters.js';
import registerMarkdown from './markdown.js';
import registerPlugins from './plugins.js';
import registerTransforms from './transforms.js';
import registerServer from './server.js';
import registerBuild from './build.js';

export default function registerAll(config) {
  registerFilters(config);
  registerMarkdown(config);
  registerPlugins(config);
  registerTransforms(config);
  registerServer(config);
  registerBuild(config);
}
