---
title: HTML isn't quite accessible out of the box
intro: There's a commonly held idea that HTML is accessible out of the box, before any CSS has been applied. Unfortunately, that isn't quite the case.
date: 2022-04-20
tags:
    - Accessibility
---

There's a commonly held idea that HTML is accessible out of the box, before any CSS has been applied.

This is put to the test every year on [CSS Naked Day](https://www.tempertemper.net/blog/css-naked-day) and, while many websites are still perfectly usable without any styling, there are still some barriers that CSS removes.

In fact, shortly after stripping the CSS from my website I received an automated email from the Google Search Console Team, highlighting issues that weren't there until CSS Naked Day:

> Search Console has identified that your site is affected by 3 Mobile Usability issues:
>
> - Text too small to read
> - Clickable elements too close together
> - Content wider than screen

'Naked' HTML will allow text to be enlarged while allowing content to reflow nicely, but there are some things, like [data tables that are wider than their container,](/blog/accessible-responsive-tables) that need CSS to be properly accessible. And, even in 2022, there are still [browser bugs we need to work around with extra styling](/blog/fixing-safaris-html-only-dark-mode-bug). Some issues will be fixed, but other are unlikely to change as they'd probably break countless live websites.

Contrary to popular belief, we'll always need a small amount of `<style>` to make things truly accessible, but well-crafted markup goes a very long way.
