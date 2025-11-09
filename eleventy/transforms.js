export default function(config) {
  // Strip iframes from the main feed only
  config.addTransform('sanitise-feed-html', (content, outputPath) => {
    if (!outputPath || !outputPath.endsWith('/feeds/main.xml')) return content;
    return content.replace(/<iframe\b[^>]*>.*?<\/iframe>/gis, '');
  });
}
