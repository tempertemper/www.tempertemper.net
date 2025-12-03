---
title: Should pagination take you to a new page?
intro: |
    'Pagination' has comes from the word 'page', so yes, pagination should consist of pages. But the question is totally valid; worth digging into!
date: 2025-12-03
tags:
    - HTML
    - Accessibility
    - Design
---

Yes. I mean, 'pagination' is derived from the word 'page'. You might even wonder why this article is even necessary, but "Is pagination a new page?" is actually a reasonable question when designing with accessibility in mind. Let me explain.


## It's all about focus behaviour

The idea is that pagination divides a list up into pages, and it's all housed in a `<nav>` (I've [written about the markup elsewhere](/blog/an-accessible-pagination-pattern-or-two)), which are used to house links to other pages. Each item in a pagination group is a separate page.

But this comes with a downside: focus is reset to the top of the page with each press of a pagination link. That could get quite boring/arduous for a keyboard or screen reader user who would have to navigate back to the pagination to go to each subsequent page.

So our thoughts rightly turn towards making the experience work well for *everyone*. Two options present themselves:

- Keep focus where it is, allowing the user to, say, go to the seventh page, by hitting 'Next' several times
- Move focus to the top of the list itself, rather than the page; saving the user having to skip past the page header and any other content that lives before the paginated list

Both of these fit the [definition of a button](/blog/buttons-links-and-focus): they perform an action that causes a change on the page, and focus is managed carefully. Let's explore each of those approaches.

### Keeping focus in place

If focus remains on the pagination button that was pressed, we have to be sure the user knows it has happened. We'd use `aria-live` to announce the change for screen reader users, but we'd have to rely on sighted users spotting that the content above the button had changed. This might be especially tricky on more constrained viewports; smaller mobile devices or when someone has increased the page zoom level.

But more importantly, how does the user know what page they need to be on? The journey from page 1 to page 7, to use our earlier example, would involve checking the items on each page until they find the one they're looking for.

So a mouse or touch screen user would hit the 'Next' button, scroll to the top of the paginated area, then scroll back down again, scanning the list. A keyboard user would have to tab backwards to the start of the paginated content, then tab through it again to get to the 'Next' button again. Worst of all, a screen reader user would have to listen backwards through the paginated items, then listen forwards again.

That's a lot of work we'd be asking people to do, when we're looking to make things easier.

### Return focus to the top of the list

The biggest problem with the keep-focus-in-place approach is the scrolling back to the top of the list, so how about doing that bit for people? This is a reasonable approach: we place focus at the top of the list with each button press, which:

- Ensures sighted users know they've paginated
- Saves reverse tabbing for keyboard-only users
- Saves backwards reading for screen reader users

We'd just need to add an accessible name to the element where focus lands, so that screen readers are told what 'page' they're on each time.

This all sounds very familiar…


### Overthinking it

Placing focus at the beginning of the list is pretty much the sort of behaviour we'd experience if it were just a new page.

Instead of landing directly on the list, our [focus would be primed](/blog/focus-priming) at the top of the page. Screen reader users would hear the page `<title>`, which should include the new page number, and there would be some kind of on-page indication of the new page number.

But there's still all that pesky header content to get past. Luckily, we have mechanisms in place to ease the tedium:

- A screen reader user has a bit of an advantage here as they can use shortcut commands to navigate straight to the page's `<h1>` or the `<main>` landmark
- Keyboard users (and screen reader users) can use the [skip link](/blog/skip-links-what-why-and-how) to get to the meat and potatoes of the page


## What else do we need to do?

So we can agree they're pages, not pseudo-pages in a panel on the same page. Anything else is overcomplicating it. But what else is needed?

### Add the page number to the title

Screen reader users should hear the contents of the `<title>` element when they arrive on a new page, reassuring them that they've landed on the right page.

```html
<title>Blog page 2</title>
```

This also updates the browsing history, making it easier to find the page you want to go back to.

No need to include any details of the page number on the first page.

### Add the page number at the top of the page

Sighted users may spot the updated page number in their browser tab, where the `<title>` is visible, but there's not a great deal of space up there so it's better to give them that bit of reassurance elsewhere too.

It's up to you as to *where*, but make sure it's somewhere:

- after the `<h1>`
- before the paginated content

It could even be included *in* the `<h1>`. On my blog, I output a paragraph just after the `<h1>`.

And, like the page title pattern, there's no need to do this on the first page.


## Some good advice

I'll finish up with a quote or two from Karl Groves' [AFixt article on pagination](https://afixt.com/a-quick-primer-on-accessible-pagination/):

> Pagination typically involves navigation between different resources or views … When a button is used instead, this context is often lost or has to be artificially recreated … making the experience brittle or inconsistent.

So yes, pagination should take you to a new page.
