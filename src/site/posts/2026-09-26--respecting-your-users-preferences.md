---
title: Respecting your users’ preferences
date: 2026-09-26
intro: Our users have already chosen how they want their devices to behave. Here's how we can respect those preferences on the web.
tags:
    - CSS
    - Accessibility
---

Over the years, I've had many battles about accessibility 'modes', and why they're a terrible idea. That's another article for another day, but one thing 'mode' advocates often come back with is that we already have modes for things like colour and motion, so why not just add more?

Our users have already told their operating system how they want things to work, whether that's darker colours, more contrast, or less movement. We should respect those preferences as they browse the web.

The browser allows us to check for various operating system preferences, for example:

- `prefers-color-scheme`, for Dark Mode
- `prefers-contrast`, for Increased Contrast Mode
- `prefers-reduced-motion`, for Reduced Motion Mode

It's important we listen to what our users tell us and design accordingly. And if respecting our users isn't reason enough, there are rulebooks to back it up.


## What the standards say

Both the [Web Content Accessibility Guidelines](https://www.w3.org/TR/wcag/) (WCAG) and the [European accessibility standard EN 301 549](https://www.etsi.org/deliver/etsi_en/301500_301599/301549/03.02.01_60/en_301549v030201p.pdf) address aspects of respecting user preferences.

### Dark Mode

Dark Mode isn't referenced specifically, but EN 301 549 talks about colour in [section 11.7](https://www.etsi.org/deliver/etsi_en/301500_301599/301549/03.02.01_60/en_301549v030201p.pdf#page=82):

> [The] user interface shall follow the values of the user preferences for platform settings for: units of measurement, colour, contrast, font type, font size, and focus cursor

It's a reasonable interpretation that Dark Mode counts as a colour preference; here's how we'd use it in CSS:

```css
@media (prefers-color-scheme: dark) {
  /* Dark mode styles go here */
}
```

### Contrast

Where it's slightly vague on Dark Mode, EN 301 549 11.7 calls out contrast directly. The CSS would look like this:

```css
@media (prefers-contrast: more) {
  /* Increased contrast styles go here */
}
```

<i>By the way, Increased Contrast Mode is different from Windows High Contrast Mode; I've written about [the distinction between them](/blog/the-difference-between-increased-contrast-mode-and-windows-high-contrast-mode).</i>

### Motion

Reduced motion isn't listed among the preferences in EN 301 549's clause 11.7, but the standard does incorporate WCAG's [2.2.2 Pause, Stop, Hide](https://www.w3.org/TR/WCAG21/#pause-stop-hide).

I've written about [reducing motion on my website](/blog/reducing-motion), and how respecting `prefers-reduced-motion` [can provide a mechanism to stop animated content](/blog/accessible-animated-content-without-the-compromise).

Here's how we'd code it:

```css
@media (prefers-reduced-motion: reduce) {
  /* Static styles go here */
}
```

There's an [alternative approach to using the motion media query](/blog/progressively-enhanced-animated-content) that's also worth reading about.


## Respecting our users and their choices

Someone who has chosen Dark Mode shouldn't see a bright white page just because they've followed a link; someone who has asked for less motion shouldn't have to hunt for another switch to stop things moving.

The browser gives us ways to listen to our users; our responsibility is to make sure our websites respect their choices.
