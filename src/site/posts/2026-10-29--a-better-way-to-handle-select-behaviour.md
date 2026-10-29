---
title: A better way to handle select behaviour
intro: "Building your own select means lots of code and difficult decisions about behaviour. Good news though: there's a new way that makes things much simpler!"
date: 2026-10-29
tags:
    - Accessibility
    - HTML
    - CSS
---

I've encountered this time and time again: designers want fully branded and styled dropdown menus in forms; developers [haven't been able to achieve it with the native `<select>`](/blog/form-styling-limitations-are-an-accessibility-issue) element, so they've built their own.

That usually involves a bunch of `<div>`s, `<span>`s, lots of JavaScript, and a good deal of ARIA. It's a lot of work.

While it's totally possible to build a fully accessible select menu without using the `<select>` element, there's a big issue with this approach: the *behaviour* of the component and the difficulties meeting our users' expectations across platforms.

In a 'how to' article from a couple of years ago, I cover how the [`<select>` keyboard experience differs between Mac and Windows](/blog/how-to-browse-the-web-with-the-keyboard-alone#select-dropdowns), and this highlights our problem when custom-building our component.


## What are the differences?

How different can they be? Surely they're just a button that triggers a menu where an option can be picked? Well, first let's examine how they work on a Mac:

- The menu must be open to choose an option (sounds obvious, but wait until we get to Windows…)
- The button is covered when the menu is open
- The focused/highlighted option in the menu is not the selected option; selecting an option requires an explicit button press or click
- They're modal, so the page behind is inert until the menu is dismissed

<i>Note: the modal is 'light dismissed', so clicking/tapping outside the menu closes it and makes the page active again.</i>

Addressing those same four points, here's how it works on Windows:

- An option can be selected without opening the dropdown
- The button remains visible when the menu is open
- The focused/highlighted option is the selected option
- They're non-modal, so the page behind the menu can be interacted with directly (you can still scroll)

<i>Note: the menu is still dismissed with a click/tap outside of it, but, unlike on a Mac, that same click/tap also interacts with the page.</i>

### How it is often tackled

So most conversations I've had with conscientious developers have been around:

- Should the options be selectable without opening the menu? (<i>No</i> tends to be the sensible answer here)
- Is the button still visible when the menu is open? (This is always a firm <i>Yes</i>)
- Are the options selected on focus? (Normally ends up a <i>No</i>, to make the choice more deliberate)
- Should the menu be modal? (This is usually a more nuanced discussion)

Some of those choices are Mac-like, and some are Windows-like, so the problem we create is that Mac users expect Mac behaviour and Windows users expect Windows behaviour. Even if the decisions we make are solid, neither Mac nor Windows users get the exact behaviour they're accustomed to.


## Browsers to the rescue

There's a [new specification for `<select>` styling](/blog/fully-customise-your-select-elements) that is rolling out in browsers, which includes `appearance: base-select`. This resets the look and feel as well as the behaviours, creating a sort of 'best of' from both Mac and Windows.

I do find it weird that CSS affects how something behaves like this; especially as it's called `appearance`. Maybe an HTML attribute would have been a cleaner approach? Anyway, let's have a look at those four behaviours again:

- The menu must be open to choose an option (Mac)
- The button remains visible when the menu is open (Windows)
- The highlighted option is not the selected option; selecting an option requires an explicit button press (Mac)
- Non-modal (Windows)

This matches the way I've always thought `<select>` elements should work, and gives me some reassurance that the design choices I've made with developers over the years have been good ones.

Until now, building our own select has meant choosing between different native behaviours, or inventing a combination of them. If we still need to build a replacement, we can now do it *better* by following that one native interaction model.

But the *best* approach might be not to build a replacement at all. Now that we can fully customise the appearance of a native `<select>`, we can leave those interaction decisions, and the not-insignificant work of implementing them, to the browser.
