---
title:  Focus appearance explained
intro: There's some great stuff coming up in WCAG 2.2, but there's one rule that's particularly difficult to understand, so here it is in a bit more detail.
date: 2022-09-02
updated: 2022-09-22
tags:
    - Accessibility
---

There's some great stuff coming up in version 2.2 of the Web Content Accessibility Guidelines (WCAG), but there's one rule that's particularly difficult to understand: [2.4.11 Focus Appearance](https://www.w3.org/TR/WCAG22/#focus-appearance-minimum).

I cover it in my [over-simplified explanation of WCAG 2.2](/blog/wcag-2-2-in-language-i-can-understand), but this is one where it's worth going into more detail.


## What the rule applies to

The rule applies to 'user interface components', which means:

- form fields
- links
- buttons

Essentially, any element you typically interact with.

<i>Note: this can also include things like [horizontally-scrolling tables](/blog/accessible-responsive-tables).</i>


## Indicator style

There are two ways to indicate focus:

1. An outline
2. A shape

I'm going to run with the outline approach as that's the one most designers are likely to use.

The focus outline should:

- be at least 1px
- be a solid line
- go round the whole element


## Colour contrast

The colour of the focus indicator is important too, so that it stands out nicely. The contrast ratio must be at least:

- 3:1 against the unfocused state of the element
- 3:1 against the background it sits on
- 3:1 against the element (e.g. a button) that has focus

There's a wee bit of flexibility here, but I'd keep it simple and use those three rules. If you *really* want to know, the contrast ratio can be less than 3:1 against the element that has focus, and the element in its unfocused state, but the indicator must be at least 2px thick.

An example would be a button, where the indicator might be the same colour as the button (1:1), but if the button *grows* by 2px along all four edges, that's allowed. *There still has to be enough contrast against the background*, though: you need to be able to see that the element is bigger!
