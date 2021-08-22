---
title: "Critical CSS: what it is, why it's useful, and how it works"
intro: |
    With Critical CSS, we can give our visitors the most important styling as early as possible and the rest when it's ready. Here's why and how to do it.
date: 2021-08-19
tags:
    - Development
    - CSS
summaryImage: large
---

One of the few development changes I made in [Version 6 of my website](/blog/website-version-6) was the move to Critical CSS. Instead of the conventional wisdom where we serve all of our styles in a single CSS file, Critical CSS:

- puts the most noticeable (critical) styles in the document `<head>` using a `<style>` element
- sneaks the remaining styles (non-critical) in in the background, so that the user doesn't notice

Before I go into how to do it, it's important to explore *why*. Let's back it right up and talk about some rules Google has introduced called [Core Web Vitals](https://web.dev/vitals/#core-web-vitals). One of those Core Web Vitals is [First Contentful Paint](https://web.dev/first-contentful-paint/), which is a performance metric that measures:

> how long it takes the browser to render the first piece of DOM content after a user navigates to your page

One of the big recommendations here is to eliminate render-blocking resources as much as possible; things like fonts, JavaScript files, and CSS.


## Preventing render-blocking

So that CSS file we've been serving all these years is render blocking. When the browser reaches the `<link rel="stylesheet" href="/styles.css" />` element, it has to wait until the `styles.css` file has been:

1. requested from the server
2. downloaded and parsed/interpreted

Once this is done, the browser can then continue rendering the rest of the page.

So how do we stop the page rendering being blocked? The answer is with some clever code in our `<head>`:

```html
<link rel="preload" href="/styles.css" as="style" onload="this.onload=null;this.rel='stylesheet'" />

<noscript>
    <link rel="stylesheet" href="/styles.css" />
</noscript>
```

Here's what's going on:

1. The `rel="preload"` and `as="style"` attributes fetch the referenced stylesheet without asynchronously, allowing the browser to carry on reading down the rest of the document
2. The JavaScript in the `onload` attribute changes the value of the `rel` attribute to `stylesheet` to allow the styles to be rendered once they have been downloaded and parsed successfully
3. The contents of the `onload` attribute is swapped to `null` to ensure the script only runs once
4. If the user doesn't have JavaScript running, the browser falls back to the `<noscript>` element and loads the stylesheet in the traditional way

But this exposes a new problem: if the styling isn't ready before the browser gets to the `<body>` element, there's going to be a nasty flash of unstyled HTML, which will cause other issues like [Cumulative Layout Shift](https://web.dev/cls/).


## Separating the critical styling from the non-critical

To avoid any new problems, we have to address the controversial issue of <i>the fold</i>. Responsive design and the countless screen sizes our websites are viewed on have shifted the way we think about that old print concept, but [the fold still relevant](https://www.nngroup.com/articles/page-fold-manifesto/).

Just as a newspaper's front page is folded, exposing just the top half of the page, our websites are usually first viewed at the top of each page; though the amount of content varies from user to user, and screen to screen.

What we want to do is *minimise* the render blocking, rather than eliminate it:

- Give our visitors enough styling before the `<body>` HTML is rendered, so that when they first land on our site it all looks great above the fold
- By the time they've started scrolling, the out-of-initial-sight styling has loaded

There are two ways of serving our render-blocking, above-the-fold styling:

1. Use a traditional CSS file and `<link rel="stylesheet">`
2. Place it directly in the HTML, using a `<style>` element

One way or the other, we're separating out our above-the-fold styling, but it's the latter technique that's employed with Critical CSS.


## Critical CSS

With Critical CSS, the visitor gets the base styling as quickly as possible; because it's right there in a `<style>` element in the document's `<head>`, there's:

- no extra request to the server for the stylesheet
- no waiting for the requested styles to load in and render

The below-the-fold, or non-critical, styling is then loaded in the background using the earlier `<link rel="preload" as="style">` technique to avoid render-blocking. By the time the user is ready to scroll, those styles will have been fetched and rendered.


## How to decide what's critical

There are automated tools to extract the critical styles, but I decided to select them manually. I use SCSS, so my [stylesheets were already nicely organised into partials](https://sass-lang.com/documentation/at-rules/import#partials), so instead of a single compile file, I made two: `critical.scss` and `non-critical.scss`. I then chose which partials to `@import` into each.

`critical.scss` includes things like:

- basic typography
- the main page background and text colours
- links
- page layout
- header and navigation layout

`non-critical.scss` includes things like:

- more advanced typography
- form styling
- table styling
- code syntax highlighting
- calls to action
- page footer layout

Life isn't always straightforward, so I did find myself separating some partials; where I did this I added the suffix `--critical` to one file name, and `--non-critical` to the other.

### What to do with the compiled CSS?

The compiled `non-critical.css` stylesheet is output as normal, so it lands in `/dist/assets/css/` and is included using that fancy non-render-blocking code I talked about earlier.

How you add your `critical.css` to each page's `<head>` will depend on how your site is built. Me, I use [Eleventy, a static site generator](https://www.11ty.dev) so the `critical.css` is:

1. output to my [`_includes/` directory](https://www.11ty.dev/docs/config/#directory-for-includes)
2. baked into each page's `<head>` via an {% raw %}`{% include %}`{% endraw %} (I use the [Nunjucks templating language](https://mozilla.github.io/nunjucks/)) during the build process

<i>It's actually a bit more complicated than that as I use autoprefixer</i>

## Downsides to Critical CSS

Critical CSS is not without its downsides.

### No caching for critical styles

With the styling being output directly in the HTML via the `<style>` element we lose caching for our critical styles. This means that the browser has to read and render our critical styles with every subsequent page load, which feels a bit wasteful.

There are clever techniques to [prefetch the already-rendered styles](https://github.com/filamentgroup/enhance#enhancejs) as an external stylesheet and use a cookie to reference that stylesheet rather than the critical styles in the `<style>` element.

This might be something I look into at some point, but for now I'm okay with the itty bitty 7 kilobytes of styling that I add to the HTML.

### Flash of unstyled non-critical content

Most visitors will arrive at the top of a page, which we've anticipated in our critical styling. But what if the link they're followed includes a fragment identifier, like `https://www.example.com#a-heading-somewhere-down-the-page`? The main page styling will be there, but there's a distinct possibility that someone sees a flash of unstyled content down there; maybe a table or form.

Again, there's probably some fancy JavaScript that can detect the fragment identifier in the URL and serve the styles in the traditional render-blocking way. But as well as the extra scripting, it would mean complicating and slowing the site build process to compile an extra file that doesn't separate critical and non-critical styles.

I'll take the flash of some unstyled content for people that arrive part-way down a page.


## A better user experience

It's still loading the same amount of styling overall, but because Critical CSS loads the styling in very deliberate stages, our visitors' *perception* is that the page has loaded faster.

A better experience for our users, a thumbs-up from search engines, and an interesting dev challenge; what's not to love!?
