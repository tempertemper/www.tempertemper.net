---
title: Screen reader users and the tab key
intro: People who use a screen reader on a laptop/desktop generally use the keyboard, but that doesn't mean they use it like a keyboard-only user.
date: 2023-12-21
tags:
    - Accessibility
related:
    - getting-started-with-voiceover-on-macos
---

Screen reader users who use a laptop or desktop computer generally (though [not always](/blog/voiceovers-trackpad-commander-on-mac)) use their keyboard to control their screen reader software. But that doesn't mean they use the keyboard like a keyboard-only user.


## Keyboard-only users

A keyboard-only user gets around an interface with only a handful of keys:

- The up and down arrow keys to scroll up and down the page
- <kbd>⇥</kbd> (Tab) and <kbd>⇧</kbd> (Shift) + <kbd>⇥</kbd> to move focus from one interactive element (links, buttons, form fields) to the next
- <kbd>⏎</kbd> (Return), <kbd>Space</kbd>, and the arrow keys to interact with elements when they have focus
- One or two other keys, such as <kbd>Esc</kbd> which should close a modal or popover
- They'll also type freely into text-based form fields

Keyboard-only users only need to *interact* with the content on the screen, and we can assume that they can *see* non-interactive content like headings and paragraph text.


## Screen reader users

With screen reader users, there's [no guarantee that they can see the contents of the screen](https://webaim.org/projects/screenreadersurvey9/#disabilitytypes), so they'll use their screen reader to access to *any* content on the screen, not just interactive stuff like buttons, links, and so on.

If a screen reader user were to use <kbd>⇥</kbd> to jump from one interactive element to the next they'd miss important content, so they'd use their screen reader software's navigation shortcut keys to get around instead.

They'd move from one chunk of content to the next, jump from heading to heading to get a feel for the content on the page, skip straight to a particular page landmark like the footer; that kind of thing. Other than filling in form content, they'd be unlikely to use the same keys to negotiate an interface as a keyboard-only user.

<i>Note: That's not to say that screen reader users will *never* use the tab key; more that screen reader users will only use the it if they already know exactly what's on the screen.</i>


## Manual testing

This is one to bear in mind if you're doing any manual accessibility testing. Using the keyboard and using a screen reader should be treated separately:

1. First test using the keyboard like a keyboard-only user
2. Then test with a screen reader using its build-in key commands
