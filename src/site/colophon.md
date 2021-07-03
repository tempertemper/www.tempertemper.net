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


## Styling

The styling is written in [SCSS](https://sass-lang.com) with [Post CSS](https://postcss.org) being used to add vendor prefixes for deeper browser support.

I use one tool, and that's [Modular Scale](https://github.com/modularscale/modularscale-sass) by [Scott Kellum](https://scottkellum.com), which allows me to use for a proper typographic scale.


## Scripts

There is only a tiny amount of JavaScript on this website; used to enhance some keyboard behaviour on [links that look like buttons](/blog/when-design-breaks-semantics) for better accessibility. Analytics are taken care of on the server-side by [Netlify Analytics](/blog/ditching-google-analytics-in-favour-of-netlify-analytics), so there's nothing running in the browser; just plain old HTML and CSS.
