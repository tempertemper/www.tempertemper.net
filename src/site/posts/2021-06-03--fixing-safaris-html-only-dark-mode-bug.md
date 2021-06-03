---
title: Fixing Safari's HTML-only Dark Mode bug
intro: |
    Aside from the current lack of Firefox support, there's a bug in Safari.
date: 2021-06-03
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

I want to use the `name="color=scheme"` meta element in my HTML, so the way I've implemented it on my website is to add a `<style>` block to the bottom of each page, before the closing `</html>` tag (so that it doesn't block any rendering):

```html
<style>
  @media screen and (prefers-color-scheme: dark) {
    a {
      color: #9e9eff;
    }
    a:visited {
      color: #d0adf0;
    }
  }
</style>
```

These styles are then overridden in my CSS, so that they look nice and on-brand:

```css
@media screen and (prefers-color-scheme: dark) {
  a:link,
  a:visited:visited {
    color: #00a0f0;
  }
}
```

<i>Note to self, I really should get round to adding visited link styling at some point.</i>

The `:link` pseudo class overrides the naked `a` on-page styling, and the chained `:visited` pseudo class overrides the unchained `:visited` pseudo class.

I'm not very happy with that code though. It's ugly and unnecessary, so I hope the WebKit team fix that bug soon so that I can tidy things up. But in the meantime, Safari users with low vision will be able to discern links in Dark Mode when the CSS fails to load.

