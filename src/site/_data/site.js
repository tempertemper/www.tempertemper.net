export default {
  title: 'tempertemper',
  company: 'tempertemper Web Design Ltd',
  url: 'https://www.tempertemper.net',
  baseurl: '',
  repo: 'https://github.com/tempertemper/tempertemper-website',
  comments: false,
  author: {
    name: 'Martin Underhill',
    twitter: '@tempertemper',
    mastodon: '@tempertemper@mastodon.social',
    linkedin: 'tempertemper',
    blusky: '@tempertemper.bsky.social',
    email: 'hello+website@tempertemper.net',
  },
  sponsor: {
    name: 'Example Corp',
    logo: 'example-corp.png',
    url: 'https://www.example.com',
    message: "Example Corp is an entirely fictional company, used here to demonstrate how sponsor messages look in practice. Any resemblance to real businesses is purely coincidental.",
    display: false
  },
  env: process.env.ELEVENTY_ENV || 'not_development',
  og_locale: 'en_GB',
};
