---
title: Sometimes when it's false, it's true
intro: Boolean attributes in HTML are quirky, and it's worth knowing how they work in case you end up setting one value and getting the opposite!
date: 2022-05-06
tags:
    - HTML
    - Development
related:
    - booleans-in-aria
---

A short posts on boolean [attributes in HTML](/blog/an-introduction-to-html-attributes). What's a boolean attribute? Something that can only be `true` or false `false`; things like:

- [`reversed`](/blog/reversing-an-ordered-list-in-html)
- `checked`
- `disabled` ([try not to use this](/blog/how-to-avoid-disabled-buttons))

They either are reversed, checked or disabled, or they're not.

The funny thing about booleans in HTML is that they're true *if they exist*. So even if the value is `false` (like `required="false"`) it's still true!

In fact, it doesn't matter what value a boolean attribute is given: `required="true"`, `required="false"`, and `required="banana"` do the same thing.

You can go one step further an remove the value altogether, leaving just `required`, and that's what I do. It differentiates a boolean nicely from other attributes like `class` and `src`.
