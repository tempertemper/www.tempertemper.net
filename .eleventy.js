module.exports = function(eleventyConfig) {

  /* Date filter */
  eleventyConfig.addFilter("date", require("./lib/filters/dates.js") );

  /* Data */
  eleventyConfig.setDataDeepMerge(true);

  /* Smart quotes filter */
  const smartypants = require("smartypants");
  eleventyConfig.addFilter("smart", function(str) {
    return smartypants.smartypants(str, 'qDe');
  });

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

  eleventyConfig.addFilter("twitterLink", function(str) {
    return "https://twitter.com/" + str.replace("@", "");
  });

  const slugify = require("slugify");
  eleventyConfig.addFilter("slug", function(str) {
    return slugify(str, {
      replacement: "-",
      remove: /[*+~.,()'"‘’“”!?:@]/g,
      lower: true
    });
  });

  /* Code syntax highlighting */
  const syntaxHighlight = require("@11ty/eleventy-plugin-syntaxhighlight");
  eleventyConfig.addPlugin(syntaxHighlight, {
    templateFormats: ["njk", "md"],
  });

  /* RSS */
  const pluginRss = require("@11ty/eleventy-plugin-rss");
  eleventyConfig.addPlugin(pluginRss);

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

  eleventyConfig.addFilter("getCurrentYear", function() {
    return new Date().getFullYear();
  });

  return {
    dir: {
      input: "src/site",
      output: "dist",
      includes: "_includes",
      layouts: "_layouts"
    },
    templateFormats : ["njk", "html", "md", "txt"],
    htmlTemplateEngine : "njk",
    markdownTemplateEngine : "njk"
  };
};
