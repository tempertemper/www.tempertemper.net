---
title: Website version 6
intro: |
    The last major version of this website was a complete behind-the-scenes rebuild. This version, on the other hand, is almost *entirely* visual.
date: 2021-07-06
tags:
    - Design
    - Development
    - Accessibility
summaryImage: version-6.png
summaryImageAlt: The number 6, set in white FS-Me bold against a dark grey background.
---

It has been around 2 years since I released [version 5 of this website](/blog/website-version-5); that version didn't do much visually but it represented a *complete* rebuild. Version 6, on the other hand, is almost *entirely* visual, with very few behind-the-scenes improvements.

Although there has been no real branding change, version 6 is the biggest single visual change since [version 4 in December 2014](/blog/how-my-websites-design-has-evolved#december-2014). So what's different?


## No more light heading fonts

The first thing you'll notice is that the headings are a lot punchier. On larger screens, the headings follow the [golden ratio](https://en.wikipedia.org/wiki/Golden_ratio) type-scale, so the heading levels are both distinct from one another and beautifully proportional.


## No more Georgia

[Georgia](https://en.wikipedia.org/wiki/Georgia_(typeface)) is great; it:

- looks great
- has serifs, which aid reading; leading the eye from one letter to the next effortlessly
- was designed specifically for the screen nearly 30 years ago, so it's legible on *any* quality of screen, even any relics that have survived since the early '90s!
- is a system font, built in on nearly every device out there, so readers have no extra time to wait while it downloads, and it eats up zero data

Georgia was the typeface I chose for body copy on my website and replacing it was a tough decision, but the right one.

Despite all of its advantages, its serifs (the wee kicks and flicks on letters) were causing problems. Serifs can make letters less easy to identify for some people, such as those with dyslexia, so from an accessibility point of view I felt a move to sans-serif everywhere was the right thing to do. Previously, only headings were set in the sans-serif FS-Me, but now it's used for everything. What sweetens the deal even further is that [FS-Me was designed specifically to be as accessible as possible](http://projectrising.in/2015/08/fs-me-the-accessable-type/).

The downside to this is that I have to serve three fonts:

- Regular
- Bold
- Italic

Where before I served two (Regular and Light), so there's a little more burden on the user. I offset some of that by ensuring the fonts download without blocking the rendering of the page, and of course there's the [compression](https://www.netlify.com/blog/2020/05/20/gain-instant-performance-boosts-as-brotli-comes-to-netlify-edge/) and [CDN](https://www.netlify.com/products/edge/) that Netlify provides to lighten the load.


## All left-aligned

Versions 4 and 5 used a mixture of:

- centre-aligned text to draw attention to content like the page heading and calls to action
- left-aligned text to make body text easy to read

With version 6, readability has been prioritised, so all content is left aligned. This also makes content easier to read for visitors who use screen zoom/magnification or have a visual impairment like [glaucoma](https://www.nhs.uk/conditions/glaucoma/): less zig-zagging to find the next chunk of content.


## A couple of technical changes

It's mainly visual, but I did make a few tweaks under the bonnet.

### No more Susy

For years, I've relied on [Susy](https://www.oddbird.net/susy/) for my grids; I even [gave a talk on it back in 2015](https://youtu.be/-jh0rHHvIzw)! But with the simplification of the overall layout, I moved to the more lightweight CSS grid for the handful of places I wanted to lay elements out to a grid.

### Critical CSS

The other technical change I made was to use [critical CSS](https://web.dev/extract-critical-css/). This is a good performance boost as the above-the-'fold' styling is all contained in a `<style>` block in each page's `<head>`, meaning no requests for the initial page styling; the below-the-fold styles are still in a separate stylesheet, but it's loaded asynchronously so doesn't block page rendering.

It might be irrational, but I'm not sure I trust [critical CSS tools](https://github.com/addyosmani/critical) to do as good a job as I would, so I separated the critical styling from the non-critical manually. I'm sure there's more optimisation I could do, so it would be good to hear from anyone who's had success with the automated route.
