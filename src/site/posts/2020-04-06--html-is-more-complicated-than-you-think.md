---
title: HTML is more complicated than you think
intro: |
    HTML, like CSS, is easy to learn. Trouble is, if you want to write it well, it gets difficult very quickly.
date: 2020-04-06
categories:
    - Design
    - Development
    - Accessibility
summaryImage: large
---

A couple of recent articles got me thinking. The first was [Why is CSS frustrating](https://css-tricks.com/why-is-css-frustrating/) by Robin Rendle at CSS-Tricks which references a [Jeremy Keith post](https://adactio.com/journal/12571) from a couple of years ago.

> CSS is pretty easy to pick up. Maybe it’s because of this that it has gained the reputation of being simple. It is simple in the sense of “not complex”, but that doesn’t mean it’s easy. Mistaking “simple” for “easy” will only lead to heartache.

The other was a roundup by Dave Rupert -- [HTML: The Inaccessible Parts](https://daverupert.com/2020/02/html-the-inaccessible-parts/). Here he starts with:

> by just using a suitable HTML element instead of a generic `div` or `span` we can have a big Accessibility impact

He then goes on to list the problems with some of that more 'suitable' HTML:

- browser compatibility, both legacy and current
- issues with how assistive technologies (screen readers like [NVDA](https://www.nvaccess.org/about-nvda/), voice controllers like [Dragon](https://www.nuance.com/dragon.html)) interpret, don't interpret or *misinterpret* certain markup
- usability issues (e.g. block links)


## The CSS and HTML learning curves are similar

What makes CSS frustrating is that it's easy to pick up and make default browser styling much more interesting, but you can quickly run into unexpected behaviour, complexity and browser compatibility issues.

It's a similar story with HTML -- it's super simple to start writing content, marking it up with HTML and seeing how things look in your browser. It's even more exciting when you apply that basic CSS that you've written.

Unfortunately HTML, like CSS, can present unexpected behaviour, complexity and browser compatibility issues.

As with colour contrast, `display: none;` and [restyling lists](https://www.scottohara.me/blog/2019/01/12/lists-and-safari.html) in CSS, HTML can do damage. Beyond the basics of wrapping a paragraph in `<p>` tags, an unordered list in a `<ul>` containing `<li>`s, etc., there are a whole host of things to consider:

- proper heading levels ([the document outline](/blog/using-the-html-document-outline))
- when and *how* to use landmarks like `<header>`, `<main>`, `<article>`, `<aside>`, `<footer>` and so on
- what's the difference between [`<article>` and `<section>`](https://www.smashingmagazine.com/2020/01/html5-article-section/)
- semantics, like when is a `<dl>` more appropriate than a `<table>`?
- the accessibility implications of using one element over another
- valid use of certain elements inside others
- ARIA `label`s, `role`s, etc.
- browser compatibility with certain elements and attributes (`<datalist>` sounds like a great idea but comes with some accessibility drawbacks)
- assistive tech like Dragon or NVDA compatibility with certain elements and attributes ([`type="number"`](https://technology.blog.gov.uk/2020/02/24/why-the-gov-uk-design-system-team-changed-the-input-type-for-numbers/), for example)
- the user experience of certain elements -- just because we *can* use an element doesn't mean we *should* (e.g. `<select multiple>` when something like `<input type="checkbox" />` probably provides better usability)
- Giving extra information for search engines and other machines via [microformats](//microformats.org) and [microdata](https://schema.org)
- Social media cards with [Open Graph](https://ogp.me) and [Twitter Cards](https://developer.twitter.com/en/docs/tweets/optimize-with-cards/guides/getting-started)
- responsive design (`<meta name="viewport" content="initial-scale=1, width=device-width" />`, `<picture>` and `srcset=""`)
- catering for new devices, like iPhones X and above with their 'notches' ([`viewport-fit=cover`](https://webkit.org/blog/7929/designing-websites-for-iphone-x/))
- catering for [new browsers like Apple Watch](https://www.brucelawson.co.uk/2018/the-practical-value-of-semantic-html/)

That list is by no means definitive, but covers a lot of the big things you'll encounter when writing your markup. It's not just the half-baked features that Dave Rupert mentions, but the slew of extra considerations that make HTML a right old bugger to master.
