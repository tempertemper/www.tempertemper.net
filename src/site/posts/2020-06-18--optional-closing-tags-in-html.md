---
title: Optional closing tags in HTML
intro: |
    One of the interesting things about HTML5 is its flexibility. You don't even need a closing tag on some elements! But be careful with that.
date: 2020-06-18
tags:
    - Development
---

One of the interesting things about HTML5 is its (very deliberate) flexibility. One thing I've always found weird is that some HTML elements don't need a [closing tag](/blog/the-difference-between-elements-and-tags-in-html).

For example, you can write a list without closing the `<li>`:

```html
<ul>
    <li>Red
    <li>Green
    <li>Blue
</ul>
```

It might be because I was writing HTML before the HTML5 spec was supported in browsers (XHMTL was quite strict about closing tags!), but, even though it's valid, it looks a bit off to me.

Aside from it *looking weird*, there are a few good reasons I steer those I teach about HTML away from this type of code.


## It's not for every element

There's a relatively short list of elements you can do this with. So you could come a cropper by omitting the closing tag from a `<ul>`, mistakenly thinking that you're allowed to do it with lists, when it's only the list *item* (`<li>`) that's allowed to be left open.

Here are the elements you're allowed to leave unclosed:

- `<html>`
- `<head>`
- `<body>`
- `<p>`
- `<li>`
- `<dt>`
- `<dd>`
- `<option>`
- `<thead>`
- `<th>`
- `<tbody>`
- `<tr>`
- `<td>`
- `<tfoot>`
- `<colgroup>`


## Getting it wrong breaks things

If you omit that `</ul>` things will break. Browsers do their best to fill gaps and it might end up being that your page doesn't *look* all that broken, but when things break in subtle ways that invalid code might end up getting published.

It could go unnoticed by visitors who use the shiny new version of Chrome on the latest version of macOS, but, as every frontend developer knows, people use all sorts of technologies to read our websites; those old browsers, out of date operating systems, screen readers and voice controllers, might not be as forgiving…


## Makes debugging more difficult

Sometimes, broken HTML is difficult to spot; especially if you're checking your code manually. Finding a missing closing tag that's causing validation issues is much more difficult if there are (perfectly valid) missing closing tags all over the place.


## Finding your place is tricky

Even if there are no validation errors, not having a closing `</li>`, for example, can make finding your place on the page difficult. `<li>`s can contain all sorts of other elements (`<p>`, `<blockquote>`, `<ul>`, you name it!). With all those child and grandchild elements, it can be tricky to find your place without an explicit closing tag.


## Time spent second-guessing

Just as the nesting in CSS or JavaScript would look off without a closing bracket (don't try it -- you'll *definitely* break things!), nested HTML that's missing *some* closing tags and not others lacks some visual order. I start to second-guess my code, which means I'm spending extra time writing it.


## So let's close our HTML elements

Leaving off *some* closing tags is fine from a technical point of view; it might even mean your file is a few bytes smaller! But it can cause problems, and, for me, the fewer problems I encounter as I work, the better!
