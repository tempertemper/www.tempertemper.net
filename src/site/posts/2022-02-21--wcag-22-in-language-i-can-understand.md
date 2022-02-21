---
title: WCAG 2.2 in language I can understand
customURL: wcag-2-2-in-language-i-can-understand
intro: A follow-up to my post on the Web Content Accessibility Guidelines 2.1, level AA; this time explaining the nine rules coming up in WCAG 2.2.
date: 2022-02-21
tags:
    - Accessibility
related: wcag-but-in-language-i-can-understand
---

I organised my [breakdown of the Web Content Accessibility Guidelines (WCAG) 2.1 AA](/blog/wcag-but-in-language-i-can-understand) using their POUR (Perceivable, Operable, Understandable, Robust) grouping, but since there are only nine criteria in the up-coming version 2.2 I'll just list them one by one.

Again, that caveat:

- This is for me, but hopefully it will help you get started understanding the intent of each rule (or 'success criterion')
- It’s not a comprehensive explanation; you’ve got [WCAG itself](https://www.w3.org/TR/WCAG22/) for that:
    - It's over-simplification in order to get to the essence of each criterion
    - Lots of exceptions have been left out, in order to keep things concise
    - There are very few measurements
- I haven’t gone into why each criterion is helpful
- There are very few examples, except where they help keep things brief

Before I dive in, it's worth mentioning that 2.2 hasn't quite made it to final release yet, so there's an outside chance that things could change between now and release. But at this stage, if something were to change, it's likely to be small tweaks and adjustments rather than anything major.


## 3.3.7 Accessible Authentication

If the user is required to enter a password, there's at least one way for them to do it without relying on memory. A simple way would be the ability to copy and paste a password into the right form field or, even better, for the password manager to fill out the log-in details automatically. Also: avoid [CAPTCHAS](https://en.wikipedia.org/wiki/CAPTCHA)!


## 2.5.7 Dragging Movements

An action that is achieved by dragging from one point to another (for example, drag-and-drop for reordering) can also be performed using individual clicks or by pressing buttons.

This is related to [2.1.1 Keyboard](/blog/wcag-but-in-language-i-can-understand#211-keyboard) and [2.5.1 Pointer Gestures](/blog/wcag-but-in-language-i-can-understand#251-pointer-gestures).


## 3.2.6 Consistent Help

Some form of help is available from every page, whether contact details, a contact form, a link to a contact page, or a link to help documentation.


## 2.4.13 Page Break Navigation

The number of pages in an ebook can change dramatically depending on what fits on each page; this is down to font choice, size, spacing, the device size, and so on. There should a way to mark each page according to those in the physical book, so that if someone were to say "Turn to page 100", *everyone* knows where page 100 is.


## 2.4.11 Focus Appearance (Minimum)

The focus indicators for keyboard users are easy to spot: they have a contrast ratio of 3 to 1 (or higher) with their contents, their surroundings, and also the unfocused state.

There are a couple of ways the indicator can look, but an outline is the simplest.


## 2.4.12 Focus Appearance (Enhanced)

The only AAA requirement in 2.2. This is the same as the Minimum requirement, only it requires a 4.5 to 1 contrast ratio, and the outline must be at least 2 pixels thick.


## 3.2.7 Visible Controls

When an action can be performed on something, the action buttons are always visible; not, for example, hidden and only revealed on hover or keyboard focus.


## 2.5.8 Target Size (Minimum)

<i>[2.5.5 Target Size](https://www.w3.org/TR/WCAG21/#target-size) has been renamed slightly: 2.5.5 Target Size (Enhanced) and a new Minimum requirement has been added at level AA.</i>

Anything clickable should be at least 24 by 24 pixels, except links within a sentence which will just be the size of the text.


## 3.3.8 Redundant Entry

If the user as already given some information, it's either:

- not asked for again
- pre-populated in the subsequent field
- available to select in a dropdown
