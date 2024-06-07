---
title: Getting started with VoiceOver on macOS
intro: |
    Understanding how screen readers work is important for anyone who works on digital products. Here's how to get started with VoiceOver on macOS.
date: 2021-01-20
updated: 2024-06-07
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

Page contents:

<nav aria-label="Page contents">

1. [Open Safari](#open-safari)
2. [Configure VoiceOver](#configure-voiceover)
3. [Activate VoiceOver](#activate-voiceover)
4. [The VoiceOver modifier](#the-voiceover-modifier)
5. [VoiceOver's 'modes'](#voiceovers-modes)
6. [Navigation commands to get started](#navigation-commands-to-get-started)
7. [Interacting with the web content](#interacting-with-the-web-content)

</nav>

## Open Safari

If you don't use Safari, the temptation might be to use your preferred browser; I urge you not to do this.

While [Chrome shares a common root with Safari](https://arstechnica.com/information-technology/2013/04/google-going-its-own-way-forking-webkit-rendering-engine/), and it's *usable* with VoiceOver, I've found some quirky behaviour that isn't there in Safari. Opera, Edge, Brave, Vivaldi and all the rest [piggy back on the Chromium project](https://blogs.windows.com/windowsexperience/2018/12/06/microsoft-edge-making-the-web-better-through-more-open-source-collaboration/), so they suffer from the same downsides as Chrome.

Firefox used to be impossible to use with VoiceOver, but [Mozilla began to change that](https://blog.mozilla.org/accessibility/proper-voiceover-support-coming-soon-to-firefox-on-macos/). Problem is, it's not perfect and is unlikely to improve any time soon, since Mozilla now have [fewer people working on Firefox](https://blog.mozilla.org/blog/2020/08/11/changing-world-changing-mozilla/).

So if you're looking to experience the same thing as almost all *actual* VoiceOver users, Safari is what you should test with.


## Configure VoiceOver

For the most part, you're going to want to use VoiceOver with its default settings, but there are a couple of things that will make your life a bit easier.

### Turn off extra instructions

VoiceOver gives you information about the thing you have moved its cursor onto, for example:

> Visited, link, Getting started with VoiceOver on macOS

By default, it pauses slightly then goes on to give you instructions on how to interact with the element:

> You are currently on a link. To click this link, press Control-Option-Space

This might be useful the first time you hear it, but after that it's just unnecessary noise. This is more when you're focused on a non-interactive element like a heading, where it says:

> Heading level 1, Getting started with VoiceOver on macOS

Then the redundant:

> You are currently on a heading level 1

To turn the extra hints off open VoiceOver Utility → Verbosity → Hints → de-check Speak instructions for using the item in the VoiceOver cursor.

### Prevent automatic reading

By default, when you land on a new page VoiceOver starts reading the page until you stop it by moving the cursor. This can get annoying very quickly. To turn it off go to the VoiceOver Utility app → Web → General → de-check Automatically speak the web page.


## Activate VoiceOver

Once you've got VoiceOver configured you need to turn it on. The default keyboard shortcut is <kbd>⌘</kbd> (Command) + <kbd>F5</kbd>, but on some Macs <kbd>F5</kbd> can be a bit tricky:

- If your keyboard doesn't have a Touch Bar, you need to use the <kbd>fn</kbd> (Function) key too: <kbd>⌘</kbd> + <kbd>fn</kbd> + <kbd>F5</kbd>
- If your keyboard does have a Touch Bar, getting access to the Function keys might be more difficult. In System Preferences → Keyboard → Keyboard, you need to ensure either 'Touch Bar shows' or 'Press and hold Fn key to' (or 'Press Fn key to' if you're on macOS Catalina or earlier) is set to 'F1, F2, etc. keys'. If this configuration doesn't work for you (it doesn't for me), it's probably best to remap the shortcut to activate VoiceOver.

### Remapping the VoiceOver shortcut

To remap the VoiceOver shortcut, head to System Preferences → Keyboard → Shortcuts → Accessibility → Turn VoiceOver on or off.  I change the shortcut to <kbd>⌘</kbd> + <kbd>§</kbd>.

Alternatively, you can forget the keyboard shortcut and navigate to System Preferences → Accessibility → VoiceOver → Enable VoiceOver any time you want to use it.

The same method you used to turn VoiceOver on will turn it off.


## The VoiceOver modifier

[Keyboard navigation](/blog/how-to-use-the-keyboard-to-navigate-on-safari) works as normal when VoiceOver is turned on, so tabbing will jump from one interactive element (links, buttons, form inputs) to the next, and so on. But users that can't see the screen need more than just the keyboard basics to read and navigate web content, which is where the VoiceOver modifier (VO) comes in.

The VoiceOver modifier is two keys: <kbd>⌃</kbd> (Control) + <kbd>⌥</kbd> (Option). By itself, VO doesn't do anything, but if you combine it with another key or keys you access the VoiceOver commands.

<i>If holding down two keys feels a bit fiddly, you can change the VoiceOver key to <kbd>⇪</kbd> (Caps Lock) instead of (or in addition to) <kbd>⌃</kbd> + <kbd>⌥</kbd> in VoiceOver Utility → General → Keys to use as the VoiceOver modifier.</i>


## VoiceOver's 'modes'

As with most screen readers, VoiceOver has two distinct modes:

- Navigation
- Interaction

Navigation is reading through the text on a webpage, skim-reading the contents, and following links from page to page. Interaction is generally more form-filling.

As a general rule, navigation requires the VoiceOver modifier and interaction doesn't.


## Navigation commands to get started

The good news is you can get around a web page with only a handful of commands:

<dl>
    <dt>VO + <kbd>→</kbd> (right arrow key)</dt>
        <dd>Go to next thing (heading, paragraph, list item, etc.)</dd>
    <dt>VO + <kbd>←</kbd> (left arrow key)</dt>
        <dd>Go to previous thing (heading, paragraph, list item, etc.)</dd>
    <dt>VO + <kbd>Space</kbd></dt>
        <dd>Follow a link or press a button.</dd>
    <dt>VO + <kbd>⌘</kbd> + <kbd>h</kbd></dt>
        <dd>Go to next heading</dd>
    <dt>VO + <kbd>⌘</kbd> + <kbd>⇧</kbd> + <kbd>h</kbd></dt>
        <dd>Go to previous heading</dd>
</dl>

<i>The overwhelming majority of screen reader users [use headings to find information](https://webaim.org/projects/screenreadersurvey10/#finding) on a page.</i>

### Getting into Safari's web content

Since VoiceOver is a way to use your *whole operating system*, when you open Safari it focusses on the browser as a whole; you can navigate the toolbar, check your bookmarks, cycle through your tabs, or view the web content (VO + <kbd>→</kbd> or <kbd>←</kbd> to move between them). But once you're the thing you want to use, you have to enter it:

<dl>
    <dt>VO + <kbd>⇧</kbd> (shift) + <kbd>↓</kbd> (down arrow key)</dt>
        <dd>Enter an 'area'</dd>
    <dt>VO + <kbd>⇧</kbd> + <kbd>↑</kbd> (up arrow key)</dt>
        <dd>Exit an 'area'</dd>
</dl>

So to dig into the web content, so you need to put VoiceOver's focus on the "web content" and hit VO + <kbd>⇧</kbd> + <kbd>↓</kbd>; you can then navigate through the webpage.

If you want to come out of the web content and back up to that top level to, for example, enter a new web address, use VO + <kbd>⇧</kbd> + <kbd>↑</kbd>, move your cursor to the "toolbar", enter the toolbar area and move your cursor to the address search box.


## Interacting with the web content

A screen reader user is [likely to move from one form-field to the next using VO + <kbd>→</kbd>](/blog/screen-reader-users-and-the-tab-key) but when filling out the form they fall back on keyboard-only behaviour, for example:

- <kbd>Space</kbd> to press a button or check a checkbox
- Numbers, letters, and other characters to type in a `<textarea>` or text `<input>`
- Arrow keys to move the selection within a `<select>` or group of radio buttons
- <kbd>⏎</kbd> from any field to submit the form

Hopefully that makes the getting used to a screen reader like VoiceOver a bit more straightforward. With all of that information it just requires a bit of time to get used to using the software; it's a great way to build empathy with users who use the web in a different way to you!
