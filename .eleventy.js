const execSync = require("child_process").execSync;
const pluginRss = require("@11ty/eleventy-plugin-rss");
const syntaxHighlight = require("@11ty/eleventy-plugin-syntaxhighlight");
const markdownIt = require("markdown-it");
const anchor = require("markdown-it-anchor");
const slugify = require("slugify");
const uslug = require("uslug");

const dates = require("./lib/filters/dates.js");
const isoDate = require("./lib/filters/isoDate.js");
const smartQuotes = require("./lib/filters/smart-quotes.js");

require("dotenv").config();

module.exports = (eleventyConfig) => {
  eleventyConfig.setUseGitIgnore(false);

  /* Date filter */
  eleventyConfig.addFilter("date", dates);
  eleventyConfig.addFilter("isoDate", isoDate);

  /* Get development environment variable */
  const { ELEVENTY_ENV } = process.env;

  /* Files and fonts */
  eleventyConfig.addPassthroughCopy({ "src/img": "assets/img" });
  eleventyConfig.addPassthroughCopy({ "src/fonts": "assets/fonts" });
  eleventyConfig.addPassthroughCopy({ "src/css": "assets/css" });

  /* Smart quotes filter */
  eleventyConfig.addFilter("smart", smartQuotes);

  /* Markdown Plugins */
  var uslugify = (s) => uslug(s);
  eleventyConfig.setLibrary(
	"md",
	markdownIt({
	  html: true,
	  typographer: true,
	}).use(anchor, { slugify: uslugify, tabIndex: false })
  );
  var mdIntro = markdownIt({
	typographer: true,
  });
  eleventyConfig.addFilter("markdown", (markdown) => mdIntro.render(markdown));

  eleventyConfig.addFilter("slugify", (str) => {
	return slugify(str, {
	  customReplacements: [
		["+", " plus "],
		["@", " at "],
	  ],
	  remove: /[*~.,–—()'"‘’“”!?:;]/g,
	  lower: true,
	});
  });

  /* Code syntax highlighting */
  eleventyConfig.addPlugin(syntaxHighlight, {
	templateFormats: ["njk", "md"],
	preAttributes: {
	  tabindex: 0,
	},
  });

  /* RSS */
  eleventyConfig.addPlugin(pluginRss);

  /* List all tags */
  eleventyConfig.addFilter("tags", (collection) => {
	const notRendered = [
	  "all",
	  "post",
	  "resource",
	  "testimonial",
	  "case-study",
	  "newsletter",
	  "skill",
	  "service",
	];
	return Object.keys(collection)
	  .filter((d) => !notRendered.includes(d))
	  .sort();
  });

  /* List tags belonging to a page */
  eleventyConfig.addFilter("tagsOnPage", (tags) => {
	const notRendered = [
	  "all",
	  "post",
	  "resource",
	  "testimonial",
	  "case-study",
	  "newsletter",
	  "skills",
	];
	return tags.filter((d) => !notRendered.includes(d)).sort();
  });

  /* Sort by order in front matter */
  eleventyConfig.addFilter("ordered", (collection) => {
	return collection.sort((a, b) => a.data.order - b.data.order);
  });

  /* Add limit for output */
  eleventyConfig.addFilter("limit", (arr, limit) => {
	return arr.slice(0, limit);
  });

  /* Remove current post from output */
  eleventyConfig.addFilter("removeCurrent", (arr, title) => {
	return arr.filter((item) => {
	  return item.url && item.data.title !== title;
	});
  });

  /* Get all the years that blog posts were posted */
  eleventyConfig.addFilter("getYears", (arr) => {
	const dates = arr.map((post) => post.date.getFullYear());
	const uniqueYears = [...new Set(dates)];
	return uniqueYears;
  });

  /* Filter items by year */
  eleventyConfig.addFilter("filterByYear", (arr, year) => {
	return arr.filter((item) => {
	  return item.date.getFullYear() == year;
	});
  });

  /* If promoted posts in frontmatter, output them first */
  eleventyConfig.addFilter("promoteRelated", (arr, related) => {
	const relatedPosts = arr.filter((item) => {
	  return (
		item.url &&
		(related || []).includes(
		  item.url.replace("/blog/", "").replace(".html", "")
		)
	  );
	});
	const unrelatedPosts = arr.filter((item) => {
	  return (
		item.url &&
		!(related || []).includes(
		  item.url.replace("/blog/", "").replace(".html", "")
		)
	  );
	});
	return relatedPosts.concat(unrelatedPosts);
  });

  /* Get current year for footer */
  eleventyConfig.addFilter("getCurrentYear", () => new Date().getFullYear());

  /* Get current date */
  eleventyConfig.addFilter("getCurrentDate", () => {
	const today = new Date();
	const dd = String(today.getDate()).padStart(2, "0");
	const mm = String(today.getMonth() + 1).padStart(2, "0");
	const yyyy = today.getFullYear();
	return yyyy + "/" + mm + "/" + dd;
  });

  /* Prevent duplicate updated values in feeds */
  eleventyConfig.addFilter("bumpSeconds", (date, seconds) => {
	const d = new Date(date);
	d.setSeconds(d.getSeconds() + (seconds || 0));
	return d;
  });

  /* Strip iframes from feed; improves reader compatibility */
  eleventyConfig.addTransform("sanitise-feed-html", (content, outputPath) => {
	if (!outputPath || !outputPath.endsWith("/feeds/main.xml")) return content;
	return content.replace(/<iframe\b[^>]*>.*?<\/iframe>/gis, "");
  });

  // Localhost server config
  eleventyConfig.setServerOptions({
	port: 3000,
  });

  // Build redirects file to site
  eleventyConfig.addPassthroughCopy("_redirects");

  // Index site for search
  eleventyConfig.on("eleventy.after", () => {
    execSync(
      `npx pagefind --site dist --glob "**/*.html"`,
      { encoding: "utf-8" }
    );
  });

  return {
	dir: {
	  input: "src/site",
	  output: "dist",
	  includes: "_includes",
	  layouts: "_layouts",
	},
	templateFormats: ["njk", "html", "md", "txt", "webmanifest", "ico"],
	htmlTemplateEngine: "njk",
	markdownTemplateEngine: "njk",
  };
};
