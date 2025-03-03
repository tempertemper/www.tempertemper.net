---
title: There's no such thing as 'menubar navigation'
intro: A problem markup and interaction pattern I encounter a lot is a result of people (understandably) following a misleading example from the W3C.
date: 2025-02-28
tags:
    - Accessibility
---

There's a problem markup and interaction pattern I encounter again and again. Developers search the web with the intention of creating the most accessible navigation, and often end up on [this example of navigation from the W3C](https://www.w3.org/WAI/ARIA/apg/patterns/menubar/examples/menubar-navigation/). But **this is not how navigation should work**.

I'm not sure there's such a thing as 'menubar navigation':

- Menubars are for actions
- Navigation is for, well, navigating

Before we dig into the differences between those, let's talk about a big red flag I spotted with the W3C's menubar navigation oxymoron.


## HTML should be simple

The whole point of HTML is that it's easy to write. Almost every website needs a navigation so it should use the simplest of markup. Looking at the [code for the W3C's example](https://codepen.io/pen?&prefill_data_id=785985a3-ea1e-456b-a0d6-b3b9b331ac59), there are over 70 lines of JavaScript. That's a lot for something that should be straightforward to implement.

Overly-complex code is usually an indicator that something isn't right.


## Menubars are not navigation

Okay, so what exactly is the difference between menubars and navigation? I mentioned above that it's about actions versus navigating, but what does that mean?

### Menubars

[MDN Docs describes menubars](https://developer.mozilla.org/en-US/docs/Web/Accessibility/ARIA/Roles/menubar_role) in this way:

> A menu is a widget that offers a list of choices to the user, such as a set of actions or functions. The menubar type of menu is usually presented as a persistently visible horizontal bar of commands.

So a menubar is a top-level, always-visible menu. And it's for "actions or functions".

The menubar for an app on macOS is a good example: it has a bunch of common top-level groupings like File, Edit, and View; inside each is a menu, containing actions like 'Export', 'Print', 'Copy', 'Paste', 'Enter Full Screen', show/hide sidebars, toolbars, etc. Those are about interacting with your document or changing your editing experience.

Actions/functions are triggered by buttons (via the `<button>` element), *so menubars and menus are for buttons*.

### Navigation

Navigation, on the other hand, is about going places via *links* (the `<a>` element); you press a link and are taken somewhere else. Of course, buttons feature too, but they're usually to show/hide sub-navigation, so are actions that make sense in the context of navigation. The crucial thing is that the last action you take when navigating is always going to a new place.

Links to other places use the `<a>` element ([don't forget the `href` attribute](/blog/links-missing-href-attributes-and-over-engineered-code)). *Navigation is for links*.


## You're probably never going to need a menubar

While navigation is super common on the web, menubars are, or at least should be, few and far between. Again, from MDN Docs:

> Menubars behave like native operating system menubars, such as the menubars containing pull down menus, commonly found at the top of many desktop application windows.

We're talking about websites, so "operating system menubars" aren't really needed very much. In fact, the only application I can think of for a menubar is a browser-based app that mimics a native application, such as Google Docs where that File/Edit/View/Insert/etc. menubar is definitely relevant.


## Confusing for keyboard users

The keyboard behaviour for a menubar is very different to the keyboard behaviour for a navigation. A keyboard user's expectations for a navigation are simple: they're links, so the <kbd>⇥</kbd> (tab) key should take them from one to the next; when they reach the link to the place they want to go to, they follow it with <kbd>⏎</kbd> (Return).

Negotiating a menubar, once an item in the menubar has focus, is generally about the arrow keys to move around and <kbd>⏎</kbd> or <kbd>Space</kbd> to press a button.

If a keyboard user sees navigation, they will expect to be using the tab key a fair amount, but not with the W3C's pattern, as it behaves like a menubar. Instead of moving to the second item in the navigation, their second tab press will move their focus *off* the navigation, meaning they'll have to <kbd>⇧</kbd> (Shift) tab back onto it and try menubar-style keypresses in the hope they work.


## Confusing for screen reader users

The W3C example wraps the menubar in a navigation landmark (via the `<nav>` element) so there is some indication for screen reader users that it's navigation, but it's confusing: first there's the information that it's navigation, then that it's a menubar. Is the navigation role a mistake? Is the menubar role a mistake? Or maybe it's one of those annoying websites that follows the W3C 'Navigation Menubar' markup pattern…


## I get it

The W3C produce the [Web Content Accessibility Guidelines (WCAG)](https://www.w3.org/TR/WCAG/) so if they're suggesting navigation should be marked up like a menu, why would people question that?

There is a warning saying "A pattern more suited for typical site navigation with expandable groups of links is the Disclosure Pattern", but how many readers will notice this, especially as 'Navigation' is the first word in the main heading of the page they're already on?

On top of this, there are apparent benefits the W3C's pattern; particularly for keyboard users, who would only have a single tab stop on the W3C's example. That seems like a good way to get from the top of the page to the main content of the page quickly, but there's a better way to do that: [a skip link](/blog/skip-links-what-why-and-how).

The pattern itself is fine for menubars, so the best thing for the W3C to do would be to change the example and remove any reference to the pattern being in any way 'navigation'.
