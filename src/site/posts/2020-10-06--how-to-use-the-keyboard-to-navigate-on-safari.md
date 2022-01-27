---
title: How to use the keyboard to navigate on Safari
intro: |
    A great way to start accessibility testing is to navigate with the keyboard. Safari is limited by default, so here's how get it working properly.
date: 2020-10-06
tags:
    - Accessibility
related:
    - using-the-keyboard-to-navigate-on-macos
---

One of the best starting points when testing a web page's accessibility is to put your mouse to one side and use your keyboard to get around.

Unlike Firefox, Chrome, Opera and Edge, navigating a web page with the keyboard alone isn't all that great an experience on Safari for macOS, but the good news is you can put it right in Preferences.

Without the setting turned on, the tab key only cycles through the text inputs (`<textarea>` and `<input type="text">`, including other text input types, like `password`, `email`, `search`, etc.) and `<select>`s on a page. So:

- no checkboxes
- no radio buttons
- no buttons (including form submit buttons)
- no links

Interacting with links and *all* form inputs on a page is much more useful, and is what all the rest of the browsers let you do by default.


## How to turn it on

To turn on full keyboard navigation in Safari, go to:

1. Preferences (<kbd>⌘</kbd> + <kbd>,</kbd>)
2. Advanced (the last tab)
3. Accessibility
4. "Press tab to highlight each item on a webpage" checkbox

The way to use the keyboard once this setting is activated is covered in [Using the keyboard to navigate on macOS](/blog/using-the-keyboard-to-navigate-on-macos), so it's worth checking that out and getting used to how interact with each element.

The only thing worth mentioning in addition to that article is the way that links work. Links aren't really a thing in macOS's preferences and pop-up dialogs, but they're one of the most frequently used interactive elements on the web. They work *slightly* differently to buttons in that, when focussed, the space bar doesn't trigger them, as it does with buttons; just the return (<kbd>⏎</kbd>) key. Space bar on a web page scrolls the page down by a screen, so that's what happens when you press the space bar on a link.


## An accessibility win

Using the keyboard to navigate a web page not only helps us empathise with people who don't have the luxury of *choosing* not to use a mouse, but is a great way to test how accessible a web page is.

Those with a visual impairment or motor issue, where locating the pointer or steadying it pointer over a small target would be impossible, *need* to use a keyboard to get around.
