---
title: Booleans in ARIA
date: 2022-05-09
intro: HTML booleans are bit quirky but, as if just to complicate things, booleans in ARIA work slightly differently. It's worth knowing how and why.
tags:
    - Development
related:
    - sometimes-when-its-false-its-true
updated: 2022-05-11
---

[HTML booleans are bit quirky](/blog/sometimes-when-its-false-its-true) but, to complicate things, booleans in ARIA work differently, even though they're both attributes that are added to [an opening HTML tag](/blog/the-difference-between-elements-and-tags-in-html#tags).

HTML doesn't care about the value an attribute is given: if it exists it's true, if it doesn't it's false. ARIA, on the other hand, *does care*.


## False usually matters

Something like `aria-expanded` allows values of `true` and `false`. `aria-expanded="true"` returns true and `aria-expanded="false"` returns false.

If this was HTML they'd both return true, but the difference with `aria-expanded` is that the false value is significant: it might be telling the user that a button has a dropdown menu that's currently closed.

This significance is because of ARIA's utility nature. Running with `aria-expanded` as an example, the closest thing in HTML is the `<details>` element with its `open` attribute:

- If `open` is present, the `<details>` widget is open
- If `open` is not present, the `<details>` widget is closed

Here, `open` is closely tied with the `<details>` element, which is either opened or closed. `aria-expanded` is usually used on a generic element like `<button>` that could have any purpose; not just showing and hiding content.


## More than just a boolean

There are also situations where an ARIA attribute allows more than just `true` and `false`, like:

- `aria-checked`, which can also accept `mixed`
- `aria-invalid`, which has `spelling` and `grammar` options
- `aria-current`, which also allows `page`, `step`, `location`, `date`, and `time`

HTML doesn't have this extra nuance.


## Stricter syntax

Because ARIA attributes cares about their values, a boolean ARIA attribute doesn't work at all unless it has a value. Even `aria-required`, which is only really useful when it's set to `true`, and doesn't have any extra values on top of `true` and `false`, needs to be written out in full.

---

<b>Update</b>: for a more in-depth look booleans, the ARIA and HTML specifications, how we can set them with JavaScript, check out [Hidde de Vries's article](https://hidde.blog/boolean-attributes-in-html-and-aria-whats-the-difference/).
