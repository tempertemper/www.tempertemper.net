---
title: If you need a link, don't use a button
intro: "Links sometimes need to look like buttons, but what about the other way round? Spoiler alert: it's a terrible idea!"
date: 2023-01-23
tags:
    - Accessibility
    - Design
    - Development
---

I've written about when [links need to look like buttons](/blog/when-design-breaks-semantics), but what about when a button might need to look like a link?

Thankfully, the case for doing this is much less common. And that's just as well since it's a lot more problematic than the other way round.

<i>Note: everything in this article also applies to rolling your own links using a `<span>` element.</i>


## Semantics/role

First up, override the semantics using ARIA. Alarm bells should be going off here, as this breaks the [Second Rule of ARIA Use](https://www.w3.org/TR/using-aria/#second):

> Do not change native semantics, unless you really have to.

But if we *have to*, adding `role="link"` will let users of assistive technology like speech recognition software access if they call it out as a "link"; it'll also let screen reader users know that they've arrived on a link.


## Behaviour

Next, we need to get our `<button>` to behave like a link (`<a>`).

<i>Another note: the `<a>` element [needs an `href` attribute in order to behave properly](/blog/links-missing-href-attributes-and-over-engineered-code).</i>

### Draggable

Links are draggable, so we need to add `draggable="true"`, but we also need to make the dragged link behave correctly, so it should be able to be dropped in:

- the browser's tab bar to open the link in a new tab
- a Finder (or File Explorer on Windows) window to save a shortcut/bookmark to the page

Can this be done with JavaScript? I'm no JavaScript expert, but I wouldn't have thought so since it's stuff that happens outside the webpage and, with the latter behaviour, the browser application itself.

### Keyboard use

Unline a button, a link that has keyboard focus isn't activated when the <kbd>Space</kbd> key is pressed; only <kbd>Return</kbd>/<kbd>Enter</kbd>. Instead, <kbd>Space</kbd> scroll the page down; unsurprisingly there's some fiddliness here as the amount the page scrolls differs between operating systems:

- On Mac it scrolls the page by very slightly less than the full height of the browser viewport
- On Windows it scrolls the page about three quarters of the height of the browser viewport

To ensure the experience matches our users' expectations, we probably need to veer into 'browser sniffing' territory, which is a big red flag.


## Actions

We also need to offer the expected set of actions in our context menu; things like:

- Open in new tab
- Open in new window
- Open in private window
- Open in tab group
- Copy link
- Copy link title
- Download/save linked file
- Add link to bookmarks
- Add link to Reading List
- Share

Ideally this menu should be native, and be presented along side the default right click actions for elements that contain text (which varies from browser to browser…), like dictionary look-up, translation, and speak aloud. There are some good [tutorials on creating a custom context menu](https://itnext.io/how-to-create-a-custom-right-click-menu-with-javascript-9c368bb58724), but I don't think it can be done natively.


## Don't do it

Even if we could do it, this is one where we really shouldn't. Links are not only one of the most common HTML elements, but one of the most complex; if a button needs to be presented as a link, we should use the `<a>` element rather than fighting a losing battle using ARIA and JavaScript.
