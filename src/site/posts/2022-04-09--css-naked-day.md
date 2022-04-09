---
title: CSS Naked Day
intro: CSS Naked Day is a day when all website owners should strip their site of CSS to expose the 'naked' HTML underneath.
date: 2022-04-09
updated: 2022-04-09
tags:
    - Accessibility
summaryImage: css-naked-day.png
summaryImageAlt: A screenshot of this page as it looked on CSS Naked Day without any styling, showing sensibly ordered, semantic, readable content.
---

[CSS Naked Day](https://css-naked-day.github.io) is a day when all website owners should strip their site of CSS to expose the 'naked' HTML underneath.

> The idea behind CSS Naked Day is to promote web standards. Plain and simple. This includes proper use of HTML, semantic markup, a good hierarchy structure, and of course, a good old play on words. In the words of 2006, it’s time to show off your `<body>` for what it really is.

For me, there's a huge accessibility angle to it too:

- It's not uncommon for things being sent to a browser by the server to be lost in transit, and that includes CSS files
- Some people don't experience the web visually, for example non-sighted screen reader users
- Some visitors rely on the underlying HTML being true to how it is presented visually, for example speech recognition software users

So without all the styling styling:

- content should be identifiable for what it is, for example headings and their level
- organisation and sequence of content should be understandable
- content itself should be readable
- operability and interactions should all still function as expected

If your site becomes unusable in any way, you’ll know you’ve got some work to do!

Update: here's how this post looked on CSS Naked Day:

<picture>
    <source srcset="/assets/img/blog/css-naked-day.avif" type="image/avif" />
    <source srcset="/assets/img/blog/css-naked-day.webp" type="image/webp" />
    <img src="/assets/img/blog/css-naked-day.png" alt="A screenshot of this page as it looked on CSS Naked Day without any styling, showing sensibly ordered, semantic, readable content" width="800" height="450" loading="lazy" decoding="async" />
</picture>
