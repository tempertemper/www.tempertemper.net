---
title: Accessibility doesn't stop at WCAG compliance
intro: While it's true that WCAG represents a solid baseline, there's a lot more we should be doing to make our work truly accessible.
date: 2022-06-23
tags:
    - Accessibility
summaryImage: robson-square-steps-vancouver.jpg
summaryImageAlt: A wide outdoor staircase with a ramp zig-zagging through the steps. There are no handrails or barriers on the ramp.
---

The [Web Content Accessibility Guidelines](https://www.w3.org/TR/WCAG/) (WCAG) are held up as The Way. Meet WCAG, ensure you continue to meet WCAG, and you're in good shape. While it's true that WCAG represents a solid baseline, there's a lot *more* we can and should be doing to make our work accessible to as many people as possible.

I spotted [a tweet from Sarah Blahovec](https://twitter.com/Sblahov/status/1524479291676889089) a wee while ago that illustrated this nicely:

> I can't believe I'm sitting in an ADA compliance webinar where this was just given as an example of compliant and accessible design.
>
> <picture>
>     <source srcset="/assets/img/blog/robson-square-steps-vancouver.avif" type="image/avif" />
>     <source srcset="/assets/img/blog/robson-square-steps-vancouver.webp" type="image/webp" />
>     <img class="natural-dimensions" src="/assets/img/blog/robson-square-steps-vancouver.jpg" alt="A set of stairs that has a ramp zigzagging back and forth cutting down the steps. The steps are frequently interrupted by the ramp and there are no handrails except for at the side of the stairs." width="400" height="300" loading="lazy" decoding="async" />
> </picture>

The most interesting reply to the tweet [highlighted the legal compliance](https://twitter.com/MurphyJ/status/1524503940699758592) of the ramp:

> if the slope of the "ramp" is less than 1:20(5%), it is not technically a "ramp" and handrails, landings, edge protection are not required.

The suggestion is that the architect knew that they could avoid using handrails if they ensured the slope of the ramp didn't exceed a certain angle. So the stairs are *compliant* design, but not *accessible* design.


## Three ways we can do better than WCAG

Complying with WCAG is great, but *only just* meeting a threshold, or taking advantage of an exception or loophole is very much not in the spirit of accessibility. Here are three good examples of things WCAG doesn't cover:

### Icon-only buttons

WCAG allows icon-only buttons. As long as they have accessible non-visible text, that's considered sufficient. But [I think we can do better](/blog/what-i-wish-was-in-wcag-prohibit-icon-only-buttons).

Aren't those icons pretty cryptic sometimes? Some icons are very widely used, but others might need a little more imagination. I'm not sure that's fair on all users.

And then there's people who use speech recognition software, who often have to guess the correct word to activate an icon-only button. There are work-arounds, like "Show numbers", which labels all interactive elements with a number that can then be 'clicked', but is that the best experience we can give our users?

### Disabled buttons

WCAG says that disabled buttons don't have to meet the [required levels of contrast](https://www.w3.org/TR/WCAG21/#contrast-minimum), in order to be more easily discernable by users with visual impairments:

> The visual presentation of text and images of text has a contrast ratio of at least 4.5:1, except for … Text or images of text that are part of an inactive user interface component

So is it okay to leave some visually impaired users wondering what that difficult-to-make-out button says? Or unsure if there's a button at all? Especially when it's a pretty simple matter to [avoid disabled buttons](/blog/how-to-avoid-disabled-buttons) altogether.


### Animation

WCAG says [auto-playing animation is fine](https://www.w3.org/TR/WCAG21/#pause-stop-hide) as long as one of the following conditions is met:

- it can be stopped
- it doesn't last more than five seconds

I don't imagine users with a vestibular disorder or photosensitive epilepsy, who could experience nausea, migraine, or even seizure, would appreciate having to frantically look for a pause button to stop the animation. And if there's no button and it stops within five seconds, there's no guarantee that something unpleasant (or dangerous…) hasn't already been triggered.


## Meeting WCAG isn't enough

Just because we're WCAG 2.1 AA compliant doesn't mean there's not more we can do to make our sites *actually* accessible.

Regarding just scraping in as 'job done' is not enough. Accessibility is not a checklist, and AA compliance is just the start. Just because something is compliant doesn't mean it's accessible!
