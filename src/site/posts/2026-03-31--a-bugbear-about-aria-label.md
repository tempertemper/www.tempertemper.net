---
title: A bugbear about aria-label
intro: Labels are labels and names are names, except `aria-label` which is a name…
date: 2026-03-31
tags:
    - Accessibility
---

I was writing about [how to write the `aria-label` for `<nav>` elements](/blog/theres-no-need-to-include-navigation-in-your-navigation-labels) and I kept referring to the `aria-label` as the 'label', which bothered me.

You know how pernickety I can get about what things are called; I mean, I wrote over 600 words on [why not to use 'headers' and 'headings' interchangeably](/blog/headers-headings-and-titles). So allow me to dig into why the 'label' bit of `aria-label` gets on my nerves.

Let's use the Web Content Accessibility Guidelines (WCAG) as our reference point for the terminology. Here's their [definition for 'label'](https://www.w3.org/TR/wcag/#dfn-labels):

> text or other component with a text alternative that is presented to a user to identify a component within web content

It's referring to what is presented *visually*; usually text or something else with a 'text alternative', like an icon.

WCAG then goes on to [define a 'name'](https://www.w3.org/TR/wcag/#dfn-name) as:

> text by which software can identify a component within web content to the user

So this is always text, and specifically about how software uses text to convey something to the user. It's about the underlying <i>accessible name</i>. That might match the label, but it won't if the label is:

- an icon
- a shortened version of a longer, more descriptive name, relying on surrounding content for visual context

Maddeningly, the [ARIA spec describes `aria-label`](https://w3c.github.io/aria/#aria-label) in terms of it being a 'name' too:

> It provides the user with a recognizable name of the object. The most common accessibility API mapping for a label is the accessible name property

So my article about `aria-label` for `<nav>` elements was much easier to read when talking about `aria-label`s as if they were labels, but it wasn’t technically correct. If `aria-label` is always used to give something a 'name', wouldn't `aria-name` have made more sense…?
