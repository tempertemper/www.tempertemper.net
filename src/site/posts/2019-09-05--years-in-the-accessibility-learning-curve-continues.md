---
title: Years in, the accessibility learning curve continues
intro: |
    I've cared about accessibility for as long as I've been working in the web and, even after all these years, I still enjoy learning new things.
date: 2019-09-05
tags:
    - Accessibility
    - Development
---

I've cared about accessibility for as long as I've been working in the web and, even after all these years, I still enjoy learning new things. The more I design, build and test, the more edge-cases I come across; recently, I realised that `<aside>` elements are higher-level than I thought. Let me explain.

MDN web docs says this about [the `<aside>` element](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/aside):

> The HTML `<aside>` element represents a portion of a document whose content is only indirectly related to the document's main content.

I took this to mean "wherever there's some content that's indirectly related to the document's content, use an `<aside>`. I would have written something like this:

```html
<body>
    <main>
        The main page content.
        <aside>
            A tantalising snippet from another page.
        </aside>
    </main>
</body>
```

I was wrong. You see, I do most of my screen reader testing in VoiceOver, which seems to be more forgiving in this area than others must be, so this has never presented an issue.


## The problem

`<aside>`s get an [implicit ARIA landmark role](/blog/implicit-aria-landmark-roles) of `complementary` and it turns out you shouldn't use `role="complementary"` inside any other landmarks.

It makes sense when you think about it -- it's content that's complementary to the *document itself*, not a *part* of the document. Using `<main>` as an example, once you've used the `<main>`  landmark, you've told the browser that that's the primary page content, so any `<aside>`s in there would be related to that, not the document itself.

What's more, [Deque's documentation on the use of `<aside>` explains](https://dequeuniversity.com/rules/axe/3.3/landmark-complementary-is-top-level) that misuse can cause problems for some screen readers:

> Screen reader users have the option to skip over complementary content when it appears at the top level of the accessibility API. Embedding an `<aside>` element in another landmark may disable screen reader functionality allowing users to navigate through complementary content

I want people navigating my sites with screen readers to be fully empowered to skip (or navigate directly to, if they prefer) my complementary content, just as a sighted user can identify an advert, for example, and ignore it.


## The fix

So here's how we'd alter the earlier example:

```html
<body>
    <main>
        The main page content.
    </main>
    <aside>
        A tantalising snippet from another page.
    </aside>
</body>
```

Basically, as soon as you've defined any landmark on the page, you've ruled out using an `<aside>` in there -- `<aside>`s have to be children of the `<body>` element (with as many `<div>`s in between as you like) or you're making life difficult for screen reader users.

Here's a quick run-down of the sectioning elements that you shouldn't use an `<aside>` inside of:

- `<article>`
- `<footer>`
- `<header>`‌
- `<main>`
- `<nav>`
- `<section>`

It's worth mentioning that it *is* safe to use `<articles>`s, `<footer>`s, `<header>`s, `<nav>`s, and `<section>`s (not `<main>` -- only one of those is allowed per page) *inside* an `<aside>`.


## Avoiding this kind of thing in future

Just like browsers, there are issues that show up in some screen readers and not others. Testing across various operating systems with different screen readers is always best, but before we get there we should take advantage of other tools at our disposal.

Ensuring our [markup passes validation](https://validator.w3.org/#validate_by_input) is a great the first step, and running a test with a browser tool like [axe](https://www.deque.com/axe/) flags pretty much every other issue I've encountered. Having run some tests like this, you can be confident that your screen reader testing will be as snag-free as possible.
