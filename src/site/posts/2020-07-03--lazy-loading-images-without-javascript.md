---
title: Lazy loading images without JavaScript
intro: |
    Non-JavaScript lazy loading is a great progressive enhancement for image-heavy pages on the web. Just a simple HTML attribute and you're away!
date: 2020-07-03
tags:
    - Development
    - Performance
    - HTML
summaryImage: large
---

When you've got loads of images on a page, you might not want the browser to fetch them all at once.

Some obvious examples are blog listing pages, or that page where a company introduces you to all of their many employees. If there's one image per item in the list, there's probably a *lot* of images! And with a lot of images comes a lot of requests to the server, lots of downloading, and lots of data allowances being burned through.

There are a [few techniques](https://www.smashingmagazine.com/2016/03/pagination-infinite-scrolling-load-more-buttons/) we can use to keep this manageable:

- pagination (common for search results)
- infinite scrolling (common on social media)
- 'Load more' buttons (common with e-commerce)

Even then, there can be plenty of images to fetch and display. Maybe the visitor only needs the first few, if they were looking for the latest blog post, or Aaron Aardvarkson in that alphabetically sorted employee list. Of course, they might need to scroll down to find the post/person they came for, so why not defer loading those images until we know they're going to want them?


## Enter lazy loading

Lazy loading has been around for a long time. The image's `src=""` attribute contains a placeholder image which is swapped out with JavaScript when the user scrolls close to it. But I've never been comfortable with that -- what if the script fails and the visitor is left without an image? Not a great experience.

Progressive enhancement is the key, and the HTML-only `loading="lazy"` attribute is all that's needed:

```html
<img src="/img/my-great-image.jpg" alt="My alt text" loading="lazy" />
```

We're telling browsers that the image should only be fetched when it's needed:

- if it's in view, or nearly in view when the page is loaded
- if the user scrolls near the image (just before it comes into view)

Browsers that don't understand the `loading="lazy"` attribute will still fetch the image, so be careful not to rely too heavily on it, but it's a nice enhancement for those pages where you would've served a bunch of images all at once anyway.

[Support across the board isn't far off](https://caniuse.com/#feat=loading-lazy-attr). Firefox, Chrome, Edge and Opera have it, and Safari has it behind a flag (from the menu: Develop → Experimental Features → Lazy Image Loading).

Hopefully the more it's used, the quicker the Safari/Webkit team will be to get the feature baked in as a default. I don't have any image-heavy listings pages on this website, but I do have [case studies](/portfolio/) that use screenshots and diagrams for illustration, so you'll find a few choice `loading="lazy"` attributes over there.
