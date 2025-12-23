import registerFilters from './filters.js';
import registerMarkdown from './markdown.js';
import registerPlugins from './plugins.js';
import registerTransforms from './transforms.js';
import registerBuild from './build.js';
import returns from './returns.js';

// In-memory build timestamp for cache-busting
let buildTimestamp = Date.now();

export default function(config) {

  // Watch JS for inline bundle rebuild
  config.addWatchTarget('src/js');
  config.addWatchTarget('src/css');

  // Build timestamp for cache‑busting
  config.on('eleventy.before', () => {
    buildTimestamp = Date.now();
  });

  config.addGlobalData('build', () => ({
    timestamp: buildTimestamp
  }));

  // Live reload when generated inline JS changes
  config.setServerOptions({
    port: 3000,
    files: [
      'src/site/_includes/scripts.js',
      'src/site/_includes/critical.css'
    ]
  });

  // Module registrations
  registerFilters(config);
  registerMarkdown(config);
  registerPlugins(config);
  registerTransforms(config);
  registerBuild(config);

  // Directory + template config
  return returns;
}
