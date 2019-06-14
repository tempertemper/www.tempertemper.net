module.exports = function(eleventyConfig) {

  /* Date filter */
  eleventyConfig.addFilter("date", require("./filters/dates.js") );

  /* Data */
  eleventyConfig.setDataDeepMerge(true);

  /* Markdown Plugins */
  var uslug = require('uslug');
  var uslugify = s => uslug(s);
  var anchor = require('markdown-it-anchor');
  var markdownIt = require("markdown-it");
  eleventyConfig.setLibrary("md", markdownIt({
    html: true,
    typographer: true
  }).use(anchor, {slugify: uslugify}));
  var mdIntro = markdownIt({
    typographer: true
  });
  eleventyConfig.addFilter("markdown", function(markdown) {
    return mdIntro.render(markdown);
  });

  /* List all tags */
  eleventyConfig.addFilter("tags", function(collection) {
    const notRendered = ['all', 'post', 'resource', 'testimonial'];
    return Object.keys(collection)
      .filter(d => !notRendered.includes(d))
      .sort();
  });

  /* List tags belonging to a page */
  eleventyConfig.addFilter("tagsOnPage", function(tags) {
    const notRendered = ['all', 'post', 'resource', 'testimonial'];
    return tags
      .filter(d => !notRendered.includes(d))
      .sort();
  });

  return {
    dir: {
      input: "src/site",
      output: "dist",
      includes: "_includes",
      layouts: "_layouts"
    },
    templateFormats : ["njk", "html", "md"],
    htmlTemplateEngine : "njk",
    markdownTemplateEngine : "njk"
  };
};
