---
title: Custom unordered list markers, done right
intro: |
    Did you know you can choose any icon you like for unordered/bulleted lists with a single line of CSS? Any Unicode character; no hacky CSS!
date: 2020-10-08
tags:
    - Development
    - CSS
---

In my post on [styling list markers](/blog/styling-list-markers-the-right-way) I mentioned that we now have proper control over list markers.

With `::marker` we can control the colour and size of both ordered and unordered lists. We also have all the flexibility we'd ever need for ordered lists, which are typographical: colour, size, typeface, weight, etc. Unordered lists, on the other hand, are a bit more limited; other than colour and size, you've only got [three shapes in `list-style-type`](https://developer.mozilla.org/en-US/docs/Web/CSS/list-style-type):

- A filled-in circle (`disc`)
- An outline of a circle (`circle`)
- A square (`square`)

Luckily, we can get *really* custom by using a [unicode value](https://en.wikipedia.org/wiki/List_of_Unicode_characters) instead of `disc`, `circle` or `square`. So, for example, if you wanted your bullets to be black, right-pointing triangles, you'd use:

```css
ul {
  list-style-type: "\25B6";
}
```

Unfortunately, custom list markers aren't supported in Safari, so for now it's a nice progressive enhancement. We need a classic double declaration for Safari as, without the `disc` style before the Unicode style, Safari displays no markers at all:

```css
ul {
  list-style-type: disc;
  list-style-type: "\25B6";
}
```

If you *need* those black, right-pointing arrows across all browsers, [you can still do it the old fashioned way](/blog/styling-list-markers-the-right-way#the-way-weve-been-doing-it) with the `::before` pseudo element.

