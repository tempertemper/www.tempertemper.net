---
title: Focus priming
intro: Most people won't need to know what focus priming is but it's a useful way to test a website's accessibility.
date: 2025-04-29
tags:
    - Accessibility
---

What's it called when you click somewhere on a webpage? Most people either won't care, either because it doesn't seem to do anything, or they can't click because they don't use a mouse or trackpad.

But *I care* because I use it when testing the accessibility of web pages. If I click somewhere and then press <kbd>⇥</kbd> (Tab), keyboard focus is placed on the next interactive element (link, form field, etc.) after where I clicked.


## Focus start position

Let's start at the start.

If a keyboard user presses <kbd>⇥</kbd> once a page has loaded, focus is placed on the first interactive element on the page, often a [skip link](/blog/skip-links-what-why-and-how). A screen reader user will hear the first bit of content on the page (again, probably/hopefully a skip link).

This suggests the *page itself* has focus before you move a muscle; this is your [focus start position](https://mastodon.social/@patrick_h_lauke/114383488828671138).

### It's invisible

Unlike when you focus on interactive elements, that initial focus isn't visible. That's okay, as the page itself:

- isn't interactive
- can't be re-focused

A visual indicator would be misleading.


## Moving focus position elsewhere on the page

Thinking purely about mouse/pointer use, how do we move that initial focus position elsewhere on the page? All you have to do is click somewhere:

- Clicking a link will usually take your focus to a new page, but some links move your focus to a specific part of the page (I'm thinking of those skip links again, but tables of content often do the same thing)
- Clicking a form field is an obvious way to move your focus as the form field you click will get a focus indicator.
- Clicking a button puts focus on that button, which in turn may place focus somewhere else ([like a modal](/blog/buttons-links-and-focus#buttons-and-modals))
- Click on pretty much anything else (an image, or a word in a block of text) it'll get that same invisible focus that the page itself had

The last example is the one we're most interested in here, and it's the one I was struggling to name. I asked around and I very much like '[focus priming](https://mastodon.social/@jtruk/114383257428586767)'.


## How to prime focus

If I want to check if something works for a screen reader user or keyboard-only user, I prime my focus by clicking my mouse pointer next to the thing I want to test.

I *could* use a skip link, or navigate down the page using something like my screen reader's 'go to next heading' shortcut, but it's often quicker to prime my focus using the mouse first.

### Using the keyboard

Here's how to prime focus when testing something for keyboard-only use:

1. Click on some text just before the interactive element you want to tab to
2. Press <kbd>⇥</kbd> to move focus onto the element

Sometimes it's necessary to tab backwards onto an element, in which case do the opposite: click just after the button/link/form field you want to give focus to, then press <kbd>⇧</kbd> (Shift) + <kbd>⇥</kbd>.

### Using a screen reader

To test for screen reader users, [we're not going to use the tab key](/blog/screen-reader-users-and-the-tab-key) as it's not just interactive elements we're interested it; it could be a list, heading, or any other piece of static content.

That aside, it's exactly the same principle:

1. Prime focus by clicking just before the element you want to test
2. Move through the content to element you want to test (for example [VO + <kbd>→</kbd>](/blog/getting-started-with-voiceover-on-macos#navigation-commands-to-get-started))

And the same if you want to test moving back to some content: prime focus just after the content and read back through the interface.


## So now we've know what to call it!

Big thanks to Patrick and James for the what-do-we-call-it suggestions. Neither term clashes with already understood ideas or terminology around more obvious focus placement; nor do they require any extra explanation, as they describe both the default focus position and the act of placing focus somewhere else manually perfectly.
