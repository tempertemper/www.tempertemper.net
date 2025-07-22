---
title: Colophon
intro: |
    tempertemper was build using Eleventy, Gulp, SCSS and carefully considered HTML.
hideIntro: true
layout: default
permalink: colophon.html
cta: true
---

This website was lovingly put together using [Eleventy, the best static site generator](/blog/website-version-5) I've come across, hands-down. The build itself is taken care of by [npm scripts](https://css-tricks.com/why-npm-scripts/), and it's all [hosted on Netlify](/blog/moving-to-netlify).


## Typography

- General text, including headings: [FS Me](https://www.fontshop.com/families/fs-me) by [Jason Smith](https://www.fontshop.com/designers/jason-smith)
- Code examples: [Menlo](https://en.wikipedia.org/wiki/Menlo_%28typeface%29) by [Jim Lyles](https://www.myfonts.com/person/Jim_Lyles/)
- Mobile typography scale: major third ([4:5](https://www.modularscale.com/?1&em&1.25))
- Typography scale where there's more breathing room: golden ratio ([1:1.618](https://www.modularscale.com/?1&em&1.618))


## Styling

The styling is written in [SCSS](https://sass-lang.com) and that's about it!


## Scripts

There is only a tiny amount of JavaScript on this website; used to enhance some keyboard behaviour for:

- [links that look like buttons](/blog/when-design-breaks-semantics)
- [data tables that are wider than their container](/blog/an-enhancement-to-accessible-responsive-tables)
- codeblocks that are wider than their container

Analytics are taken care of on the server-side by [Netlify Analytics](/blog/ditching-google-analytics-in-favour-of-netlify-analytics), so there's nothing running in the browser; just plain old HTML and CSS.
