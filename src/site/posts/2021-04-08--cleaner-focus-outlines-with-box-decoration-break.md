---
title: Cleaner focus outlines with box-decoration-break
intro: |
    When I changed my site's form and button focus styles, links felt a bit left out. But discovering `box-decoration-break` has made things consistent.
date: 2021-04-08
tags:
    - CSS
summaryImage: box-decoration-break--newest.png
summaryImageAlt: A screenshot of a longer heading on my blog listing page that is also a link and in its focus state. It's a black outline in Light Mode and a white outline in Dark Mode, and the outline looks tidy where the text wraps onto a new line.
---

Until the week before last, my site's form input focus styling was a bit all over the place, so I spent some time designing a consistent style for form inputs, including buttons. I settled upon:

- a black outline in Light Mode
- a white outline in Dark Mode

The outlines (actually using `box-shadow`, rather than `outline`, so that the outline follows the rounded corners of the inputs and buttons) are:

- high contrast against their background
- high contrast against the blue button or input border they surround
- 3px thick, increasing the input's overall size slightly

This ensures that I'm not [communicating meaning solely with colour](https://www.w3.org/TR/WCAG21/#use-of-color) and preempts the up-coming [Focus Appearance (Minimum) success criterion](https://www.w3.org/TR/WCAG22/#focus-appearance-minimum) in WCAG 2.2.

But what about links? The link focus styling was now different to forms and buttons; it changed the background colour of the link to a blue but didn't add an outline:

<picture>
    <source srcset="/assets/img/blog/box-decoration-break--old.avif" type="image/avif" />
    <source srcset="/assets/img/blog/box-decoration-break--old.webp" type="image/webp" />
    <img src="/assets/img/blog/box-decoration-break--old.png" alt="A screenshot of a longer heading on my blog listing page that is also a link and in its focus state. It has a blue background with a black outline in Light Mode and a white outline in Dark Mode, but the outline looks messy where the text wraps onto a new line." width="800" height="450" loading="lazy" decoding="async" />
</picture>

This was fine from an accessibility point of view, but consistency is important; especially when highlighting things that can be interacted with: an outline for some elements, a background colour for others…

I had tried it adding a border to focused links, but it looked a bit messy when links broke onto a new line, so I resigned myself to a difference between links' and other elements' focus styling:

<picture>
    <source srcset="/assets/img/blog/box-decoration-break--trial.avif" type="image/avif" />
    <source srcset="/assets/img/blog/box-decoration-break--trial.webp" type="image/webp" />
    <img src="/assets/img/blog/box-decoration-break--trial.png" alt="A screenshot of a longer heading on my blog listing page that is also a link and in its focus state. It has a blue background with a black outline in Light Mode and a white outline in Dark Mode, but the outline looks messy where the text wraps onto a new line." width="800" height="450" loading="lazy" decoding="async" />
</picture>

Just the other day, though, I spotted a [tweet from Josh W. Comeau](https://twitter.com/JoshWComeau/status/1374371370864283655) that mentioned `box-decoration-break` which, after a bit of [reading up on MDN Web Docs](https://developer.mozilla.org/en-US/docs/Web/CSS/box-decoration-break), looked like it might solve my messy outline problem!

So I added the following to my focus style to see what would happen:

```css
box-decoration-break: clone;
```

The result was exactly what I was after:

<picture>
    <source srcset="/assets/img/blog/box-decoration-break--newer.avif" type="image/avif" />
    <source srcset="/assets/img/blog/box-decoration-break--newer.webp" type="image/webp" />
    <img src="/assets/img/blog/box-decoration-break--newer.png" alt="A screenshot of a longer heading on my blog listing page that is also a link and in its focus state. It has a blue background with a black outline in Light Mode and a white outline in Dark Mode, and the outline looks tidy where the text wraps onto a new line." width="800" height="450" loading="lazy" decoding="async" />
</picture>

Where previously I was using a blue background and inverting the text colour, bringing links in line with forms would mean simply adding an outline, so I went one step further and went outline-only:

<picture>
    <source srcset="/assets/img/blog/box-decoration-break--newest.avif" type="image/avif" />
    <source srcset="/assets/img/blog/box-decoration-break--newest.webp" type="image/webp" />
    <img src="/assets/img/blog/box-decoration-break--newest.png" alt="A screenshot of a longer heading on my blog listing page that is also a link and in its focus state. It's a black outline in Light Mode and a white outline in Dark Mode, and the outline looks tidy where the text wraps onto a new line." width="800" height="450" loading="lazy" decoding="async" />
</picture>

The good news is that `box-decoration-break` has [great support across all modern browsers](https://caniuse.com/?search=box-decoration-break) when used with the `-webkit-` prefix, and on browsers that don't support `box-decoration-break` it still does its job; just looks a bit messier.
