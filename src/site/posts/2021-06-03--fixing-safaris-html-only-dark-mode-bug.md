---
title: Fixing Safari's HTML-only Dark Mode bug
intro: |
    A bug with link text colours in Safari's HTML-only Dark Mode theme means we need a bit of extra code. Here's how to patch things until it's fixed.
date: 2021-06-03
updated: 2021-11-04
tags:
    - CSS
    - HTML
    - Accessibility
---

Aside from the lack of Firefox support, [there's a bug in Safari](https://bugs.webkit.org/show_bug.cgi?id=209851) that makes it difficult to see links in browser's [HTML-only dark mode](/blog/html-only-dark-mode).

The problem is that the blue colour used for links is the same as that used in Light Mode (`#0000ee`), which has a [1.99 to 1 contrast ratio](https://webaim.org/resources/contrastchecker/?fcolor=0000EE&bcolor=121212) against the dark page background `#121212`. This means it doesn't meet the AA Web Content Accessibility Guidelines (WCAG) [Contrast (Minimum) success criterion (SC)](https://www.w3.org/TR/WCAG21/#contrast-minimum).

Chromium browsers (Chrome, Edge, Opera, Brave, etc.) use `#9e9eff` for links, which is a [7.84 to 1 contrast ratio](https://webaim.org/resources/contrastchecker/?fcolor=9E9EFF&bcolor=121212), satisfying not only Contrast (Minimum), but the level AAA [Contrast (Enhanced) SC](https://www.w3.org/TR/WCAG21/#contrast-enhanced).

It's a similar story with visited links, where Safari uses a failing `#551a8b` (a [1.7 to 1 contrast ratio](https://webaim.org/resources/contrastchecker/?fcolor=551A8B&bcolor=121212)) and Chromium browsers use an excellent AAA `#d0adf0` (a [9.73 to 1 contrast ratio](https://webaim.org/resources/contrastchecker/?fcolor=D0ADF0&bcolor=121212)).


## Fixing the bug

I want to use `<meta name="color-scheme" content="dark light" />` element in my HTML, so the way I've implemented it on my website is to add a `<style>` block to the bottom of each page, before the closing `</html>` tag (so that it doesn't block any rendering):

```html
<style>
  @supports (color-scheme: dark light) {
    @media screen and (prefers-color-scheme: dark) {
      :where(a:link) {color: #9e9eff;}
      :where(a:visited) {color: #d0adf0;}
    }
  }
</style>
```

Aside from increasing the contrast of links and visited links in Dark Mode, using the same colour values as Chrome, this:

- wraps it all in a `@supports` at-rule so that the contained styles don't get used for browsers like Firefox that support `prefers-color-scheme` but not HTML-only dark mode; `#9e9eff` and `#d0adf0` have low contrast ratios against a white background ([2.38 to 1](https://webaim.org/resources/contrastchecker/?fcolor=9E9EFF&bcolor=FFFFFF) and [1.92 to 1](https://webaim.org/resources/contrastchecker/?fcolor=D0ADF0&bcolor=FFFFFF), respectively)
- uses [the `:where` pseudo-class](https://developer.mozilla.org/en-US/docs/Web/CSS/:where) which is nice to use for 'default' styles since it <q>always has 0 specificity</q>, so doesn't need any special overrides in the CSS (for example, chaining the pseudo-class in the selector like `:link:link {}`)

I hope the WebKit team fix that bug soon so that I can tidy things up, but in the meantime Safari users with low vision will be able to discern links in Dark Mode when the CSS fails to load.

