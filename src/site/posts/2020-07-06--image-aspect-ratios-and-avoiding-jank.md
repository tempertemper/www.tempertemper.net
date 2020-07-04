---
title: Image aspect ratios and avoiding jank
intro: |
    Adding dimensions to our images in HTML is useful again. They're a progressive enhancement to calculate the image's aspect ratio and prevent jank.
date: 2020-07-06
tags:
    - Development
    - Performance
---

In last week's [roundup of the most interesting features announced at WWDC 2020](/blog/wwdc-2020-roundup) I mentioned that Safari would now be supporting image aspect ratios, but what does that mean?


## Remember image size attributes?

Let me start with another trip down memory lane. I remember when it was best practice to add image size attributes to every image. I think there was even an SEO benefit to specifying the `width=""` and `height=""`. But then [responsive web design and high definition screens](/blog/the-briefest-of-histories-of-responsive-images) arrived and those attributes became superfluous.

Because the responsive web squashes and stretches things depending on the size of the screen, images weren't going to be what we specified in the `width=""` and `height=""` attributes except at very specific screen widths. So we stopped adding image dimensions to our HTML, and even removed them from existing code.

Well it turns out they're useful again, so we can start adding them back!


## Image dimensions are useful again

Before I go into why image dimensions are useful again, and how to use them, it's worth illustrating a common annoyance on the web: 'jank'.

### Janky web pages

There's only one thing more more frustrating than a slow website: a slow website where you have to chase content down the page as images load in above. Worse if you're trying to click a link that has suddenly become a moving target.

The problem is that the browser knows the width of the image as it's usually set to `100%`, but the height has to be worked out once the image itself has loaded. Until then, the browser has no idea, so it goes with `0` until it knows. So that `0` height suddenly jumps to the height of the image in relation to its width once the image has been downloaded.

Browsers read a webpage from top to bottom, rendering what they can as they go. While they're fetching an image, they'll continue to output the rest of the page, so you often see a couple of paragraphs of text suddenly separate when the image in between them finally arrives, and this causes the second paragraph to jump down the page.

What if you were reading that second paragraph? Or about to click a clink in it? It's now a moving target!


### Stopping the janky loading with CSS

The jank could always be stopped with CSS and a wrapping `<div>`, but *only if we knew the image's aspect ratio* (say 16:9). We'd then use CSS and the padding hack:

```css
.image-wrapper {
  padding-bottom: 56.25%;
  position: relative;
}
.image-wrapper img {
  width: 100%;
  height: 100%;
  position: absolute;
  left: 0;
  top: 0;
}
```

But that means extra markup (`<div class="image-wrapper">) and extra CSS. It also means all images would have to have the same aspect ratio, and if they didn't the CSS would have to added to. Not ideal.

### A proper fix

`width=""` and `height=""` attributes in our markup are now meaningful again. The browser uses them to calculate the *aspect ratio* of an image, which, as it knows the width, allows it to set a height. So while the image is being fetched and downloaded the browser leaves the correct amount of space where the image will appear. So you can carry on reading that paragraph under it—or even click that link!—without worrying about the page shuffling down.


## What our code should look like

### HTML

Let's start with the markup. All you need to do is add a `width=""` and `height=""` to your images!

```html
<img src="/img/my-great-image.jpg" alt="My alt text" width="800" height="450" />
```

First of all, note there are no units (`width="800" height="450"`, rather than `width="800px" height="450px"`). Like SVG, omitting units is the least specific (weakest) way of doing this, which means it's the most easily overridable.

Without units the browser will calculate the dimensions using pixels anyway (whatever pixels mean), so if CSS fails, those retina images stay at a sensible size and don't take over the full width of your laptop's screen, so the content is still nicely readable.

It's possible to boil your dimensions right down, knowing that CSS will step in and make them full-width, like this:

```html
<img src="/img/my-great-image.jpg" alt="My alt text" width="16" height="9" />
```

But **don't do that**. in the scenario where CSS fails to load, your image would be teeny tiny (16px × 9px in this example) so I'd encourage you to use more sensible dimensions like `800` × `450`.

### CSS

The CSS is minimal. You set a width and a height that override the values in the HTML:

```css
img {
    width: 100%;
    height: auto;
}
```

If that's too broad, you could scope that to, for example, images within articles:

```css
article img {
    width: 100%;
    height: auto;
}
```

So we're telling the browser:

- to make images the same width as their container
- for images' heights should be calculated automatically based on
    - the width of the container
    - the image itself's aspect ratio


## The limitations of Markdown

One thing to mention is that Markdown can't add image attributes so instead of the `![My alt text](/my-great-image.jpg)` we have to use HTML `<img src="/my-great-image.jpg" alt="My alt text" />`.

I don't mind that at all, as it means I can throw a `loading="lazy"` attribute on images that appear below the 'fold'.

## Browser support

Firefox, Chrome (therefore Edge and Opera) support intrinsic aspect ratios today. Apple announced that Safari will support them when the next versions of Safari are released in (likely) September, but to get a sneak preview, [Safari Technology Preview](https://developer.apple.com/safari/technology-preview/) supports it now.

This is a progressive enhancement, so older browsers will just get the jank they always got. Not great, but not a worse experience than they were already getting.
