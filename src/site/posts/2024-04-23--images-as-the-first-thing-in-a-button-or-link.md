---
title: Images as the first thing in a button or link
intro: If the text of an interactive element like a button or link is preceded with an accessible image, we've probably got an accessibility problem.
date: 2024-04-23
tags:
    - Accessibility
    - HTML
---

An accessibility problem I encounter regularly is where the text of an interactive element like a button or link is preceded with an image, and the image is accessible.

Here's an example of some problematic code:

```html
<button>
    <img src="bin-icon.svg alt="Bin" />
    Delete
</button>
```

<i>Note: the image could be added in all sorts of ways, from directly adding the `<svg>` code to using an icon font with CSS, but an `<img>` element makes it nice and clear.</i>

Seems innocuous enough, but there's a disconnect between the visible label and the underlying accessible name.


## Label versus name

Visually, there's a button with a bin icon then the word 'Delete'. Since both things in the button are accessible, a screen reader user would hear something like:

> Bin delete, button

There are two issues here:

1. Do screen reader users really need to hear "Bin"? Their experience is pretty noisy at the best of times, so streamlining our UI is usually appreciated
2. A hurdle has been placed in front of speech recognition users; let's explore that some more…


## Impeding speech recognition software users

In order to activate the button in our example, speech recognition software users need to say the name of the icon as part of the command, since it's accessible:

> Click bin delete

The first problem is that [there's no way to know for sure what the accessible name of the icon is](/blog/what-i-wish-was-in-wcag-prohibit-icon-only-buttons#speech-recognition-software).

- "Click trash delete"? Nope…
- "Click remove delete"? Not that either…
- "Click delete delete"? Clutching at straws now…

But most users will probably start by assuming the icon is decorative, and say "Click delete". This won't work either since you need to say the name of the icon.

People are likely to give up after the first or second attempt to press the button, and use their software's command to attach numbers to every interactive item on the page; they'll then just say "Click ten" (or whatever number is given to the delete button).


## How to fix it

The icon is getting in the way for speech recognition software users and adding very little other than extra noise for screen reader users. You could argue that it doesn't really add much visually either, and you'd be right except for the fact that some people, for example dyslexic people, benefit from a visual 'hook' that saves them having to read the text.

So we want to:

- Keep the icon as a visual hook
- Prevent it being picked up by screen reader software
- Get it out of the way for speech recognition software users

Easy! We just leave the icon visually and hide it accessibly:

```html
<button>
    <img src="bin-icon.svg alt="" />
    Delete
</button>
```

<i>Note: You could also use something like `aria-hidden="true"` or `role="presentation"` to stop the icon being accessible.</i>

That way screen reader users hear "Delete, button", speech recognition software users will hopefully trigger it on the first attempt with "Click delete", and we've got a text-based label together with a nice icon as a visual aid.
