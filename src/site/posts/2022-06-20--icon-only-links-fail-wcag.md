---
title: Icon-only links fail WCAG
intro: Icon-only buttons don't fail the Web Content Accessibility Guidelines (WCAG), even though I wish they did, but what about icon-only links?
date: 2022-06-20
tags:
    - Accessibility
summaryImage: icon-only-link.png
summaryImageAlt: A big question mark character with an underline to indicate that it's a link.
---

I've written about how I wish the Web Content Accessibility Guidelines (WCAG) would [prohibit icon-only buttons](/blog/what-i-wish-was-in-wcag-prohibit-icon-only-buttons), but what about icon-only *links*?

You might think they're governed by the same rules, but there's a difference. You might think that links, like buttons, are governed by [Headings and Labels](https://www.w3.org/TR/WCAG21/#headings-and-labels), but links have their own dedicated [Link Purpose (In Context)](https://www.w3.org/TR/WCAG21/) success criterion which says this:

> The purpose of each link can be determined from the link text alone or from the link text together with its programmatically determined link context

Unlike Headings and Labels, which uses the term 'label', Link Purpose (In Context) very specifically talks about "the link text". This is an important distinction, as [WCAG defines labels as](https://www.w3.org/TR/WCAG21/#dfn-labels):

> text or other component with a text alternative that is presented to a user to identify a component

A <i>label</i> can be either text or an icon/image, which is the loophole that allows icon-only buttons. Links, on the other had, are defined much more clearly: it *has to be* text.

I see plenty of icon-only links in interfaces around the web:

- 'Back to top' links that are arrows pointing upwards
- Little cog illustrations that take the user to a settings page
- Navigation that uses a house icon for the 'home' link

These are all failures of 2.4.4 Link Purpose (In Context) because there is no link text.
