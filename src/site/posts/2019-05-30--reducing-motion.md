---
title: Reducing motion
intro: |
    Accessibility is important, so I've taken steps to minimise animation on my site, and even removed it completely for those who 'prefer reduce motion'.
date: 2019-05-30
tags:
    - Design
    - Development
    - Accessibility
---

There has never been a lot of animation on my website, and that was a very deliberate decision. Accessibility is very high on my list of priorities when designing and developing a website, and motion and balance (vestibular) disorders---permanent or temporary---mean that animation can be very uncomfortable for some people.

That said, I do have some animation on my website. No parallax scrolling or items flying into view as you scroll down the page; certainly not! But I've taken steps to reduce it by:

1. Removing animation entirely where it didn't really serve any purpose or add any character, for example links used to have a very slight animation to smooth the colour change on hover
2. Stopping animation completely when someone has their operating system set to reduce motion (on a Mac it's in System Preferences → Accessibility → Display → Reduce motion)

There are only a handful of moving parts left on my site, now that number 1 has been taken care of:

- The underscore on the logo blinks every couple of seconds, to make it look like a cursor
- When opening and closing the navigation on mobile screens two things happen:
    1. The three lines in the icon animate to form a cross
    2. The navigation menu is revealed smoothly
- Upon focus, the search button grows to fill the whole navigation bar, covering the navigation items

The `prefers-reduced-motion` media query allows us to prevent those animations. As an example, here's the SCSS to show how I disabled the animation for the underscore/cursor in my logo:

```css
.underscore {
  animation: blink 2s steps(20, start) infinite;

  @media screen and (prefers-reduced-motion: reduce) {
    animation: none;
  }
}
```

`prefers-reduced-motion` was [introduced to Safari in 2017](https://webkit.org/blog/7551/responsive-design-for-motion/) and has pretty good [support across browsers](https://caniuse.com/#feat=prefers-reduced-motion).

My advice would be to be conservative with your use of motion on your website, but, where you do use it, be sure to offer a reduced or zero motion alternative for those who ask.
