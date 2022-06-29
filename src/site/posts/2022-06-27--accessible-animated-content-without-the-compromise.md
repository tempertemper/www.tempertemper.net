---
title: Accessible animated content without the compromise
intro: Accessible animated GIFs are rubbish. Instead of compromising our animations in order to meet WCAG, we should be checking what our users prefer.
date: 2022-06-27
updated: 2022-06-29
tags:
    - Accessibility
    - Design
    - Development
---

I've written about how [accessible animated content can be a bit rubbish](/blog/accessible-animated-gifs-are-pointless), and I stand by that. It's better just to use a static image than compromise the experience of the animation in order to make it accessible.

To recap why they're less than ideal, meeting [WCAG'S Pause, Stop, Hide](https://www.w3.org/TR/WCAG21/#pause-stop-hide) means you lose the essence of an animated GIF, as it:

- shouldn't animate automatically
- shouldn't loop indefinitely (stops within 5 seconds)

Adding a pause button is a solution, but I don't want to make someone feel sick, trigger a migraine, or worse, *and* expect them to scrabble around looking for the pause button. Remember: [just because it meets WCAG doesn't mean it's accessible](/blog/accessibility-doesnt-stop-at-wcag-compliance)!

The good news is that, since I wrote that first article, I've discovered a way we can [have our cake and eat it](https://en.wikipedia.org/wiki/You_can't_have_your_cake_and_eat_it). No compromise to the animation, no pause buttons, and accessible!

I was catching up on some reading and came across Hidde de Vries' [Meeting "2.2.2 Pause, Stop, Hide" with prefers-reduced-motion](https://hidde.blog/meeting-2-22-pause-stop-hide-with-prefers-reduced-motion/), where Hidde explains:

> The crucial part of the Success Criterion text, I think, is:
> 
> > for [moving, blinking and scrolling content] there is a **mechanism** for the user to pause, stop, or hide it
> 
> (emphasis mine; exception and exact definition of the content this applies removed for clarity)
> 
> I feel setting 'prefers reduced motion' could count as such a mechanism, for most cases of moving content.

I agree! This means that we can use GIFs and other animated content freely *and* remain compliant:

- animated GIFs do what they do for people who are okay with that
- static images with the same meaning are used when people tell us they are not okay with animation

Of course, we have to be careful with the implementation. We have to ensure we don't make any assumptions about the [7.66% of people who *can't* tell us they'd prefer less/no motion](https://caniuse.com/prefers-reduced-motion); those on:

- older operating systems
- older web browsers
- Opera Mini (where lack of support is probably a philosophical decision)

We can do that by [using the picture element and some nice progressive enhancement](/blog/progressively-enhanced-animated-content):

```html
<picture>
    <source srcset="animation.gif" media="(prefers-reduced-motion: no-preference)" />
    <img src="static-image.jpg" alt="A description that applies to both the image and animation" />
</picture>
```

<i>Note: using `<picture>` is a great approach for most animated GIFs (or GIF-style videos), but the message should always be the same as the static image; no users should miss out on the meaning of the content because they can't see the animation.</i>

This means:

- Users who can't tell us that they prefer reduced motion because of the limitations of their software get the static image; if we can't be sure, we shouldn't animate
- Users who *can* tell us whether they prefer reduced motion or not get a static image or an animation, depending on their settings

So accessible animations are a waste of time, but if we serve the right content (either a static image or an animation) to our users based on their operating system preferences, everyone gets a great experience!
