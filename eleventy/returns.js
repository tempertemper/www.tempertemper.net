export default {
  dir: {
    input: 'src/site',
    output: 'dist',
    includes: '_includes',
    layouts: '_layouts',
  },
  templateFormats: [
    'njk',
    'html',
    'md',
    'txt',
    'webmanifest',
    'ico'
  ],
  htmlTemplateEngine: 'njk',
  markdownTemplateEngine: 'njk',
};
