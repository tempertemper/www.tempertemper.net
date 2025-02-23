---
title: "Skip links: what, why, and how"
intro: Ever noticed one of those "Skip to main content" links when you press the tab key? They're important.
date: 2025-02-23
tags:
    - Accessibility
---

If you're like me an you're a bit of a hybrid mouse/keyboard user, you might have noticed something as you browse the web: pressing the <kbd>⇥</kbd> (tab) key when you land on a website sometimes causes a previously-hidden "Skip to main content" (or words to that effect) link to appear.

'Skip links' are a great way for keyboard-only users to move their focus past the header and navigation of a web page, directly to the main content area so they can begin interacting with the page.

This is so important that the Web Content Accessibility Guidelines (WCAG) contains a rule called [2.4.1 Bypass Blocks](https://www.w3.org/TR/WCAG/#bypass-blocks), which says:

> A mechanism is available to bypass blocks of content that are repeated on multiple web pages.


## Why is this so important?

Imagine there's an interesting looking link in the opening paragraph of a blog post:

- A mouse/trackpad user only has to move their cursor from wherever it's currently resting to the link
- A touch-screen user would just tap the link
- A speech recognition software user would say the command to 'click' the link
- [A sighted or partially sighted screen reader user](/blog/not-all-screen-reader-users-are-blind) could move their cursor to the main heading of the page with a quick keyboard shortcut, then jump to the link
- A blind screen reader user wouldn't not know the link was there on page load, but they will quickly discover it after jumping to the main heading and reading through the first paragraph of content

But it's not so easy for a keyboard-only user; especially when there are a lot of navigation items, even multiple blocks of navigation, to tab past.

- [Amazon's homepage](https://www.amazon.co.uk) currently has 44 tab stops before the main page content
- MDN Docs has 38 in the main header, plus any breadcrumb navigation, a language switcher, links to related articles (sometimes over 100), and an outline of the article
- [Wikipedia](https://en.wikipedia.org/wiki/Main_Page) has 18 tab stops before the article-specific navigation
- GitHub has 11
- Even the relatively navigation-light GOV.UK can have up to 6 or so, depending on the page

Keyboard-only users are often keyboard-only users because they have a motor impairment that prevents them using a mouse; this often means they experience fatigue quickly. Repeatedly pressing the tab key before getting to the main chunk of content can be a lot of work, particularly when this would have to be done on *every page*. Luckily, all of the examples I list above have a skip link of some sort, meaning it's a single <kbd>⇥</kbd> press to show and focus the skip link, then <kbd>⏎</kbd> (Return) to follow it.


## How to do it

Skip links are great. And they're really easy to implement too, requiring zero JavaScript. First, the HTML:

```html
<header>
    <a href="#main" class="skip-link">
        Skip to main content
    </a>
    <!-- Header and navigation content -->
</header>
<main id="main" tabindex="-1">
    <!-- Page contents -->
```

No fancy code there:

- A main content container with an `id`
- A link that goes to that `id`
- The link has a class, so that we can add CSS to show/hide it
- The `<main>` element has a `tabindex="-1"` to to ensure browsers put focus on it reliably

And the CSS?

```css
.skip-link:not(:focus):not(:active) {
  clip-path: inset(50%);
  height: 1px;
  overflow: hidden;
  position: absolute;
  white-space: nowrap;
  width: 1px;
}
main:focus {
  outline: none;
}
```

This looks a bit more complex ([TPGi have a good breakdown of the technique](https://www.tpgi.com/the-anatomy-of-visually-hidden/)), but all it's doing is:

- Visually hiding the skip link when it doesn't have focus
- Showing the skip link when it receives focus
- Preventing the main content container getting a focus outline when the skip link is followed (since a focus indicator would suggest it's interactive, and it's not; plus focus can't be placed back onto it manually so an indicator would be doubly misleading)

That's it!
