---
title: Accessible responsive tables
intro: |
    Tables can be tricky to make work responsively; they can also be tricky to make accessible. Here's a step by step guide to making your tables both!
date: 2021-05-28
tags:
    - Accessibility
    - Development
    - HTML
    - CSS
summaryImage: large
---

I make a point of not adding 'just in case' CSS to my website. Until last week's article about [AVIF and WebP file sizes sometimes being bigger than their source PNG or JPEG](/blog/avif-and-webp-are-not-always-better-than-png-and-jpg) there were no tables, so before I could publish I needed to write some HTML and CSS!

One approach to tables that work on all sorts of screen sizes is to [reformat the table on smaller screens](https://www.afenwick.com/blog/2021/responsive-accessible-table/). Essentially, you take each table row and repurpose it as a sort of `<dl>`, visually, where each row is presented as a series of keys (each table header) and a values (the value under the header).

This can be a really effective approach, but when comparison of table data is central to the meaning of the table, something else is needed.

The alternative might surprise you: present the table more or less as-is on mobile; no layout adjustment necessary!

But is that really responsive? And what about accessibility? Responsive websites are basically about avoiding scrolling in two directions, and the [Reflow Success Criterion](https://www.w3.org/TR/WCAG21/#reflow) (SC) in the Web Content Accessibility Guildelines (WCAG)says content should be:

> presented without loss of information or functionality, and without requiring scrolling in two dimensions

Sounds like we might be stuck, but it continues:

> Except for parts of the content which require two-dimensional layout for usage or meaning

So while we normally have to ensure that our users only have to scroll up and down the page on small screens, we're allowed content that also scrolls horizontally if it's important for maintaining its meaning, like it is with a data comparison table.


## Making our tables scroll horizontally

The first challenge here is that we don't want our horizontally scrolling tables to make the whole screen scroll left and right; just the table itself. We need a container:

```html
<div class="table-container">
    <table><!-- Table contents --></table>
</div>
```

And we need some CSS to make the table scroll inside its container:

```css
.table-container {
  overflow: auto;
}
```


## What about keyboard users?

That's great if you're using a mouse or a touch screen where you can scroll the table left and right within its container, but what about keyboard users?

The [Keyboard SC](https://www.w3.org/TR/WCAG21/#keyboard) says:

> All functionality of the content is operable through a keyboard interface

If a keyboard user can't move the table content into view it's inaccessible to them, so what do we do? We have to make the table container *focusable*; once it has focus the left and right arrow keys will scroll its content horizontally.

```html
<div class="table-container" tabindex="0">
    <table><!-- Table contents --></table>
</div>
```

So now a keyboard user can view the whole table on small screens, just like every other user! But how do they know when it has focus?


## Focus styling

We just need a wee bit more CSS to ensure our tables get an outline when focused:

```css
.table-wrapper:focus {
  outline: 3px solid rebeccapurple;
}
```

But I reckon we can do better. The focus indicator is essential for keyboard users, but with `:focus` it also appears when someone clicks or taps on the table. This is unnecessary and can be a bit distracting, especially as you can't remove focus styling on touch screens by tapping outside of the table; you have to tap *something else that's focusable*.

The [`:focus-visible` pseudo class is a great new addition to CSS](/blog/refining-focus-styles-with-focus-visible) that shows a focus styling only when an element has *keyboard* focus.

For now, dropping `:focus` would mean there were no focus styles styles in some browsers, so we need to keep our focus styling and override it if `:focus-visible` is supported:

```css
.table-wrapper:focus {
  outline: 3px solid rebeccapurple;
}

.table-wrapper:focus:not(:focus-visible) {
  outline: none;
}

.table-wrapper:focus-visible {
  outline: 3px solid rebeccapurple;
}
```


## Accessible name

The [fifth Rule of ARIA Use](https://www.w3.org/TR/using-aria/#fifthrule) states:

> All interactive elements must have an accessible name

As our table is now focusable and scrollable, so like any other interactive element it needs an accessible name.

In order to do this, we need to:

1. give our container a 'role', either explicitly with the `role="region"` attribute, or by changing the element to a `<section>`
2. label our container, either with `aria-label`, or `aria-labelledby` and a corresponding element (in this case, the `<caption>` element is an ideal label)

This will satisfy the [Name, Role, Value SC](https://www.w3.org/TR/WCAG21/#name-role-value) which demands:

> For all user interface components … the name and role can be programmatically determined

<i>A [user interface component is defined](https://www.w3.org/TR/WCAG21/#dfn-user-interface-components) as: <q>a part of the content that is perceived by users as a single control for a distinct function</q>.</i>

So our markup looks more like this:

```html
<section class="table-container" tabindex="0" aria-labelledby="caption">
    <table>
        <caption id="caption">The title of the table</caption>
        <!-- Table contents -->
    </table>
</section>
```

And with that, our table is both responsive and accessible!
