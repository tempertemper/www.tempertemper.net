---
title: Buttons, links, and focus
intro: Knowing when to use a button or link is important, and there's some great guidance out there. Here's another way to work out when to use which.
date: 2023-02-16
tags:
    - Design
    - Accessibility
related:
    - when-design-breaks-semantics
    - rolling-your-own-links
---

I've written about what to do when [links need to look like buttons](/blog/when-design-breaks-semantics) and [buttons need to look like links](/blog/if-you-need-a-link-dont-use-a-button), but it's sometimes tricky to know whether an element should be a button or a link in the first place.

Eric Eggert's [excellent piece on buttons versus links](https://yatil.net/blog/buttons-vs-links) says that a link:

> changes what the URL in the browser points to and then displays that website or file. If the browser can’t display the file, it gets downloaded.

And that buttons:

> perform an action.

I've referenced this in countless conversations with designers, and it makes things clearer for them.

There's another difference I've noticed in button/link behaviour which I also use to help the people I work with know which element to use: focus behaviour.

When a keyboard user follows a link, their focus should be taken to the new place; when a keyboard user presses a button, focus should remain on that button.


## A couple of edge cases

This seems pretty straightforward until you start to think about links that take you to a different part of the page and buttons that open a modal. Each of these cases deserve a wee bit more attention.

### Jump/anchor links

Following a link will take your focus and drop it at the very start of a new page, but what about links to a part of the *same page*?

When a link's `src` attribute points to the `id` of an element on the same page, focus is placed on the target element. This fits with the rule, since focus is taken off the element that was activated (a link) and moved somewhere new.

### Buttons and modals

When a keyboard user presses a button that triggers a modal, focus can't remain on the button as:

- The user expects their focus to move to the modal so that they can interact with it
- The page the button sits on is now behind the modal and should not be interactive until the modal is closes

So we need to move focus, which seems very similar to the jump/anchor links scenario. But the difference is that focus is returned to that original button once the modal is closed, so focus essentially stays in the same place once a task is completed.

Admittedly, it gets a bit complicated if the button you pressed to open the modal is not there any more. Here's an example: you press a button to remove an item from a list, and a modal pops up to ask you if you are sure you want to do this; if you confirm you *are* happy to proceed the list item, and therefore button you pressed won't be there when the modal is closed.

In this case, focus might be placed very close to the item you removed, or it might be returned to the start of the page. This is something for the designer to decide and should be validated via testing with keyboard-only users.

I guess what fits here is that your focus isn't moved somewhere else and you're on your own, like you would be with a link, where it's up to you to navigate back to where you came from; instead your focus position is carefully considered and, hopefully, your expectations are met.

It's not always straightforward, and sometimes warrants some discussion, but I've found focus placement another good way to help designers understand whether to use a link or button in their designs.
