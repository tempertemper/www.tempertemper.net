---
title:  Focus appearance explained
intro: There's some great stuff coming up in WCAG 2.2, but there's one rule that's particularly difficult to understand, so here it is in a bit more detail.
date: 2022-09-02
updated: 2023-07-24
tags:
    - Accessibility
related:
    - wcag-2-2-in-language-i-can-understand
---

There's some great stuff coming up in version 2.2 of the Web Content Accessibility Guidelines (WCAG), but there's one rule that's particularly difficult to understand: [2.4.13 Focus Appearance](https://www.w3.org/TR/WCAG22/#focus-appearance).

<i>Update: this rule was simplified considerably when [WCAG 2.2 moved to 'Proposed Recommendation'](https://www.w3.org/news/2023/web-content-accessibility-guidelines-wcag-2-2-is-a-w3c-proposed-recommendation/) on the 20th of July 2023; it was also moved from level AA to AAA. So this article probably isn't very useful anymore!</i>


## What the rule applies to

The rule applies to 'user interface components', which means:

- form fields
- links
- buttons

Essentially, any element you typically interact with.

<i>Note: this can also include things like [horizontally-scrolling tables](/blog/accessible-responsive-tables).</i>


## Indicator style

The focus indicator must:

- be at least 2px thick
- be a solid line
- go round the whole element


## Colour contrast

The contrast of the focus indicator is covered already in [1.4.11 Non-text Contrast](https://www.w3.org/TR/WCAG21/#non-text-contrast), so it must provide at least a 3:1 contrast ratio against:

- 3:1 against the background it sits on
- 3:1 against the element (e.g. a button) that has focus

The focus indicator should also be obvious compared to

- how the element looked *before it had focus*
- other similar elements that aren't in focus

So Focus Appearance sets the contrast ratio of the focus indicator against the focused element's unfocused state at at least 3:1.
