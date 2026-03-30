---
title: There's no need to include 'navigation' in your navigation labels
intro: Marking up navigation is pretty straightforward, but here's a nice reminder on how the `aria-label` should be written.
date: 2026-03-30
tags:
    - Accessibility
---

When you mark up your website, groups of links are probably going to be wrapped in `<nav>` elements. If you have more than one, you're going to have to label each of them so that screen reader users know what each is for.

I've written about [How navigation should work for keyboard users](/blog/how-navigation-should-work-for-keyboard-users), which includes a healthy dollop of instructions for making screen reader users' experiences decent too.

I talked about using `aria-label` to differentiate each `<nav>` group, but I didn't really mention *how* to write your labels. Here's the example I used:

```html
<nav aria-label="Primary">
    <ul>
        <li><a href="/home">Home</a></li>
        <li><a href="/about">About</a></li>
        <li><a href="/contact">Contact</a></li>
    </ul>
</nav>
```

Just "Primary". Doesn't look like much, but it's enough. You see, screen reader software announces three things:

- The role of the element
- The name of the element
- The state of the element, if applicable

We're not concerned with the state in this example (if there was a sub-navigation with a button that triggered it, it'd have a state depending on whether it was opened and closed); here we're interested in the role and the name.

The role is gleaned from the `<nav>` element, which has an implicit `role="navigation"`. This lets screen reader users know they've arrived at "navigation".

The name is the <i>accessible name</i> of the element, conveyed by the `aria-label` attribute: "Primary". This might be "Secondary" for another navigation group, or something like "Social", depending on the purpose.

So screen reader software is going to announce something along the lines of "Navigation, Primary" or "Primary navigation landmark".

And now to the reason I wrote this post: including the word "navigation" in your `<nav>` labels. There's no need. If we did, we'd hear something like "Navigation, Primary navigation". Not the end of the world, but unnecessarily repetitive for screen reader users.
