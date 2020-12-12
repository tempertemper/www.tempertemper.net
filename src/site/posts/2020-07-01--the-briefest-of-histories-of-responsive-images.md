---
title: The briefest of histories of responsive images
intro: |
    There are a lot of things to consider when using images on the web. But why is it so complex? And how can we tackle that complexity?
date: 2020-07-01
tags:
    - Development
    - Design
---

2010 was a big year for images on the web. [Ethan Marcotte's responsive web design](https://alistapart.com/article/responsive-web-design/) article was published on A list Apart and the iPhone 4 landed.

Remember that iPhone? The biggest news for users and web developers alike was its Retina display. The extra 2ᳵ resolution made it very difficult to pick out any pixels; it looked *gorgeous*. Other device manufacturers quickly followed suit.

But there was a lot of work to do! Images all over the web suddenly looked fuzzy in comparison to the beautifully rendered text next to them.

Rewind to the days before responsive websites. Every image's dimensions were known, and web developers could carefully optimise images to look great in an 800px ᳵ 450px container, compressing them down until that moment before they lost too much fidelity.

Then, of course, mobile devices and responsive design arrived and we had a bit of a moving target. We still had a lot of control over those images as we knew the largest they'd be at any given screen size and could optimise them at those dimensions.

But now with Retina/HD screens it got a lot more difficult. Our images had to be bigger than their container, so that when they were squeezed down to fit they were approximately double the pixel density they were before and looked sharp and crisp on high definition screens.

Books like [Retinafy your web sites and apps](https://retinafy.me) sprang up with tips and tricks to get the best images with the lowest performance overhead. SVG and (whisper it) icon fonts came to the fore for icons, where PNGs and GIFs were previously commonplace.

Since then, we've been given the `srcset=""` attribute for images, where we can give the browser a few different resolution images with instructions to, for example, serve the smallest one on small screens and progressively larger images as screen sizes increase. Good for keeping our websites performant.

`<picture>` allows us much more control. We can serve different images *with the same meaning* in different circumstances. Some examples:

- a square cropped, zoomed-in-on-the-subject image on mobile, and the same picture but in a landscape crop and much more zoomed out on larger screens
- a darker variant for visitors who have Dark Mode turned on (with the `media="(prefers-color-scheme: dark)"` attribute)
- a static image for those with `media="(prefers-reduced-motion: reduce)"` turned on, and an animated gif for everyone else
- serving a more compressed image like WebP, if it's supported by the browser, or a good old JPEG if it's not

As displaying images on the web has become more challenging, the level control we have over them has grown accordingly. CSS-Tricks have recently published a [complete guide to `srcset=""` and `<picture>`](https://css-tricks.com/a-guide-to-the-responsive-images-syntax-in-html/) which demonstrates how complex it can be.

For me, I try to keep my website image-light, only using them where absolutely necessary. I'm not one for arbitrary [hero images on blog posts](/resources/hero-images-pros-and-cons) and prefer to let the words do the talking. Hopefully that's enough!
