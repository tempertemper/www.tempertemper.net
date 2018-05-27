'use strict';

const paths = {
  build: `${__dirname}/build`,
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

const nunjucks = require('@frctl/nunjucks')({
  filters: {
    markdown(str) {
      return markdown.render(str);
    },
    markdownInline(str) {
      return markdown.renderInline(str);
    },
    slugify(str) {
      return str.toLowerCase().replace(/[^\w]+/g, '' + '/\s/g','-');
    }
  },
  paths: [`${paths.static}/assets/img/svg`]
});

// Project config
fractal.set('project.title', 'tempertemper pattern library');

// Components config
fractal.components.engine(nunjucks);
fractal.components.set('ext', '.njk');
fractal.components.set('default.preview', '@preview');
fractal.components.set('default.status', 'wip');
fractal.components.set('label', 'Patterns');
fractal.components.set('path', `${paths.src}/patterns`);

// Docs config
fractal.docs.engine(nunjucks);
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
