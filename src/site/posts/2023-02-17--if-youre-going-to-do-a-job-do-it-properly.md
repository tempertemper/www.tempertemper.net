---
title: If you're going to do a job, do it properly
intro: I often hear the phrase “forward fix” used when referring to accessibility. It sounds fancy, but what it really means is “We’ll come back to the accessibility bit later”.
date: 2023-02-17
tags:
    - Accessibility
---

Making your work accessible to all is doing the job properly.

All too often I hear the phrase "forward fix" used when referring to accessibility. It sounds fancy, but what it really means is "We'll come back to the accessibility bit later".

The problem here is that:

- <i>later</i> could take months or even years to come around
- it sets a dangerous cultural precedent
- the feature may need fundamental rework, not just just an addition of a few lines of code to <i>add accessibility</i>

Imagine a world where websites and applications were built buy keyboard users for keyboard users. Since the designers and developers don't use a mouse, they do what Manuel Matzo did in his tongue-in-cheek article [Building the most inaccessible site possible with a perfect Lighthouse score](https://www.matuzo.at/blog/building-the-most-inaccessible-site-possible-with-a-perfect-lighthouse-score/) and use CSS to hide the mouse cursor with:

```css
*,
*:hover {
  cursor: none;
}
```

They also stop accidental mouse clicks with:

```css
body {
  pointer-events: none;
}
```

Sounds ridiculous, doesn't it… That's what we do when we defer accessibility as a forward fix.

Designing and building without considering accessibility is a job part-done and, by that reasoning, there are *way* too many half made websites and apps out there.
