import pluginRss from '@11ty/eleventy-plugin-rss';
import syntaxHighlight from '@11ty/eleventy-plugin-syntaxhighlight';

export default function(config) {
  // Passthroughs
  config.addPassthroughCopy({ 'src/img': 'assets/img' });
  config.addPassthroughCopy({ 'src/fonts': 'assets/fonts' });
  config.addPassthroughCopy({ 'src/css': 'assets/css' });
  config.addPassthroughCopy('_redirects');

  // Syntax highlighting
  config.addPlugin(syntaxHighlight, {
    templateFormats: ['njk', 'md'],
    preAttributes: { tabindex: 0 },
  });

  // RSS
  config.addPlugin(pluginRss);
}
