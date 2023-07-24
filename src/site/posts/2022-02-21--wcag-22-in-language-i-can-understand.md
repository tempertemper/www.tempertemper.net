---
title: WCAG 2.2 in language I can understand
customURL: wcag-2-2-in-language-i-can-understand
intro: A follow-up to my post on the Web Content Accessibility Guidelines 2.1, level AA; this time explaining the nine rules coming up in WCAG 2.2.
date: 2022-02-21
updated: 2023-07-24
tags:
    - Accessibility
summaryImage: wcag-2-2.png
summaryImageAlt: The letters ‘WCAG’ with ‘2.2’ underneath.
related:
    - wcag-but-in-language-i-can-understand
    - wcag-aaa-in-language-i-can-understand
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

<i>The [W3C moved WCAG 2.2 to 'Proposed Recommendation'](https://www.w3.org/news/2023/web-content-accessibility-guidelines-wcag-2-2-is-a-w3c-proposed-recommendation/) on the 20th of July 2023, and a fair amount changed since I first published this back in February 2022. This document has been updated to reflect those most recent changes.</i>


## 2.4.11 Focus Not Obscured (Minimum)

When tabbing to a focusable item, the element should be at least partially visible; not completely covered by a 'sticky' footer element, for example. This ensures keyboard users can see where the item that currently has focus is.


## 2.4.12 Focus Not Obscured (Enhanced)

Almost the same as 2.4.12 Focus Not Obscured (Minimum), but the focusable item should be *fully* visible when it's tabbed to, so that no scrolling is necessary to bring it into view.


## 2.4.13 Focus Appearance

The focus indicators for keyboard users are easy to spot: they are at least 2px thick and have a contrast ratio of 3 to 1 (or higher) against their unfocused state. This [used to be a lot more complex](/blog/focus-appearance-explained).


## 2.5.7 Dragging Movements

An action that is achieved by dragging from one point to another, for example drag-and-drop for reordering, can also be carried out by pressing buttons ('Move item up', 'Move item down', etc.).

This is related to [2.1.1 Keyboard](/blog/wcag-but-in-language-i-can-understand#211-keyboard) and [2.5.1 Pointer Gestures](/blog/wcag-but-in-language-i-can-understand#251-pointer-gestures).


## 2.5.8 Target Size (Minimum)

<i>[2.5.5 Target Size](https://www.w3.org/TR/WCAG21/#target-size) has been renamed slightly: 2.5.5 Target Size (Enhanced) and a new Minimum requirement has been added at level AA.</i>

Anything clickable should be at least 24 by 24 pixels, except links within a sentence which will just be the size of the text.


## 3.2.6 Consistent Help

Some form of help is available from every page, whether contact details, a contact form, a link to a contact page, or a link to help documentation.


## 3.3.7 Redundant Entry

If the user as already given some information, it's either:

- not asked for again
- pre-populated in the subsequent field
- available to select in a dropdown


## 3.3.8 Accessible Authentication (Minimum)

If the user is required to log in, they don't have to remember a password, for example they can:

- copy and paste a password into the right form field
- use password manager software to fill out the log-in details automatically
- have a verification link sent to their email

It also allows photo recognition, like [CAPTCHAs](https://en.wikipedia.org/wiki/CAPTCHA) and user-uploaded picture recognition.


## 3.3.8 Accessible Authentication (Enhanced)

Almost the same as 3.3.7 Accessible Authentication, but CAPTCHAs and user-uploaded pictures can't be included in the log-in process.


## 4.1.1 Parsing

This has been removed from the specification.
