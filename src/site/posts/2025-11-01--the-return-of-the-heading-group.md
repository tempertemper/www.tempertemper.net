---
title: The return of the heading group
intro: |
    I've written about why we probably shouldn't use header elements for headings, but what should we use instead? The `<hgroup>` element is back!
date: 2025-11-01
tags:
    - HTML
    - Accessibility
---

So if we [shouldn't use headers for headings](/blog/page-headings-dont-belong-in-the-header), what should we use instead?

Well, most of the time you'll just use the heading (`<h1>`), but I understand why you might have wanted to use the `<heading>` to group the `<h1>` with other information; maybe:

- The date posted, category, and other details associated with a blog article
- The section the user is in, for example if a Team page is part of a wider 'About' section, that About marker could be useful
- An alternate title, such as 'Dr. Strangelove' which might come along with "or: How I Learned to Stop Worrying and Love the Bomb"
- An introductory sentence

All these things are useful and not quite part of the main body text of the article, so how do we group them with the `<h1>`, if not a `<header>` element? I'd go with `<hgroup>` (Heading Group).


## A brief history of the heading group element

The original specification for `<hgroup>` was that it allowed multiple heading levels to be grouped together, and only the [highest level heading](https://html5doctor.com/the-hgroup-element/) would contribute to the [document outline](/blog/using-the-html-document-outline). Here's an example:

```html
<hgroup>
    <h1>I make amazing websites</h1>
    <h2>And, of course, they're accessible!</h2>
</hgroup>
```

So, in theory, a screen reader user wouldn't hear this `<hgroup>`'s `<h2>` when skipping through the headings on the page; just its `<h1>`. But that behaviour was never implemented by browsers, so screen reader software still picked up the `<h2>` just as it would any other `<h2>` on the page, which could lead to a slightly confusing experience, since that `<h2>` doesn't represent a section of the page.

So `<hgroup>` was [removed from the HTML specification](https://lists.w3.org/Archives/Public/public-html-admin/2013Apr/0003.html) in 2013.

There was still a need for some way to group headings and their directly related content, so the [`<subhead>` element was explored](https://rawgit.com/w3c/subline/master/index.html) shortly afterwards:

```html
<h1>
    I make amazing websites
    <subhead>And, of course, they're accessible!</subhead>
</h1>
```

The idea here was that the contents of the `<subhead>` would be excluded from the page outline, just as the `<h2>` was meant to be in our earlier example. But a new HTML element is a hard sell which is, I'm guessing, why it didn't get much further than those early proposals.

I can't quite pin down when [the HTML spec](https://html.spec.whatwg.org/multipage/sections.html#the-hgroup-element) changed, but [MDN Docs updated their `<hgroup>` entry in 2022](https://github.com/mdn/content/commit/59abae8b181d413e89e882c97e58c68c7c2c929f), simultaneously resurrecting the element and fixing the document outline problem.


## How it should work

[MDN Docs now has this to say](https://developer.mozilla.org/en-US/docs/Web/HTML/Reference/Elements/hgroup):

> The `<hgroup>` HTML element represents a heading and related content. It groups a single `<h1>`–`<h6>` element with one or more `<p>`.

So no more than one heading, and the only other content we're allowed alongside our heading is paragraphs, like this:

```html
<hgroup>
    <h1>I make amazing websites</h1>
    <p>And, of course, they're accessible!</p>
</hgroup>
```

This means the only thing that makes it to the document outline, of course, is the `<h1>`. And we have a nice way to tie other content to the heading.


## But there's a catch

The `<hgroup>` element is fine to add, but there's an issue: browsers don't do anything with it. At least not without a bit of extra work.

According to [MDN Docs' technical summary](https://developer.mozilla.org/en-US/docs/Web/HTML/Reference/Elements/hgroup#technical_summary), it should get the implicit role of `group`. It does (you can see it in Chrome's accessibility tree, for example) but, just like with [ARIA's group role](https://w3c.github.io/aria/#group), nothing is actually communicated when there's just a `role="group"` attribute; you need a label in order for the role to be exposed.

You could use `aria-labelledby` and point to the `<h1>`, but that would be repetitive, and all we need to convey here is that the user has reached the page's heading group. So this is the markup pattern I'd recommend:

```html
<hgroup aria-label="Heading">
    <h1>I make amazing websites</h1>
    <p>And, of course, they're accessible!</p>
</hgroup>
```

This exposes the `<hgroup>` as a "heading group" as expected, letting a screen reader user know where it starts, its content, and where it ends.
