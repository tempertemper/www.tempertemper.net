---
title: How button groups should work for keyboard users
intro: Menubars, menus, toolbars, and tablists are part of a larger family of 'button groups'. Here's how they should behave when using the keyboard.
date: 2025-03-12
tags:
    - Accessibility
    - Development
related:
    - theres-no-such-thing-as-menubar-navigation
---

I've written about [the difference between navigation and menubars](/blog/theres-no-such-thing-as-menubar-navigation), where I mention that menubars should be few and far between on the web. But menubars are part of a larger family of 'button groups', which are fairly common.

Some members of the button group family are:

- Menubar
- Menu
- Toolbar
- Tablist

So how should button groups behave when using the keyboard?


## Some quick, oversimplified examples

Before I dig into the commonalities of button group behaviour, it helps to understand what each type of button group is and how it might be marked up.

### Menubars

[MDN Docs describes menubars](https://developer.mozilla.org/en-US/docs/Web/Accessibility/ARIA/Roles/menubar_role) as:

> a presentation of menu that usually remains visible and is usually presented horizontally

Each item usually contains its own menu; think those File/Edit/View/etc. menubars at the top of Google Docs documents. A menubar's markup might look something like:

```html
<div role="menubar" aria-label="Document menubar">
    <button role="menuitem">File</button>
    <!-- File menu goes here -->
    <button role="menuitem">Edit</button>
    <!-- Edit menu goes here -->
    <button role="menuitem">View</button>
    <!-- View menu goes here -->
    <!-- More menubar items -->
</div>
```

<i>This is an illustration and far from production code!</i> You're going to want a bunch of other things on the menu items, like:

- `aria-haspopup` to let screen reader users know if a item has a menu of its own (it probably does)
- `aria-controls` to make create a relationship between the menubar button and its menu


### Toolbars

Toolbars are similar to menubars but are usually more focused on a particular purpose, like formatting text. [MDN Docs describes toolbars](https://developer.mozilla.org/en-US/docs/Web/Accessibility/ARIA/Roles/toolbar_role) as:

> a collection of commonly used function buttons or controls represented in a compact visual form.

Here's how the markup might look:

```html
<div role="toolbar" aria-label="Text formatting">
    <button>Bold</button>
    <button>Italic</button>
    <button>Bulletted list</button>
    <button>Numbered list</button>
</div>
<!-- Markup for the text editing area -->
```

Again, this is a huge oversimplification; among other things, you're going to want:

- `aria-controls` on the toolbar to hook it up to its text editing area
- icons (SVGs, or some other kind of image) in place of the button text (one of the [few instances where icon-only buttons are probably okay to use](/blog/what-i-wish-was-in-wcag-prohibit-icon-only-buttons))
- `aria-pressed` to communicate whether a format has been applied or not
- `type="button"` on all those `<button>` elements, to stop them submitting the form when pressed


### Menus

Menus are found in all sorts of places: sometimes inside menubars and toolbars, sometimes as submenus inside other menus; sometimes on their own, like a right-click context menu or attached to Actions buttons on each item in a list. [MDN Docs describes menus](https://developer.mozilla.org/en-US/docs/Web/Accessibility/ARIA/Roles/menu_role) as:

> a type of composite widget that offers a list of choices to the user.

Here's how you might mark up a menu:

```html
<button id="actions">Actions</button>
<div role="menu" aria-labelledby="actions">
    <button role="menuitem">Move up</button>
    <button role="menuitem">Move down</button>
    <button role="menuitem">Edit</button>
    <button role="menuitem">Delete</button>
</div>
```

Menus are pretty straightforward, so all I'd add is that you'll want `aria-expanded` on the trigger button, if there is one. Oh, and if you've got submenus you're going to want `aria-level` to inform screen reader users about how deep in the menu hierarchy they are, as well as some `aria-haspopup`, `aria-controls`, of course.


### Tablist

[MDN Docs describes a tablist](https://developer.mozilla.org/en-US/docs/Web/Accessibility/ARIA/Roles/tablist_role) like this:

> The tablist role identifies the element that serves as the container for a set of tabs.

Pretty straightforward: a group of tabs. Here's some example markup:

```html
<div role="tablist" aria-label="Tabs">
    <button role="tab">Tab 1</button>
    <button role="tab">Tab 2</button>
    <button role="tab">Tab 3</button>
    <!-- The rest of the tabs -->
</div>
<!-- Contents of the tab that's currently active -->
```

Again, that's oversimplified, so in your working code you'll be reaching for:

- `aria-controls` on each tab to match the `id` of its tab panel
- `aria-selected` to tell screen reader users whether or not the content for the tab they're on is currently in view
- the `hidden` attribute on the currently unselected tab panels


## Keyboard behaviour

Now you know what a button group is, it's important they all work in the same way using the keyboard; especially as some can be contained within others, like menus inside menubars and toolbars. Buckle up as this gets complicated!

### A single tab stop for keyboard users

Once a keyboard user has tabbed onto the button group, they would then move from button to button using the arrow keys, rather than further tab presses. Another press of the <kbd>⇥</kbd> (tab) key should take focus off the button group and onto the next interactive element in the document.

So on top of the extra markup I mention after each example, you're also going to want `tabindex` attributes on each button. JavaScript should listen for arrow key presses and clicks to shift both the `0`-value tabindex and the focus (via the `focus()` method) around, while ensuring the non-focused buttons have `tabindex="-1"`.

Worth mentioning too that when tabbing onto a button group when no button has been selected, the first item should get focus. If a button has been selected, for example a tab in a tablist, focus goes directly to it.

### Arrow keys to get around

Speaking of key presses, once a button group has focus the arrow keys are the way to get around.

In horizontally laid-out groups the <kbd>→</kbd> (right) and <kbd>←</kbd> (left) keys move between the buttons; in vertically laid-out button groups it's <kbd>↓</kbd> (down) and <kbd>↑</kbd> (up).

Of course these can be used in combination, for example a menubar where <kbd>→</kbd> and <kbd>←</kbd> move between items, and <kbd>↓</kbd> and <kbd>↑</kbd> move focus up and down one of its menus.

If each menu in a menubar or toolbar doesn't open automatically on focus, pressing <kbd>↓</kbd> should open the menu and move focus to its first item. <kbd>⏎</kbd> (Return) or <kbd>Space</kbd> should do the same. <kbd>↑</kbd> usually places focus on the *last* item in the menu.

Whether <kbd>↓</kbd> when on the last menu item circles focus back up to the first or just stays put is up to you. I'd probably go with the last item being a dead-end to avoid risk of any disorientation. Ditto for horizontally laid-out button groups: <kbd>→</kbd> when on the last item (or <kbd>←</kbd> when on the first) could move focus to the first (or the last), but I wouldn't do that.

When a menu item has a submenu, it should be opened with <kbd>→</kbd> (or <kbd>⏎</kbd> or <kbd>Space</kbd>) and focus should go to the submenu's first item. <kbd>←</kbd> when in a submenu should close it and put focus back on it's parent button.

### Bells and whistles

You could even add extra keyboard shortcuts, for example <kbd>Home</kbd> and <kbd>End</kbd> could move focus to the first and last items in a menu. <kbd>⌥</kbd> (Option) + <kbd>↑</kbd>/<kbd>↓</kbd> also does that on macOS.

Here's another one that you'd find on macOS: <kbd>⌘</kbd> (Command) + <kbd>↓</kbd>/<kbd>↑</kbd> jumps from one section within a menu to the next.

In a menubar menu, and on a menu item without submenu, <kbd>→</kbd> could move focus to the next menu in the menubar. And <kbd>←</kbd> would do the same in the opposite direction.

### Escape

Menubars, toolbars, and tablists are always on view, but menus are normally hidden until triggered; the <kbd>Esc</kbd> key should close an open menu and put focus:

- back on the menu's parent button if triggered by a button on-page, or as part of a toolbar
- where the cursor is in the document's editing area, if part of a menubar


## Screen reader users

Screen reader users will first hear the role of the button group, which will tell them that the button group style interaction pattern is required, rather than their normal screen reader navigation keys.

### Orientation

So that screen reader users know which arrow keys to press (<kbd>→</kbd>/<kbd>←</kbd> or <kbd>↓</kbd>/<kbd>↑</kbd>) You could use `aria-orientation` to explicitly set the horizontal/vertical orientation of the button group, but this is:

- Not all that well supported across screen readers
- Probably implicit in the button group type anyway (menubars, toolbars, and tabs are usually horizontal, menus normally vertical)


## Complex

As you can see, button groups are fiddly components, requiring an awful lot of JavaScript to drive the various keypresses. But there's some good news:

- Menubars are almost never what you need
- Toolbars aren't all that uncommon
- Menus are fairly low complexity, especially when kept to one level
- Tablists can (and often should) be avoided by using alternate designs, but at least they're the simplest form of button group if you need to use one

So for when you do need to use a button group, now you know how they should behave using the keyboard.
