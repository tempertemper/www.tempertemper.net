import pluginRss from '@11ty/eleventy-plugin-rss';
import syntaxHighlight from '@11ty/eleventy-plugin-syntaxhighlight';
import { eleventyImageTransformPlugin } from '@11ty/eleventy-img';
import path from 'node:path';

export default function(config) {
  // Passthroughs
  config.addPassthroughCopy({ 'src/img': 'assets/img' });
  config.addPassthroughCopy({ 'src/fonts': 'assets/fonts' });
  config.addPassthroughCopy({ 'src/css/print.css': 'assets/css/print.css' });
  config.addPassthroughCopy({ 'src/site/favicon.ico': 'favicon.ico' });
  config.addPassthroughCopy({ 'src/site/manifest.webmanifest': 'manifest.webmanifest' });
  config.addPassthroughCopy('_redirects');

  // Syntax highlighting
  config.addPlugin(syntaxHighlight, {
    templateFormats: ['njk', 'md'],
    preAttributes: { tabindex: 0 },
  });

  // RSS
  config.addPlugin(pluginRss);

  // Rewrite /assets/img/* URLs to filesystem paths under src/img so Eleventy Image can read sources
  if (config.htmlTransformer && 'addPosthtmlPlugin' in config.htmlTransformer) {
    config.htmlTransformer.addPosthtmlPlugin('html', (context) => {
      const inputPath = context?.page?.inputPath;
      const baseDir = inputPath ? path.dirname(inputPath) : process.cwd();
      return (tree) => {
        tree.match({ tag: 'img' }, (node) => {
          const src = node?.attrs?.src;
          if (!src || node?.attrs?.['eleventy:ignore'] !== undefined || src.startsWith('data:') || src.startsWith('http')) {
            return node;
          }

          const cleanedSrc = src.replace(/^\/+/, '');
          if (cleanedSrc.endsWith('.webp') || cleanedSrc.endsWith('.avif')) {
            node.attrs['eleventy:ignore'] = '';
            return node;
          }
          if (cleanedSrc.startsWith('assets/img/')) {
            const imagePath = cleanedSrc.replace(/^assets\/img\//, '');
            const absolutePath = path.join(process.cwd(), 'src/img', imagePath);
            node.attrs.src = path.relative(baseDir, absolutePath);
          }

          return node;
        });
        return tree;
      };
    }, { priority: 10 });

  }

  // Eleventy image build
  config.addPlugin(eleventyImageTransformPlugin, {
    urlPath: '/assets/img/',
    outputDir: 'dist/assets/img',
    formats: ['avif', 'webp', 'auto'],
    widths: ['auto'],
    htmlOptions: {
      imgAttributes: {
        decoding: 'async',
        loading: 'lazy',
      },
    },
  });

  config.addTransform('strip-srcset-widths', (content, outputPath) => {
    if (!outputPath || !outputPath.endsWith('.html')) {
      return content;
    }

    // Remove width descriptors so width/height attributes control layout.
    return content.replace(/srcset="([^"]+)"/g, (_, value) => {
      const cleaned = value
        .split(',')
        .map((entry) => entry.trim().split(/\s+/)[0])
        .filter(Boolean)
        .join(', ');
      return `srcset="${cleaned}"`;
    });
  });
}
