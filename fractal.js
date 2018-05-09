'use strict';

const fractal = module.exports = require('@frctl/fractal').create();

fractal.components.engine('@frctl/nunjucks');
fractal.components.set('ext', '.njk');

fractal.set('project.title', 'tempertemper pattern library');

const mandelbrot = require('@frctl/mandelbrot');
const tempertemperTheme = mandelbrot({
  favicon: '../img/icons/favicon.ico',
  skin: "white"
});
fractal.web.theme(tempertemperTheme);

fractal.components.set('path', __dirname + '/src/patterns');

fractal.docs.set('path', __dirname + '/src/docs');

fractal.web.set('static.path', __dirname + '/web');
fractal.web.set('server.syncOptions', {
  open: false,
  injectChanges: true,
  notify: true
});
fractal.components.set('default.preview', '@preview');
fractal.components.set('default.status', 'wip');
fractal.docs.set('default.status', 'draft');
fractal.components.set('label', 'Patterns');

// fractal.web.set('builder.dest', __dirname + '/build');
