---
title: When and how to use the section element
intro: |
    So what on earth is a `<section>` element actually for? The answer isn't as obvious as you might have hoped, but it's definitely straightforward.
date: 2021-02-11
tags:
    - HTML
    - Accessibility
summaryImage: large
---

HTML5 brought with it some questions, like "what on earth is a `<section>` element for!?". The answer isn't as obvious as you might have hoped, but it's definitely straightforward.

First let's talk about landmarks. As a sighted user, I can scan a web page and, without thinking, identify key areas (landmarks) on the page; things like:

- The header
- Navigation bars
- The main content
- A call to action
- A filter panel
- The footer

You may think this'd be impossible for a non-sighted user, but you'd be wrong! Screen reader users can bring up a list of the landmarks on the page and quickly skip to any one of them. If properly marked up in the HTML, some of these landmarks are [added automatically by the browser](/blog/implicit-aria-landmark-roles); on the other hand, there are some landmarks we have to be more deliberate with.


## When to use a section

The most common landmarks on a web page are the header, the main navigation, the main content area, and the footer. These all have their own HTML elements, which create landmarks on the page. For any landmarks that aren't defined already in HTML (like the call to action or filter panel from that list above), a `<section>` is probably what you need.


## How to use a section

A `<section>` element on its own doesn't do much; it's effectively the same as using a `<div>` (other than it [providing 'sectioning' for `<footer>` and `<header>`](/blog/implicit-aria-landmark-roles#the-theory)). What we need to do is tell the browser what the section *is*, and we do this by labelling the section.

Visually, your section is probably going to stand out somehow; a different coloured background or some other way to distinguish it from the other content on the page. Adding a label to the section allows it to stand out to *non-visual users*.

### Using `aria-label`

The first thing you might reach for is `aria-label`, giving the `<section>` a non-visual name which will be read out to screen readers:

```html
<section aria-label="Join the mailing list">
    <h2>Join the mailing list</h2>
    <p>This is the section content</p>
</section>
```

In VoiceOver (macOS's screen reader), this section would be read out amongst the list of landmarks as "Join the mailing list region".

But there are problems with this method:

1. It's not very [DRY](https://en.wikipedia.org/wiki/Don%27t_repeat_yourself) as we're adding the same content in two places
2. If the `<h2>` (or whatever we're using to visually label the `<section>`) is changed at some point, there's a risk that the `aria-label` is forgotten about, creating a mismatch

When a `<section>` label doesn't match its first heading, a screen reader user has to do more work to orient themselves if they jump to that particular landmark: the section was called one thing, yet its heading says another.

### Using `aria-labelledby`

To avoid the problems inherit with `aria-label` on a `<section>`, we can use `aria-labelledby` instead. This way, if the section's heading is updated, the label changes automatically:

```html
<section aria-labelledby="sectionHeading">
    <h2 id="sectionHeading">Join the mailing list</h2>
    <p>This is the section content</p>
</section>
```


## Summing up

A `<section>` is a custom landmark, when `<header>`, `<nav>`, `<main>`, `<footer>`, etc. aren't appropriate. All you need to do is give it some semantic meaning with ARIA and screen reader users will be able to jump right to it, just like a sighted user can.
