---
title: Page headings don't belong in the header
intro: I have a habit of thinking pretty deeply about semantics and structure, and have been considering the main page heading and where it should live.
date: 2025-10-15
tags:
    - Accessibility
    - HTML
---

Unsurprisingly, I have a tendency to think pretty deeply about semantics and structure. Recently, I've been considering `<h1>` elements and where they live in the document, and I've landed on keeping it outside the header.

Don't get me wrong, nesting the `<h1>` inside the page's `<header>` (or `<div role="banner">`) is a perfectly valid approach; [according to MDN Docs](https://developer.mozilla.org/en-US/docs/Web/HTML/Reference/Elements/header) the `<header>` is used to wrap:

> introductory content, typically a group of introductory or navigational aids. It may contain some heading elements but also a logo, a search form, an author name, and other elements.

But having thought about this, I don't think headings are much use here. Let me explain why.


## Where should the page heading live?

I'll start with where the `<h1>` *should* be placed, and you'll start to see why the `<header>` isn't the right location: it's the header for the page, and the main page content should live within the `<main>` element.

```html
<header>
    <!-- Header stuff -->
</header>
<main>
    <h1>Page heading</h1>
    <!-- Main page content -->
</main>
```


## Why is the header the wrong place?

First, we're not talking about just any old `<header>` element, we're talking about the main page header/banner.

### Headers in the main element lose their semantics

Just like [when used in a `<section>` element](/blog/implicit-aria-landmark-roles#the-theory), `<header>` elements' role of `banner` is removed by the browser when nested in a `<main>`. Going back to MDN Docs' and their [Usage Notes](https://developer.mozilla.org/en-US/docs/Web/HTML/Reference/Elements/header#usage_notes):

> when nested within said elements [sectioning content, `<main>`], it loses its landmark status

So when a `<header>` is inside a `<main>`, it behaves like a `<div>`. So if we want to use a `<header>` to house our `<h1>` it would have to be outside `<main>` element so that it can do its job.

In this example, the header would carry no semantic meaning, so screen reader users would be left wondering where the main page header landmark was.

```html
<main>
    <header>
        <!-- Header stuff -->
        <h1>Page heading</h1>
    </header>
    <!-- Main page content -->
</main>
```


### Separating causes skipping/jumping issues

If we decide to move the `<h1>` into the header to preserve its landmark role, another problem appears:

```html
<header>
    <!-- Header stuff -->
    <h1>Page heading</h1>
</header>
<main>
    <!-- Main page content -->
</main>
```

Putting the `<h1>` in the `<header>` separates the heading from the main content of the page. This means:

- Keyboard users who use the [skip link](/blog/skip-links-what-why-and-how) would jump down past the `<h1>`, which may scroll the heading out of the viewport
- Likewise, screen reader users who use the skip link would jump past the heading, and would have to navigate backwards if they wanted to read it
- Screen reader users who navigate by landmark to land on the `<main>` element would miss the `<h1>` and, again, have to navigate backwards to check it out

None of these things are dealbreakers, and it would be a [seriously tough accessibility auditor](/blog/erring-on-the-side-of-caution) who failed it against the Web Content Accessibility Guidelines (WCAG)'s [2.4.3 Focus Order](https://www.w3.org/TR/wcag/#focus-order) since:

- Keyboard users will have probably seen the `<h1>` before they use the skip link and jump past it
- Screen reader users will probably have heard the `<h1>` content via the `<title>` when they first landed on the page (assuming the [2.4.2 Page Titled](https://www.w3.org/TR/wcag/#page-titled) has been met)

You could even fix things for keyboard users by having the skip link jump to the `<h1>` rather than the `<main>` element. But as with many work-arounds, this could create some potential issues for screen reader users who might use the skip link:

- Referring to "main content" in the skip link is pretty standard, but if it says "main" and the element linked to is a heading, that could be slightly disorienting
- Omitting "main" and using just "Skip to content" could work, but landmarks that shouldn't be in the `<main>`, like the [`<aside>` element](/blog/years-in-the-accessibility-learning-curve-continues) and `<footer>` contain content too, so the skip link text becomes less than accurate

I'm really nit-picking here, but it's important to think about things beyond the visually obvious.

### Adds noise for screen reader users

A screen reader user who uses a gesture or [keyboard shortcut to jump to the first heading](/blog/getting-started-with-voiceover-on-macos#navigation-commands-to-get-started) would hear some unnecessary information. After the details of the page heading they've successfully landed on, they'd then have to listen to the end of the `<header>` element ("End of banner" or similar) and the start of the `<main>` element before they got to the first bit of content after the heading.

Not a huge issue, but I'm all for streamlining the user experience.


## See what I mean?

I prefer to keep things simple and predictable from the user's point of view. Page structure depends on context, but as a rule of thumb I keep the page's `<h1>` out of the `<header>`. It keeps the hierarchy clear, the landmarks consistent, and the experience smoother for everyone.
