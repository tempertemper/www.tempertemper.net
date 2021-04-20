---
title: The difference between strikethrough and del
intro: |
    Just like `<em>` and `<i>`, and `<strong>` and `<b>`, the distinction between `<s>` and `<del>` is subtle, but it's worth knowing.
date: 2021-04-14
tags:
    - HTML
    - Development
---

Just like `<em>` and `<i>`, and `<strong>` and `<b>`, the distinction between `<s>` and `<del>` is subtle, but it's worth knowing.


## Things that have been deleted

[The deleted text (`<del>`) element](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/del) is a good place to start as its use is much more specific than strikethrough:

> text that has been deleted from a document

So `<del>` should be used for things like:

- code 'diffs', to see what code has been removed from a document
- word processors that track changes
- 'done' to-do list items


## Things that no longer apply but are still meaningful

The [explanation of strikethrough (`<s>`) in MDN Web Docs](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/s), my usual go-to reference point, falls a little short of its usual high standard:

> things that are no longer relevant or no longer accurate

If the text is no longer relevant, why are we keeping it? My take on this that struck through text:

- has to be relevant to the *document*
- no longer *applies*

Some examples are probably helpful:

- Sold out tickets, where the original listing lets late-comers know what they've missed out on
- Discounts! That initial price is important so buyers know how much they're saving
- Corrections, where you want to communicate what you originally wrote as well as what you replaced it with

A correction might look like this:

```html
<p>When he returned from the barbers with a terrible haircut, my first instinct was to <s>laugh out loud</s> console him</p>
```

Strikethrough is more multi-purpose than `<del>`, which has a very specific use, so for formatting text in paragraphs `<s>` is almost certainly element to reach for.
