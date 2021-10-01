---
title: Links, missing href attributes, and over-engineered code
intro: |
    Links without an `href` attribute are ignored by browsers; making them 'behave' using JavaScript, CSS, and other HTML attributes is not a solution.
date: 2021-09-30
updated: 2021-10-01
tags:
    - Accessibility
    - Development
    - HTML
summaryImage: href.png
summaryImageAlt: The HTML for a link with the href attribute and its value scored out.
---

Just the other day, I was chatting to a software tester I work with about an accessibility issue they were having with a link. It looked like a link, worked fine with a mouse pointer and keyboard, but it wasn't behaving as expected using [NVDA](https://www.nvaccess.org/), a screen reader for Windows. The HTML looked something like this:

```html
<a tabindex="0" id="unique-identifier">Link text</a>
```

Can you spot what's missing? That's right: no `href` attribute.

Without an `href`, a link is simply a placeholder for a link. From the [HTML Living Standard](https://html.spec.whatwg.org/multipage/text-level-semantics.html#the-a-element):

> If the `a` element has no `href` attribute, then the element represents a placeholder for where a link might otherwise have been placed, if it had been relevant

Essentially, an `<a>` without an `href` is meaningless; it might as well be a `<span>`.

`href`-less `<a>` elements don't receive focus when navigating a page with the tab key, and their default browser styling is exactly the same as their surrounding text, rather than dark blue (`#0000ee`) and underlined.

I'm assuming this was a knowledge gap rather than over-enthusiasm or a technical limitation, but one way or another, by omitting the `href` the developer(s) who wrote this code actually made quite a bit of extra work for themselves:

- Set up JavaScript redirects based on the `id` to do what the `href` would have done
- Add the `tabindex="0"` attribute to ensure the 'link' was focusable with the tab key
- [Style links without the `:link` pseudo-class](/blog/always-style-links-with-a-pseudo-class)

They also made the link inaccessible to some screen reader users.

Recreating HTML's built-in functionality manually is time consuming and more error prone. If some code you write doesn't work as expected, before reaching for JavaScript, writing more CSS, and adding HTML attributes, check it's not just a missing `href`.
