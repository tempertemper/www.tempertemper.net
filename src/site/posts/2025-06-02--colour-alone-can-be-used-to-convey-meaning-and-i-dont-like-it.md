---
title: Colour alone can be used to convey meaning, and I don't like it!
intro: Despite WCAG's guidance to avoid conveying information with colour alone, there's a caveat that allows it, and I'm not a happy bunny!
date: 2025-06-02
tags:
    - Accessibility
---

[1.4.1 Use of Color](https://www.w3.org/TR/WCAG/#use-of-color) in the Web Content Accessibility Guidelines (WCAG) seems straightforward:

> Color is not used as the only visual means of conveying information, indicating an action, prompting a response, or distinguishing a visual element.

What that's saying is that if we use colour on its own to convey meaning, we're not going to meet 1.4.1. That's not to say we can't use colour to convey meaning at all; we just need to have an additional affordance; maybe [add some text](https://wearecolorblind.com/examples/bbc-online-football-tables/), maybe use an icon, maybe something else.

But there is an exception where colour on its own *can* be used to convey meaning. And, annoyingly, it's buried in WCAG's ['Understanding' piece for 1.4.1](https://www.w3.org/WAI/WCAG22/Understanding/use-of-color.html):

> If content is conveyed through the use of colors that differ not only in their hue, but that also have a significant difference in lightness, then this counts as an additional visual distinction, as long as the difference in relative luminance between the colors leads to a contrast ratio of 3:1 or greater.

My first issue with this is that I really don't think the accompanying Understanding documents are the place to introduce caveats; that should all be done in the main WCAG document.

Now that that's off my chest, let's have a look at what it's saying in plain English. If the contrast ratio of the colour we're using to convey meaning is high enough, and we don't cause any knock-on [1.4.3 Contrast (Minimum)](https://www.w3.org/TR/WCAG/#contrast-minimum) or [1.4.11 Non-text Contrast](https://www.w3.org/TR/WCAG/#non-text-contrast) issues, it's okay not to have any extra affordances.

So to my second issue: this, like many other WCAG exceptions, encourages designers to get lazy; just up the contrast a bit to 3:1 and we can use colour on its own to convey important information.

We should be aiming to make our digital products as accessible as we can and *just enough* doesn't feel right at all.
