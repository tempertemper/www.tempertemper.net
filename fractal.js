'use strict';

const paths = {
  build: `${__dirname}/patterns`,
  src: `${__dirname}/src`,
  static: `${__dirname}/tmp`
};

const fractal = require('@frctl/fractal').create();

const mandelbrot = require('@frctl/mandelbrot')({
  favicon: '/assets/img/icons/favicon.ico',
  lang: 'en-gb',
  skin: 'white'
});

const markdown = require('markdown-it')({
  html: true,
  xhtmlOut: true,
  typographer: true
});

const hbs = require('@frctl/handlebars')({
  helpers: {
    markdown: ({ data: { root: { content } } }) => markdown.render(content)
  }
});

// Project config
fractal.set('project.title', 'tempertemper pattern library');

// Components config
fractal.components.engine(hbs);
fractal.components.set('ext', '.hbs');
fractal.components.set('default.preview', '@preview');
fractal.components.set('default.status', 'wip');
fractal.components.set('label', 'Patterns');
fractal.components.set('path', `${paths.src}/patterns`);

// Docs config
fractal.docs.engine(hbs);
fractal.docs.set('ext', '.md');
fractal.docs.set('default.status', 'draft');
fractal.docs.set('path', `${paths.src}/docs`);

// Web UI config
fractal.web.theme(mandelbrot);
fractal.web.set('static.path', paths.static);
fractal.web.set('server.syncOptions', {
  open: false,
  injectChanges: true,
  notify: true,
  port: 8080
});
fractal.web.set('builder.dest', paths.build);
fractal.web.set('builder.urls.ext', null);

// Export config
module.exports = fractal;
