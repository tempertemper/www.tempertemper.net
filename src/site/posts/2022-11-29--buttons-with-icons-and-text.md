---
title: Buttons with icons and text
intro: We can all agree that icon-only buttons are a bad idea, but how do we provide the most accessible experience when we pair an icon with visible text?
date: 2022-11-29
tags:
    - Accessibility
    - Development
---

Despite what the Web Content Accessibility Guidelines (WCAG) say, [icon-only buttons are not accessible](/blog/what-i-wish-was-in-wcag-prohibit-icon-only-buttons). Adding a visible text label makes the button's purpose obvious but, oftentimes, keeping the icon alongside the text is still the right thing to do for our users.

If we pair the visible text in our buttons with an icon, the icon is *pure decoration* since the visible text is doing all of the work. This means we can safely hide the icon from assistive technology, knowing that the button's visible label is also the accessible name. If we don't, we can cause problems for some users.


## Problems for screen reader software

If the icon is has an accessible name, it'll be read out by screen reader software, which adds unnecessary noise for screen reader users.

Worse still, if the accessible name is an obscure unicode character (which is often the case when using icon fonts) it could be read aloud, causing confusion. Some screen readers like VoiceOver also display these characters visually as a question mark in a rectangle (like this: `⍰`) which, again, can be confusing for sighted screen reader users.


## Problems for speech recognition software

Having an icon with an accessible name can stop people using speech recognition software in their tracks, particularly when the icon is placed *before* the visible button text.

When a speech recognition software user wants to press a button, they will say "Click", then the visible text label; the software then finds the button that matches the label they read out and presses it.

If there is an icon with an accessible name before the visible text, the user would be expected to say the name of the icon: "Click", then the icon's accessible name, then visible text label. The problems here are:

- They have no idea what the accessible name of the icon is
- The accessible name of the icon might be an unpronounceable Unicode character

Either way, they won't get a match and nothing will happen. They'll be left wondering if it was a glitch in the software or their regional accent (being Scottish, I encounter this all of the time!), and will often waste time trying again before falling back on a workaround.


## How to put things right

Icons can be added to our buttons in a whole host of ways, including:

- An `<svg>`
- The `<img>` element
- An icon font

The fix is to keep the icon visually while hiding it non-visually; an empty `alt` attribute on the `<img>` element (for example `<img src="icon.png" alt="" />`), or maybe an ARIA attribute like `aria-hidden="true"`‌ or `role="presentation"`.

However we do it, when we hide decorative icons in buttons from screen readers and other assistive technology, we remove another hurdle that disabled people have to negotiate as they use the web. We're providing a good user experience for *all*.
