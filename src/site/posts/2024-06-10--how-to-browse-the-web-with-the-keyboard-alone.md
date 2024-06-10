---
title: How to browse the web with the keyboard alone
intro: Some people use the keyboard to get around their computer. Knowing how to do this is important for accessibility testing and to inform design.
date: 2024-06-10
tags:
    - Accessibility
summaryImage: keyboard.png
summaryImageAlt: The keyboard icon from macOS’s Settings app; a very simply drawn white keyboard over a grey ‘squircle’ app icon background.
related: getting-started-with-voiceover-on-macos
---

Some people use the keyboard to get around their computer, whether their laptop or mobile device. Knowing how to do this is important for both accessibility testing and understanding how to design and develop with keyboard-only users in mind.

The good news in that there are very few keys you need to use:

- <kbd>⇥</kbd> (Tab key)
- <kbd>Space</kbd>
- <kbd>⏎</kbd> (Return/Enter)
- <kbd>Esc</kbd>
- <kbd>↑</kbd> <kbd>↓</kbd> <kbd>←</kbd> <kbd>→</kbd> (the up, down, left, and right arrow keys)
- <kbd>⇧</kbd> (Shift)

<i>Note: In this article I'm going to focus on using the keyboard to get around a web page, rather than the whole operating system (which is a bit more complex and can be activated via Settings → Accessibility → Motor → Keyboard → toggle Full keyboard access).</i>

Page contents:

<nav aria-label="Page contents">

1. [Scrolling](#scrolling)
2. [The tab key](#the-tab-key)
3. [Following links and pressing buttons](#following-links-and-pressing-buttons)
4. [Interacting with forms](#interacting-with-forms)
5. [Moving around items in a group](#moving-around-items-in-a-group)

</nav>


## Scrolling

The assumption is that keyboard-only user can see the screen, so how would someone move up and down a web page to read the content?

<kbd>Space</kbd> scrolls the viewport down by about a viewport's height at a time until it reaches the bottom, and <kbd>⇧</kbd> + <kbd>Space</kbd> scrolls it back up; again by a viewport's height until it reaches the top.

In some browsers the <kbd>↓</kbd> will nudge the page down slightly, and <kbd>↑</kbd> will nudge it up again.

You can also jump to the bottom of the page with <kbd>⌘</kbd> (Command) + <kbd>↓</kbd> on macOS and the <kbd>End</kbd> key on Windows. The opposite is also true: you can scroll back to the top of the page with <kbd>⌘</kbd> + <kbd>↑</kbd> on macOS and <kbd>Home</kbd> on Windows.


## The tab key

As a keyboard-only user the tab key is your friend. It moves focus from one interactive element to another:

- Links
- Buttons
- Form fields
- Other interactive components, such as [accessible tables](/blog/accessible-responsive-tables)

<kbd>⇧</kbd> + <kbd>⇥</kbd> moves backwards to the previous interactive element.

<i>Note: by default Safari on macOS only tabs to form fields; you have to [activate tabbing to all interactive components](/blog/how-to-use-the-keyboard-to-navigate-on-safari).</i>


Once you reach the last interactive element in a web page, pressing <kbd>⇥</kbd> will circle your focus back to the top of the browser. <kbd>⇧</kbd> + <kbd>⇥</kbd> from the first interactive item on a page brings focus to the items in the browser's chrome (tabs, bookmarks, address bar, etc.).


## Following links and pressing buttons

When you are focused on a link, pressing <kbd>⏎</kbd> follows it; the same with a button.

The <kbd>Space</kbd> key can also be used to press a button, although this isn't the case with links. Pressing space when focus is on a link scrolls the page down rather than following the link.


## Interacting with forms

Things get a wee bit more complicated when filling a form in.

Text-based form fields like text `<input>`s and `<textarea>`s are pretty straightforward: tab onto the field, type a value, then tab onto the next field.

Checkboxes are even simpler: check/de-check using the <kbd>Space</kbd> key.

### Radio buttons

Checkboxes can exist individually or as a group (a `<fieldset>`), but the keyboard behaviour doesn't change one way or the other. Radios only make sense as a group of two or more and behave differently to a group of checkboxes. Instead of being independent controls (in other words, tab to a checkbox, check it if appropriate, tab to the next, and so on) radio buttons work as a unified whole; here's how they work:

- Tab onto the radio group; this highlights the first radio item but doesn't select it
- Press <kbd>Space</kbd> to select that first highlighted-but-not-selected radio button
- Press <kbd>↓</kbd> or <kbd>→</kbd> to move to and select the next radio button
- Press <kbd>↑</kbd> or <kbd>←</kbd> to move to and select the previous radio button
- <kbd>⇥</kbd> takes you out of the radio group and onto the next interactive element on the page

Tabbing backwards onto a radio group where no items have been selected highlights the last item (without selecting it); tabbing or <kbd>⇧</kbd>-tabbing onto a radio group when an item has already been selected places focus on that item.

### Select dropdowns

`<select>`s behave differently on macOS and Windows. Once you've tabbed onto the parent 'button', open the menu by pressing <kbd>Space</kbd> and close it with <kbd>Esc</kbd>. Beyond that, things get interesting:

#### macOS
On macOS you can also use <kbd>↑</kbd> or <kbd>↓</kbd> to open the menu. If an option hasn't already been selected it will focus on the top option, otherwise it'll highlight the previously selected option.

Further presses of the <kbd>↑</kbd> or <kbd>↓</kbd> keys move focus up and down the options until you hit the last (or come back up to the first) option, at which point it will stop.

Moving from option to option only *highlights* that option, it doesn't *select* it. To select the option you must press <kbd>Space</kbd> or <kbd>⏎</kbd>, at which point the menu closes and the value of the parent button changes to the selected option. If you re-open the menu the selected option will have a tick next to it.

#### Windows
On Windows, instead of pressing <kbd>Space</kbd>, you can use any of the arrow keys to change an option:

- <kbd>↓</kbd> and <kbd>→</kbd> move to the next option until you hit the last one, at which point it'll stop
- <kbd>↑</kbd> and <kbd>←</kbd> move to the previous option until you hit the first one, where it'll stop

When doing this, the menu remains closed and the label of the button changes. You can also open the menu first so that you can see the whole list of options; just press <kbd>Space</kbd> before using the arrow keys.

The big thing here is that highlighting an option *also selects it*; these are not separate steps like they are on macOS. This means that <kbd>Space</kbd> and <kbd>⏎</kbd> only have the effect of closing the menu, the same thing as pressing the <kbd>Esc</kbd> key.


## Moving around items in a group

You'll encounter other 'groups' of things, for example:

- tabs in a tab panel
- buttons in a button toggle
- menu items in a menubar

None of these patterns are native HTML so there's no 'default' behaviour to refer to. There is some commonality though: like a radio group, the group of tabs/buttons/menu items should be a single tab stop, and the arrow keys are the mechanism to move focus from one tab/button/menu item to the next. Each item is essentially a button, so the expected keyboard behaviour would be that each button is pressed in the normal way (<kbd>⏎</kbd> or <kbd>Space</kbd>).







