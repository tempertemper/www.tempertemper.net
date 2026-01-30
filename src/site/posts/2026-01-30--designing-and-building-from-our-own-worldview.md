---
title: Designing and building from our own worldview
intro: Producing accessible software means continually questioning your own habits and assumptions about how people interact with it.
date: 2026-01-30
tags:
    - Accessibility
---

We tend to design and build our digital products from our own worldview, not thinking about the many other ways people might use them. This is totally natural, and it's why accessibility exists: to continually remind people who design and build that *their* experience of the world is not the *only* one.

We often end up in a position where we haven't considered accessibility, so all that functionality has to be added retrospectively which means:

- [the project cost ends up *much* higher](/blog/accessibility-by-degrees)
- [legal action](/blog/making-sense-of-accessibility-and-the-law) has been a real possibility
- [the job was only part-done](/blog/if-youre-going-to-do-a-job-do-it-properly)
- a *lot* of people will have been excluded


## That common misconception

The way I use the web and apps is pretty common:

- Touch on my phone
- Mouse or trackpad on my laptop

There's the keyboard on my laptop too, of course, that I use to type into text boxes. And that's how the majority of people negotiate their devices.

But that's not the only way. People use all sorts of different, as the Web Content Accessibility Guidelines (WCAG) calls them, [input modalities](https://www.w3.org/TR/wcag/#input-modalities). In fact, even from my own perspective, mouse/trackpad and touch is only superficially how I get around: I make very heavy use of my keyboard beyond typing text.


## How I use the keyboard

I love efficient workflows, and using the keyboard on my laptop plays a huge part in speeding things up.

### Web page navigation

If I'm filling out a form on a web page, rather than take my fingers off the keys and onto my trackpad or mouse to move from field to field, I tend to fly through using <kbd>⇥</kbd> (Tab). I'll keep my fingers over the keys to check/uncheck checkboxes and press in-form buttons using <kbd>Space</kbd>, and I usually try to submit a form using <kbd>⏎</kbd> (Return/Enter) while still focused on a field, since it's quicker than tabbing to and pressing the Submit button.

For general browsing, I like to use <kbd>Space</kbd> to scroll the page down by a viewport's-height. I also have a habit of using the <kbd>⇥</kbd> key to move focus to links or buttons, lazily [priming my focus](/blog/focus-priming) with the mouse/trackpad and tabbing around from there, and I'll happily arrow around [tabs and other button groups](/blog/how-button-groups-should-work-for-keyboard-users).

<i>Note: if you're a Safari-on-Mac user and want to make more use of the keyboard [there's a bit of config](/blog/how-to-use-the-keyboard-to-navigate-on-safari) you have to set first.</i>

### App and operating system navigation

Regardless of whether I'm typing or clicking around, my left hand is always on my keyboard, as it makes getting around my operating system much quicker; for example, I:

- make heavy use of <kbd>⌘</kbd> (Command) + <kbd>⇥</kbd> to switch from app to app
- close windows with <kbd>⌘</kbd> + <kbd>w</kbd>
- open tabs with <kbd>⌘</kbd> + <kbd>t</kbd>
- go to the next or previous tab with <kbd>⌃</kbd> (Control) + <kbd>⇥</kbd> or <kbd>⌃</kbd> + <kbd>⇧</kbd> (Shift) + <kbd>⇥</kbd>
- cycle through windows with <kbd>⌘</kbd> + <kbd>`</kbd>
- quit an app with <kbd>⌘</kbd> + <kbd>q</kbd>
- press <kbd>⏎</kbd> to confirm a dialog

You get the idea.

### And an uncommon set-up

Every laptop or desktop computer comes equipped with a keyboard, and keyboards are getting more and more common on iPads, but not on phones, surely?

You might find this bizarre, but my keyboard usage extends to my iPhone too, where I sometimes [hook a Bluetooth keyboard up to my phone](/blog/holidays-no-laptop-and-a-bluetooth-keyboard).


## Hurdles

Being a hybrid mouse/trackpad and keyboard user, I get a bit of an insight into some of the issues keyboard-only users encounter. Those hurdles irritate me as they break my flow and force me to use a mouse-based workaround, but there’s an extra level of frustration in knowing these are hurdles some people simply can’t get past.

But that's not the full picture. People use digital products in all sorts of ways, and we've only just scratched the surface.


## The wider perspective

I love this micro-site on how [100 people who use your website might break down](https://how-many.herokuapp.com/people/100); there are people in that list whose primary input modality might be:

- keyboard-only
- screen reader
- speech recognition software

Or any number of other methods, like a switch device, eye tracking, or head-wand. The point is, as my late father-in-law used to say:

<blockquote lang="es-es">
    El mundo es plural
</blockquote>

Which very roughly translates from Spanish as "Everybody is different".

Designing and building accessible software means continually questioning your own habits and assumptions. No single way of interacting with technology is universal.
