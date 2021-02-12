---
title: Which way is that arrow pointing!?
intro: |
    To show/hide content you'll probably use an arrow on the toggle to indicate that content will be revealed. Sounds simple enough, doesn't it?
date: 2019-11-02
tags:
    - Design
---

When designing a user interface, it's not uncommon to use some kind of toggle to reveal/hide content. There are all sorts of situations you'd need to do this:

- Drop-down navigation
- Some content hidden in a `<details>` element with a `<summary>` as the toggle
- A list of `<option>`s in a `<select>` element
- A list of `<option>`s in a `<datalist>` element

There are solid arguments against using each of these things (that probably warrant their own article), but if you *have to* use one, you're probably going to need to distinguish the toggle from a normal link or button somehow. This is often done with an arrow indicator of some kind, placed inline, before or after the clickable text (probably better before).


## Which way should the arrow point?

The first problem is which way or ways the arrow should be pointing in the closed and open states.

### Right/down

A common approach is for the arrow to point **right when the content is closed** and **down when the content is open**.

The right-pointing arrow indicates there is something else to progress to; the downwards arrow leads the eye towards the newly revealed content. This is how the `<details>` and `<summary>` elements work [before any extra styling](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/details).

A downside to this method is that the user might expect to be taken to a new page -- links that take you back a page often have a left-pointing arrow, so they might reasonably assume that progressing to the next step in a process might have a right-pointing arrow.

### Down/up

Another pattern would be to point **down when the content is closed**, switching to pointing **up when the content is open**.

The down arrow hopefully indicates there is content that will be revealed underneath; the up arrow tells the user they can collapse the content back up into its closed state.

A problem here is that when the arrow switches to point upwards, the user's eye may well be led back up the page, rather than down to the location of the revealed content.

### No change

Of course, you could go with one of the above for the initial closed state without changing the arrow direction at all for the open state, as the change in direction in and of itself could prove disorienting for some users.


## What type of arrow is best?

So it's not just which way the arrow's pointing that can get a little sticky. The *type of arrow* you use can make a big difference too.

### Arrows

It's rare to see [actual arrows](https://en.wikipedia.org/wiki/Arrow_(symbol)) (&#11106;) used for this kind of thing. The 'shaft' leading to the arrowhead gives them more movement/momentum; they feel like they'll take the user somewhere else entirely, rather than reveal something in place. They're sometimes employed in pagination, 'read more' links in article lists, or to illustrate call to action buttons, and all of these clicks would take you somewhere else.

### Triangles

There can be a real problem with [equilateral triangles](https://en.wikipedia.org/wiki/Equilateral_triangle): if all sides are the same length (or very close to the same the length) it's certainly a neat looking arrow (&#9654;), but where is the arrow pointing? As left-to-right readers, we follow the arrow in that same direction: is your downward-pointing arrow (&#9660;) actually pointing up and right? Anything with a level of ambiguity needs thought, and, as Steve Krug once famously said (in [a whole book](https://books.apple.com/gb/book/dont-make-me-think-revisited/id788508912)):

> Don't make me think

More definite [isosceles triangles](https://en.wikipedia.org/wiki/Isosceles_triangle), where two sides are noticeably longer than the third (&#8883;), are much better. It's clear the arrow isn't pointing where a longer edge meets a shorter edge, so there's no pause for thought.

### Chevrons

Chevrons are a commonly used alternative to triangles. The new default `<select>` styling in [Edge uses a chevron](https://blogs.windows.com/msedgedev/2019/10/15/form-controls-microsoft-edge-chromium/) and it's clear which direction they're pointing.

The downside here is that, depending on the sharpness/flatness of the angle, the right-pointing arrow could be confused with a greater-than operator (&gt;), or a closing speech mark (&rsaquo;) by some visitors for whom English isn't their first language.


## What to do?

So if show/hide toggles are best avoided and every arrow direction and shape has downsides, what should we do? As with most things: *test*. Putting your designs in front of real users will tell you how well your arrows are understood; informing changes and reinforcing decisions.
