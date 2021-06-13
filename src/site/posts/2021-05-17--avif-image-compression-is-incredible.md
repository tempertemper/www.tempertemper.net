---
title: AVIF image compression is incredible
intro: |
    WebP images are now supported in all modern web browsers, but the emerging AVIF format promises to be even better; I'm pleased to tell you it is!
date: 2021-05-17
updated: 2021-05-18
tags:
    - Development
    - Performance
summaryImage: large
---

I've [written about WebP images](/blog/using-webp-images), which are now supported in all modern web browsers, but I've been meaning to look into AVIF images for a while. AVIF promises to be a superior image compression algorithm, and having tried it I can say that it's incredible!

Before I get into how good AVIF is, there are, broadly speaking, two approaches with this new found compression power:

1. Reduce file size and keep the same visible image quality as the equivalent JPEG or PNG
2. Increase image quality and keep the file size the same as the equivalent JPEG or PNG

Of course, there's an in-between approach where we increase the quality of the image while still reducing the file size, and AVIF is *definitely* capable of this.


## The savings

Taking my already-exported-for-web JPG and PNG images, I've been getting consistent file size reductions of around two thirds; often even more. That's a lot of savings for no further loss of fidelity!

This is in line with [Daniel Aleksandersen's findings](https://www.ctrl.blog/entry/webp-avif-comparison.html) and [Gilles Dubuc sums it up nicely](https://calendar.perfplanet.com/2018/is-avif-the-future-of-images-on-the-web/):

> The hype is indeed real

### WebP

Compare those savings with WebP, which tends to reduce most files by around a quarter and it's not even close! Especially when I've found the odd occasion where an equivalent quality WebP file ends up slightly bigger than the PNG or JPEG; I'm yet to see that with AVIF.


## Why is a smaller file size better?

The smaller the website bundle we sent to our users, the happier they'll be:

- They'll burn through their data less quickly, potentially saving them money
- Our websites will load more quickly, even on dodgy 3G connections
- Our websites are suddenly more accessible to people with low data plans and bad internet connectivity
- Search engines will rank our sites higher, now that [Google are taking 'page experience factors' into account](https://developers.google.com/search/blog/2020/11/timing-for-page-experience)


## Limited browser support

AVIF is currently only [supported in a couple of web browsers](https://caniuse.com/avif) but collectively they represent 64.62% of all users, so not to be sniffed at:

- Chrome (Mac, Windows and Android)
- Opera

Firefox has support in the next version due for public release, but we're still waiting on Edge (which I don't imagine will be all that far away, since it's built on the same engine as Chrome and Opera) and---crucially---Safari is yet to join the party, which would bring the non-Android mobile world some real benefits.

Until then, just like we're doing with WebP, we can serve AVIF images as a progressive enhancement via the `<picture>` element:

```html
<picture>
    <source srcset="my-great-image.avif" type="image/avif" />
    <source srcset="my-great-image.webp" type="image/webp" />
    <img src="my-great-image.jpg" alt="A description of the image." width="800" height="450" />
</picture>
```

This code:

1. serves the AVIF image if browser support is there
2. chooses WebP if AVIF isn't recognised
3. falls back a JPEG if neither AVIF nor WebP is supported


## Limited operating system and app support

AVIF isn't an export option in many image processing apps yet. I've tried Pixelmator Pro, Acorn, Figma, Sketch, and Affinity Designer, but their most advanced export format is WebP. There are [other ways to convert images to AVIF](/blog/converting-images-to-avif-in-2021), but to get the same visual control is much more laborious.

It's also not a supported format on macOS yet, which makes viewing the AVIFs in Finder awkward (I've been opening them in Opera). I found one or two bits of software that would allow Finder to display AVIFs, but I'm happy with my browser-based workaround for the time being.

Apple are a member of the group that created the [AV1 video format](https://en.wikipedia.org/wiki/AV1), which AVIF is based on, so it's surely only a matter of time before they begin to support AVIF in both Finder and Safari. And while we're waiting for that, I'm hoping our favourite apps will start to add AVIF as an export option.


## What I'm doing

Me? I've already rolled out AVIF support on this website, using the `<picture>` element; I've gone with like-for-like quality with my JPEG and PNG exports because:

- the images I export for my website are a careful balance of looking good and file size, so they're already decent enough quality
- I want to get them to my visitors faster
- I want to use up less of my visitors' data
