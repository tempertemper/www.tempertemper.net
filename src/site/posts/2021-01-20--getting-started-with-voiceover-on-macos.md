---
title: Getting started with VoiceOver on macOS
intro: |
    Understanding how screen readers work is important for anyone who works on digital products. Here's how to get started with VoiceOver on macOS.
date: 2021-01-20
tags:
    - Accessibility
    - Apple
summaryImage: voiceover.png
summaryImageAlt: The VoiceOver caption panel, saying "VoiceOver on Safari, Getting started on VoiceOver for macOS, window, Getting started with VoiceOver on macOS, web content has keyboard focus".
---

An understanding of how screen readers work is an important part of the journey for anyone who works on digital products. It gives us an appreciation for the many ways people use the web, and there are two solid things you can do:

1. Observe people who use screen readers using the web
2. Use a screen reader yourself

The first involves some research and planning, which can be huge barriers, unless you work for an organisation that has access to a panel of volunteers, or know someone personally who uses a screen reader and doesn't mind giving you a demo.

The second might seem daunting, but, thinking back, it only took me around an hour of fumbling to get to grips with VoiceOver on my Mac.


## Open Safari

If you don't use Safari, the temptation might be to use your preferred browser; I urge you not to do this.

While [Chrome shares a common root with Safari](https://arstechnica.com/information-technology/2013/04/google-going-its-own-way-forking-webkit-rendering-engine/), and it's *usable* with VoiceOver, I've found some quirky behaviour that isn't there in Safari. Opera, Edge, Brave, Vivaldi and all the rest [piggy back on the Chromium project](https://blogs.windows.com/windowsexperience/2018/12/06/microsoft-edge-making-the-web-better-through-more-open-source-collaboration/), so they suffer from the same downsides as Chrome.

Firefox used to be impossible to use with VoiceOver, but [Mozilla began to change that](https://blog.mozilla.org/accessibility/proper-voiceover-support-coming-soon-to-firefox-on-macos/). Problem is, it's not perfect and is unlikely to improve any time soon, since Mozilla now have [fewer people working on Firefox](https://blog.mozilla.org/blog/2020/08/11/changing-world-changing-mozilla/).

So if you're looking to experience the same thing as almost all *actual* VoiceOver users, Safari is what you should test with.

### Make the tab key useful

[Highlighting *all* controls on a web page](/blog/how-to-use-the-keyboard-to-navigate-on-safari) is really useful. It isn't something every user will have switched on, but it makes a page more usable, and can uncover some extra issues if you're a software tester.


## Activate VoiceOver

FIrst you need to turn VoiceOver on. The default keyboard shortcut is <kbd>⌘</kbd> (Command) + <kbd>F5</kbd>, but, unfortunately, <kbd>F5</kbd> can be a bit tricky:

- If your keyboard doesn't have a Touch Bar, you need to use the <kbd>fn</kbd> (Function) key too: <kbd>⌘</kbd> + <kbd>fn</kbd> + <kbd>F5</kbd>
- If your keyboard does have a Touch Bar, getting access to the Function keys might be more difficult. In System Preferences → Keyboard → Keyboard, you need to ensure either 'Touch Bar shows' or 'Press and hold Fn key to' (or 'Press Fn key to' if you're on macOS Catalina or earlier) is set to 'F1, F2, etc. keys'. If this configuration doesn't work for you (it doesn't for me), it's probably best to remap the shortcut to activate VoiceOver.

### Remapping the VoiceOver modifier

To remap the VoiceOver shortcut, head to System Preferences → Keyboard → Shortcuts → Accessibility → Turn VoiceOver on or off.  I change the shortcut to <kbd>⌘</kbd> + <kbd>§</kbd>.

Alternatively, you can forget the keyboard shortcut and navigate to System Preferences → Accessibility → VoiceOver → Enable VoiceOver any time you want to use it.

The same method you used to turn VoiceOver on will turn it off.


## The VoiceOver modifier

[Keyboard navigation](/blog/how-to-use-the-keyboard-to-navigate-on-safari) works as normal when VoiceOver is turned on, so tabbing will jump from one interactive element (links, buttons, form inputs) to the next, and so on. But users that can't see the screen need more than just the keyboard basics to read and navigate web content, which is where the VoiceOver modifier (VO) comes in.

The VoiceOver modifier is two keys: <kbd>⌃</kbd> (Control) + <kbd>⌥</kbd> (Option). By itself, VO doesn't do anything, but if you combine it with another key or keys you access the VoiceOver commands.

<i>It's worth mentioning you can change the VoiceOver key to <kbd>⇪</kbd> (Caps Lock) instead of (or in addition to) <kbd>⌃</kbd> + <kbd>⌥</kbd> in VoiceOver Utility → General → Keys to use as the VoiceOver modifier.</i>


## Commands to get started

The good news is you can get around a web page with only a handful of commands:

<dl>
    <dt>VO + <kbd>→</kbd> (the right arrow key)</dt>
        <dd>Go to next thing (heading, paragraph, list item, etc.)</dd>
    <dt>VO + <kbd>←</kbd> (the left arrow key)</dt>
        <dd>Go to previous thing (heading, paragraph, list item, etc.)</dd>
    <dt>VO + <kbd>⇧</kbd> (shift) + <kbd>↓</kbd> (the down arrow key)</dt>
        <dd>Go into area</dd>
    <dt>VO + <kbd>⇧</kbd> + <kbd>↑</kbd> (the up arrow key)</dt>
        <dd>Go out of area</dd>
</dl>


## Getting into Safari's web content

Since VoiceOver is a way to use your *whole operating system*, when you open Safari it focusses on the browser as a whole; you can navigate the toolbar, check your bookmarks, cycle through your tabs, or view the web content (VO + <kbd>→</kbd> or <kbd>←</kbd> to move between them).

You're looking to dig into the web content, so you need to put VoiceOver's focus on the web content (the focus will be here by default when you open or switch to Safari) and hit VO + <kbd>⇧</kbd> + <kbd>↓</kbd>.

If you want to come out of the web content and back up to that top level, use VO + <kbd>⇧</kbd> + <kbd>↑</kbd> and choose a different area.


## Interacting with the web content

Once you're in the web content, read through the page with VO + <kbd>→</kbd> or <kbd>←</kbd>. You can fall back on your normal keyboard behaviour for everything else:

- <kbd>⏎</kbd> (Return) to follow a link
- <kbd>⏎</kbd> or <kbd>Space</kbd> to press a button
- Numbers, letters and other characters to type in a `<textarea>` or text `<input>`
- Arrow keys and <kbd>Space</kbd> to choose from a group of radio buttons
- <kbd>⇥</kbd> (Tab) and <kbd>Space</kbd> to choose options from a group of checkboxes
- Arrow keys and <kbd>⏎</kbd> to navigate and choose from form controls like `<select>`

You get the idea.

Getting used to a screen reader like VoiceOver is really pretty straightforward, with a little effort, and, most importantly, it's a great way to build empathy with users who use the web in a different way to you!
