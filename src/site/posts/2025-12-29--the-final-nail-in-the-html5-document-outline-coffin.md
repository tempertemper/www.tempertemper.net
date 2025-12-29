---
title: The final nail in the HTML5 document outline coffin
intro: All the main browsers have finally dropped visual support for the HTML5 document outline algorithm. Here's why that's good news.
date: 2025-12-29
tags:
    - HTML
    - Accessibility
---

I've written about the [document outline](/blog/using-the-html-document-outline) before, including a reference to how HTML5 introduced the HTML5 document outline algorithm, which was never fully supported.

First, a reminder; what was the idea? Well, it was to allow content to be truly modular, so that each chunk could be included in a document wherever the author wanted without any friction:

- Each chunk/module/section would be wrapped in a sectioning element (like a `<section>`)
- The heading level in each module would always start at level one (`<h1>`)
- The browser would create the document outline based on how the modules were put together

Here's how the markup might have looked:

```html
<h1>This is the main heading</h1>
<p>It's the level one heading.</p>
<section>
    <h1>This is a section-scoped heading</h1>
    <p>It should look and behave like a level two heading.</p>
</section>
```

It was a neat idea, and it would have saved me a bunch of if/else logic in many of the websites I build, for example:

- There's a list of blog post snippets and each has the post title as its heading
- On the main blog listing page an `<h1>` introduces the page ("Blog"?) so the list item headings are level two
- On a page like the homepage the `<h1>` introduces the purpose of the website and an `<h2>` talks about the blog; any sample posts in a short list would be level three

No fiddly logic with the HTML5 document outline algorithm; the browser would take care of the levels based on how deeply they were nested.


## Never fully supported

Strangely (or maybe not so strangely?) browsers added *some* support for the HTML5 document outline algorithm. This fancy HTML5 method was never finished:

- It worked *visually*, as browsers changed their default styling to match the algorithm
- Non-visually, it did nothing; the accessibility tree just included lots of level one headings, as per the markup, and screen reader users would have been left wondering what was going on

Since human beings have a tendency to view the world from their own perspective, and the majority of designers and developers are sighted, the visual conformance with the HTML5 technique was seen by many as a green light. *I* certainly saw it like that before I began to dig into accessibility and realised the problems I was causing.


## What about that final nail?

Firefox and Chrome removed the visual support for the HTML5 document outline algorithm some time ago:

- [Firefox in version 138, April 2025](https://developer.mozilla.org/en-US/docs/Mozilla/Firefox/Releases/138#experimental_web_features)
- [Chrome around July 2025](https://github.com/whatwg/html/issues/7867#issue-1218728578)

So I was heartened to see that the [recent release of Safari 26.2](https://webkit.org/blog/17640/webkit-features-for-safari-26-2/) has done the same:

> To go with the intended original behavior in HTML5, the default UA styling for h1 elements was specified to visually change the headline when it was a child of article, aside, nav, or section … In Safari 26.2, following a recent HTML standard change, those UA [User Agent] styles are being removed.

Good news.
