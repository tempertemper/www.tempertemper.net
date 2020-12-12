---
title: Colophon
intro: |
    tempertemper was build using Eleventy, Gulp, SCSS and carefully considered HTML.
hideIntro: true
layout: default
permalink: colophon.html
cta: true
---

This website was lovingly put together using [Eleventy, the best static site generator](/blog/website-version-5) I've come across, hands-down. The build itself is taken care of by [Gulp](https://gulpjs.com/), but I'm in the process of removing that extra dependency and moving to [npm scripts](https://css-tricks.com/why-npm-scripts/). It's all [hosted on Netlify](/blog/moving-to-netlify).


## Typography

- Headings, labels, buttons, etc.: [FS Me](https://www.fontshop.com/families/fs-me) by [Jason Smith](https://www.fontshop.com/designers/jason-smith)
- Most other text: [Georgia](https://en.wikipedia.org/wiki/Georgia_(typeface)) by [Matthew Carter](https://en.wikipedia.org/wiki/Matthew_Carter)
- Code examples: [Menlo](https://en.wikipedia.org/wiki/Menlo_%28typeface%29) by [Jim Lyles](https://www.myfonts.com/person/Jim_Lyles/)


## Styling

The styling is written in [SCSS](https://sass-lang.com) with a smattering of [Post CSS](https://postcss.org). The Sass tools I'm using are:

- [Susy](https://www.oddbird.net/susy/) by Oddbird, though it's [on my to-do list](https://github.com/tempertemper/tempertemper.net/projects/1?fullscreen=true) to refactor the layout using CSS Grid
- [Modular Scale](https://github.com/modularscale/modularscale-sass) by [Scott Kellum](https://scottkellum.com), for a proper type scale


## Scripts

There is no JavaScript at all on this website! Analytics are taken care of by [Netlify Analytics](/blog/ditching-google-analytics-in-favour-of-netlify-analytics) and the rest is just plain old HTML and CSS.
