import registerFilters from './filters.js';
import registerMarkdown from './markdown.js';
import registerPlugins from './plugins.js';
import registerTransforms from './transforms.js';
import registerServer from './server.js';
import registerBuild from './build.js';
import returns from './returns.js';

export default function(config) {

  // Watch JS for inline bundle rebuild
  config.addWatchTarget('src/js');
  config.addWatchTarget('src/scss');

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
  registerServer(config);
  registerBuild(config);

  // Directory + template config
  return returns;
}
