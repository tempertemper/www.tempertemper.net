---
title: Figcaptions versus alt text
intro: How does an image's descriptive (`alt`) text differ from the caption it would have if it were used in a `<figure>` element?
date: 2026-02-28
tags:
    - Accessibility
    - HTML
    - Content
---

I've had the same conversation more than once about how a `<figure>` element's caption should be used, and how that differs from an image's `alt` text.


## Image descriptions

Before we go into the difference, it's important to understand what `alt`, or descriptive, text is. The Web Content Accessibility Guidelines (WCAG) provides some clarity in [Non-text Content](https://www.w3.org/TR/WCAG22/#non-text-content), which covers image `alt` text:

> All non-text content … has a text alternative that serves the equivalent purpose

The `alt` text must convey the same message as the image. This means that anybody who can't see an image can still understand the meaning it's communicating.

As a quick aside, there are a few things that I won't get into:

- Images that don't convey meaning: these wouldn't make sense to use in a `<figure>` element
- How detailed, or otherwise, the description of the image should be
- Other things that can be used in a `<figure>` element, like code, video, or just plain old text


## Captions

Okay, so now we know what `alt` text is, let's talk about `<figcaption>` and how it's different. First, the markup pattern; here's a typical `<figure>` element that uses an image:

```html
<figure>
    <img src="example.jpg" alt="Image description" />
    <figcaption>The caption</figcaption>
</figure>
```

The `<figure>` groups an image, its descriptive text, and a `<figcaption>`. [MDN Docs has this to say](https://developer.mozilla.org/en-US/docs/Web/HTML/Reference/Elements/figcaption):

> The `<figcaption>` HTML element represents a caption or legend describing the rest of the contents of its parent `<figure>` element

Not too much to go on, so let's think about captions. [Merriam Webster](https://www.merriam-webster.com/dictionary/caption) has a nice explanation of the word 'caption' in our context:

> the explanatory comment or designation accompanying a pictorial illustration

So we're not talking about describing the image, which would be repetitive; we're explaining what part the image plays in what we're writing.



## Some examples

I've always found this a bit abstract, and better explained with examples.

### A simple photo

Let's start with the image I use on my [About page](/about) (I'll strip away the `<picture>` markup I use over there for simplicity's sake):

```html
<img src="/assets/img/martin-underhill-tempertemper.jpg" alt="A friendly white man with a bald head, short brown beard, and glasses. He's wearing a casual dark blue shirt and is leaning against a grey stone wall, nursing a cup of tea." />
```

The image's context, placed directly after the intro paragraph about me, means that readers will know it's a picture of me. And you'll note that with the `alt` text alone you get a good flavour of what the image is communicating.

Now let's imagine the same image appeared on a longer page which referenced a few people, where the reader might not know that the picture was of me. We might do this:

```html
<figure>
    <img src="/assets/img/martin-underhill-tempertemper.jpg" alt="A friendly white man with a bald head, short brown beard, and glasses. He's wearing a casual dark blue shirt and is leaning against a grey stone wall, nursing a cup of tea." />
    <figcaption>Martin Underhill of tempertemper</figcaption>
</figure>
```

The content preceding and following the image no longer has to lead directly into or out of it, since it's accompanied by the "Martin Underhill of tempertemper" caption.

This pattern works well for other articles, for example an article about football:

```html
<figure>
    <img src="a-tackle.jpg" alt="Two footballers aggressively challenging for the ball in the air; the player in black and white has reached the ball with his head before the one in light blue" />
    <figcaption>Newcastle United's Dan Burn kept Manchester City's Erling Haaland quiet for most of the game</figcaption>
</figure>
```

### Multiple images

I've done a fair bit of [accessibility auditing](/services/audits) in my time, and I often find the same issue in multiple places. Instead of raising an issue for each, I usually list all the occurrences in a single issue.

One image and a bulleted list for each occurrence usually does the job here, but sometimes the same issue might look slightly different in different places; in which case I'll group those variations together under a `<figure>` and explain why they're relevant with a single caption:

```html
<figure>
    <img src="issue1a.png" alt="A text link that has keyboard focus but no visible focus state" />
    <img src="issue1b.png" alt="A button that has keyboard focus but no visible focus state" />
    <img src="issue1c.png" alt="A checkbox that has keyboard focus but no visible focus state" />
    <figcaption>Keyboard focus states are not present</figcaption>
</figure>
```

The key thing here is that all images should demonstrate the same point.

### Reference

Again when auditing, I sometimes have the opposite situation, where one image would illustrate multiple issues raised. I could repeat the same image on each issue, but sometimes it can be more reader-friendly to use a single image in the first issue, numbered in its caption, and reference it from each subsequent issue:

```html
<figure>
    <img src="figure1.png" alt="A list of icons, each representing a different service that is on offer." />
    <figcaption>Figure 1: Icons are used instead of visible text for navigation to each service.</figcaption>
</figure>
```

I might then reference 'Figure 1' in a number of separate-but-related accessibility issues that are caused by [using icons instead of text](/blog/what-i-wish-was-in-wcag-prohibit-icon-only-buttons).

### Interesting formats

If the image is a photo, screen grab, illustration, the JPG or PNG format is fine (or something similar like [WEBP](/blog/using-webp-images) or [AVIF](/blog/avif-image-compression-is-incredible)), but sometimes a diagram or chart might be best as an SVG. You can reference the SVG from an `<img />` element, of course, but you can also embed the SVG directly on the page:

```html
<figure>
    <svg width="500" height="200" viewBox="0 0 500 200" xmlns="http://www.w3.org/2000/svg" aria-labelledby="logoTitle">
        <title id="logoTitle">
            A line graph showing newsletter subscriber growth over time, with an overall upward trend and a sharp increase in early 2025.
        </title>
        <!-- SVG paths -->
    </svg>
    <figcaption>Newsletter subscribers continue to increase over time</figcaption>
</figure>
```

In this case, the `<title>` gives a concise equivalent of what the chart conveys. If readers need more detail; for example exact dates, plateaus, or growth rates; that explanation is often better placed in the surrounding content (maybe in an accompanying table?) rather than crammed into the image's descriptive text.


## Replacing versus explaining

Not every image needs a `<figure>` and `<figcaption>`; an `<img />` with well-written `alt` text in the right context is often enough. But if the image (whether visually or through its descriptive text) doesn't stand on its own, you probably need a caption to explain what the image adds to the page.

So `alt` and caption should never be the same. One replaces the image; the other supports it.
