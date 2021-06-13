---
title: Refining focus styles with focus-visible
intro: |
    `:focus-visible` triggers only on keyboard focus; not on click. This can make our interfaces cleaner, but should it replace `:focus` completely?
date: 2021-05-25
updated: 2021-06-03
tags:
    - CSS
    - Development
summaryImage: large
---

The `:focus` pseudo selector has always been a bit of a pain. In lots of browsers, an item is considered focused when it is clicked with a pointing device like a mouse or trackpad. This means there's a flash of a link or button's focus styling when it's activated:

1. The link or button styling tells you it's clickable
2. The `:active` state tells you you're pressing it
3. When you release your click, the link or button is considered activated

Step 3 is when the focus style will typically appear, which is:

- too late to be useful
- visually messy

Firstly, it's worth mentioning that Safari is the outlier here: it behaves nicely and doesn't add `:focus` styling to links or buttons when they're activated. Firefox, Chrome, and every other browser I've tested in do.

This is where the `:focus-visible` pseudo class comes in. It only shows a focus styling only when an element has *keyboard* focus, so it looks like our problem with `:focus` could be solved!

The good news is that `:focus-visible` is [supported in every modern browser except Safari](https://caniuse.com/css-focus-visible), so we can have a nice, tidy link/button clicking experience across *all* browsers!


## How do we add `:focus-visible`?

`:focus-visible` is actually a tricky progressive enhancement. It's not as simple as removing `:focus` styles in favour of `:focus-visible` as that would mean there were no focus styles styles on some browsers, notably Safari.

I worried that, because Safari's visual click behaviour with `:focus` is already very nice, `:focus-visible` might not be very in their list of priorities. The good news is that [support was added in Safari Technology Preivew 122](https://developer.apple.com/safari/technology-preview/release-notes/#r122), although it has to be activated in the Develop menu's Experimental Features list.

We need to:

1. Keep our classic `:focus` styling for Safari and, perhaps, legacy browsers like Internet Explorer
2. If a browser supports `:focus-visible`:
    1. remove the `:focus` styling
    2. add our focus styling back in with `:focus-visible`

Here's some example CSS for link focus styles:

```css
/* For browsers that don't support :focus-visible */
a:focus {
  outline: 3px solid rebeccapurple;
}

/* Remove :focus styling for browsers that do support :focus-visible */
a:focus:not(:focus-visible) {
  outline: none;
}

/* Add focus styling back in browsers that do support :focus-visible */
a:focus-visible {
  outline: 3px solid rebeccapurple;
}
```


## When to use `:focus-visible`

A word of caution: I don't think [`:focus-visible` as a blanket replacement for `:focus`](https://twitter.com/LeaVerou/status/1045768279753666562?s=20).

As I say, it's great for tidying up click styling on links and buttons, and could be really useful for other focusable elements where a focus outline:

- is essential for keyboard users' orientation
- could be confusing for user who click inside an element

But for other elements `:focus` is still the right approach. For example, it's *always* useful to have an obvious focus marker when your cursor has been placed in a form text input, regardless of whether you tabbed to it with your keyboard or clicked into it with your mouse.
