---
title: Always style links with a pseudo-class
intro: |
    Ever wondered why we have the `:link` pseudo-class as well as the `a` selector in CSS? Aren't they doing the same thing? Turns out they're not.
date: 2021-10-01
tags:
    - Development
    - CSS
---

I remember I used to wonder why we have the `:link` pseudo-class in CSS; why not just use the `a` element selector? Aren't they doing the same thing? Turns out they're not.

[`<a>` elements without an `href`](/blog/links-missing-href-attributes-and-over-engineered-code) don't have any semantic value and, by default, browsers:

- match their styling to their surrounding text
- don't include them in the page's tab index
- prevent them being clickable/actionable

If we were to style an `href`-less `<a>` element, we'd be telling our visitors that the `<a>` element was in some way different from the text it's within. It wouldn't be.

[MDN Docs describes the `:link` pseudo-class](https://developer.mozilla.org/en-US/docs/Web/CSS/:link) like this:

> The `:link` CSS pseudo-class represents an element that has not yet been visited. It matches every unvisited `<a>` … element that has an href attribute.

So if we want to change default link styling we should be targeting `:link` in our CSS, not `a`.

Don't do this:

```css
a {
  /* Link styles go here */
}
```

Do do this:

```css
:link {
  /* Link styles go here */
}
```
