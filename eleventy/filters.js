import slugifyLib from 'slugify';
import dates from '../lib/filters/dates.js';
import isoDate from '../lib/filters/isoDate.js';
import smartQuotes from '../lib/filters/smart-quotes.js';

export default function(config) {
  // Dates
  config.addFilter('date', dates);
  config.addFilter('isoDate', isoDate);

  // Smart quotes
  config.addFilter('smart', smartQuotes);

  // Slugify with custom rules
  slugifyLib.extend({
    '+': ' plus ',
    '@': ' at ',
  });
  config.addFilter('slugify', (str) =>
    slugifyLib(str, {
      remove: /[*~.,–—()'"‘’“”!?:;]/g,
      lower: true,
      trim: true,
    })
  );

  // List all tags (hide internal)
  config.addFilter('tags', (collection) => {
    const notRendered = [
      'all','post','resource','testimonial','case-study','newsletter','skill','service',
    ];
    return Object.keys(collection).filter((d) => !notRendered.includes(d)).sort();
  });

  // Tags on page (hide internal)
  config.addFilter('tagsOnPage', (tags) => {
    const notRendered = [
      'all','post','resource','testimonial','case-study','newsletter','skills',
    ];
    return tags.filter((d) => !notRendered.includes(d)).sort();
  });

  // Sort by front matter order
  config.addFilter('ordered', (collection) =>
    collection.sort((a, b) => a.data.order - b.data.order)
  );

  // Limit
  config.addFilter('limit', (arr, limit) => arr.slice(0, limit));

  // Remove current post
  config.addFilter('removeCurrent', (arr, title) =>
    arr.filter((item) => item.url && item.data.title !== title)
  );

  // Blog years
  config.addFilter('getYears', (arr) => {
    const years = arr.map((post) => post.date.getFullYear());
    return [...new Set(years)];
  });

  // Filter by year
  config.addFilter('filterByYear', (arr, year) =>
    arr.filter((item) => item.date.getFullYear() == year)
  );

  // Promote related first (keeps your URL normalization)
  config.addFilter('promoteRelated', (arr, related) => {
    const rel = Array.isArray(related) ? related : [];
    const normalise = (u) => u.replace('/blog/', '').replace('.html', '');
    const relatedPosts = arr.filter((item) => item.url && rel.includes(normalise(item.url)));
    const unrelatedPosts = arr.filter((item) => item.url && !rel.includes(normalise(item.url)));
    return relatedPosts.concat(unrelatedPosts);
  });

  // Current year/date
  config.addFilter('getCurrentYear', () => new Date().getFullYear());
  config.addFilter('getCurrentDate', () => {
    const d = new Date();
    const dd = String(d.getDate()).padStart(2, '0');
    const mm = String(d.getMonth() + 1).padStart(2, '0');
    const yyyy = d.getFullYear();
    return `${yyyy}/${mm}/${dd}`;
  });

  // Bump seconds
  config.addFilter('bumpSeconds', (date, seconds) => {
    const d = new Date(date);
    d.setSeconds(d.getSeconds() + (seconds || 0));
    return d;
  });

  // Strip emojis
  config.addFilter('stripEmojis', value => {
      if (!value) return value;
      return value.replace(
          /[\p{Emoji_Presentation}\p{Emoji}\uFE0F]/gu,
          ''
      );
  });
}
