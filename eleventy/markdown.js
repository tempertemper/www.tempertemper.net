import markdownIt from 'markdown-it';
import anchor from 'markdown-it-anchor';
import uslug from 'uslug';

export default function(config) {
  const uslugify = (s) => uslug(s);

  config.setLibrary(
    'md',
    markdownIt({ html: true, typographer: true })
      .use(anchor, { slugify: uslugify, tabIndex: false })
  );

  const mdIntro = markdownIt({ typographer: true });
  config.addFilter('markdown', (markdown) => mdIntro.render(markdown));
}
