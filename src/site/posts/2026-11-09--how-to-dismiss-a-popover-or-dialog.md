---
title: How to dismiss a popover or dialog
intro: There are a bunch of ways to dismiss popovers and dialogs. Here's what they are, how they work, and where you might use them.
date: 2026-11-09
tags:
    - Accessibility
    - HTML
---

I've been digging into the [new `<select>` element](/blog/fully-customise-your-select-elements) lately, and how its dropdown is dismissed got me thinking about dismissal more broadly: what are the best ways for users to close overlays like popovers and dialogs?


## Modal versus non-modal

Before looking at how we dismiss these overlays, it's worth understanding the distinction between modal and non-modal.

A <b>modal</b> overlay makes the page behind it inert, meaning it can't be interacted with until the overlay has been dismissed. The background page is often dimmed to indicate this, but not always. Keyboard focus usually stays inside the modal, looping from the last focusable item back to the first, until it's dismissed.

A <b>non-modal</b> overlay allows us to interact with the page behind it while it's still open, including scrolling up and down.

The distinction between popovers and dialogs themselves isn't always clear-cut, but I'm not going to get into that; the dismissal methods I'll describe can apply to either.


## Dismissal

Trusty old MDN Docs covers the three types of dismissal in their [`closedby` documentation](https://developer.mozilla.org/en-US/docs/Web/HTML/Reference/Elements/dialog#closedby) for `<dialog>`:

- Light dismiss
- A platform-specific user action
- A developer-specified mechanism

### Light dismiss

Going back to that MDN Docs page for a definition, it says:

> the `<dialog>` is closed when the user clicks or taps outside it.

For a modal overlay that uses light dismiss, clicking/tapping outside closes it and makes the previously inert page active again; the next click interacts with the page as normal. The traditional [`<select>` dropdown on a Mac](/blog/a-better-way-to-handle-select-behaviour#what-are-the-differences) is an example of this.

For a non-modal overlay, the page behind is already interactive, so that same click/tap dismisses the overlay but could also interact with something on the page. A good example is the traditional [`<select>` dropdown on Windows](/blog/a-better-way-to-handle-select-behaviour#what-are-the-differences).

### A platform-specific user action

These are all the built-in dismissal methods. Our MDN docs definition for a platform-specific user action gives examples:

> such as pressing the <kbd>Esc</kbd> key on desktop platforms, or a "back" or "dismiss" gesture on mobile platforms.

The <kbd>Esc</kbd> key is the most obvious example, and doesn't need any more explanation. The 'back' gesture is, I think, referring to [Android's Back action](https://support.google.com/pixelphone/answer/6073614), and an example of a 'dismiss' gesture would be VoiceOver on iOS, where drawing a Z shape with two fingers invokes the 'scrub' [gesture](https://support.apple.com/en-gb/guide/iphone/iph3e2e2281/ios).

### A developer-specified mechanism

Not everyone knows a platform-specific action for dismissing an overlay, so we can add our own controls to make dismissal more discoverable. These are what MDN Docs calls developer-specified mechanisms:

> A … mechanism such as a `<button>` with a `click` handler that invokes `HTMLDialogElement.close()` or a `<form>` submission.

So submitting a form can be a way to dismiss a dialog, as long as we build it to work that way.

Buttons are pretty straightforward too, particularly in dialogs. You often see a close button (an 'X' or the word 'Close') in the top corner, or sometimes a 'Cancel' button alongside the main action button (which might be 'Okay', 'Confirm', or something along those lines).

Popovers are less likely to have explicit close buttons; instead, the button that opens them might act as a close control.


## Which behaviour is right?

So how should we tackle dismissing our popovers and dialogs? The browser gives us a useful starting point, so if we're building our own equivalents, following those native behaviours helps us meet users' expectations, much like [following the browser's behaviour when building a custom select](/blog/a-better-way-to-handle-select-behaviour).

For a popover, that means:

- Non-modal
- Light dismiss
- Platform-specific dismissal
- The button that opens it will also close it

For a dialog:

- Modal (they can also be non-modal, but I rarely encounter good use cases for that)
- Not light dismiss
- Platform-specific dismissal

### I wouldn't stop there

I’d add two dialog-related recommendations:

- An explicit close or cancel button ([avoiding icon-only](/blog/what-i-wish-was-in-wcag-prohibit-icon-only-buttons))
- Light dismiss, as long as an accidental click outside won't silently discard someone's work in a half-filled form

And, moving back to popovers, if they're something menu-like, I'd copy the behaviour of `<select>` elements that use `appearance: base-select`, making sure the popover closes when keyboard focus moves out of it.

There isn't one correct way to dismiss every overlay. The methods we offer should suit the type of overlay and how it's used, but dismissal should always be intuitive, predictable, and consistent.
