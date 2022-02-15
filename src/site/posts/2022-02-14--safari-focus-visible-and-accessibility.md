---
title: Safari, focus-visible and accessibility
intro: Safari is the last browser to support the `:focus-visible` pseudo-class, and with that support comes a huge accessibility win.
date: 2022-02-14
tags:
    - Accessibility
    - CSS
related: refining-focus-styles-with-focus-visible
---

Before embarking on my career in the web, there was one book I obsessed over: [CSS: The Missing Manual](https://www.oreilly.com/library/view/css-the-missing/0596526873/). I learned the 'LoVe HAte' mnemonic ('L' for `:link`, 'V' for `:visited`, 'H' `:hover`, and 'A' for `:active`), but I don't recall any mention of the `:focus` pseudo class.

In fact, it has always been common practice to simply remove focus outlines entirely, which has caused a big accessibility problems for people who use the keyboard to navigate, as highlighted in a recent [blog post from Manuel Rego of the WebKit team](https://webkit.org/blog/12179/the-focus-indicated-pseudo-class-focus-visible/):

> The goal of the old `:focus` selector was to allow authors to better style the focus indicator to be in tune with their overall design choices … The net result, unfortunately, has been that the most common use of the `:focus` selector has been to remove indicators altogether. This avoids the "false positive" focus styles that cause complaints from many users. The problem is that removing focus styling breaks website accessibility, causing trouble for people navigating the page using the keyboard.

The `:focus-visible` pseudo-class intends to fix this, and Manuel continues:

> Fortunately, a new CSS selector comes to the rescue, avoiding this kind of accessibility issue while providing the behavior web developers were looking for. The `:focus-visible` pseudo-class … allows web authors to style the focus indicator only if it would be drawn natively.

This is great news! Safari has long been the missing link here, but with the up-coming Safari 15.4, `:focus-visible` will have full support across modern browsers. So:

- people can enjoy a focus outline-free experience as they click around their websites
- keyboard users will still get the markers they need to navigate
