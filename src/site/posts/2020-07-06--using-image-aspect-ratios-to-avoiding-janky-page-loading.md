---
title: Using image aspect ratios to avoiding janky page loading
intro: |
    Adding dimensions to images in HTML is useful again! They're a progressive enhancement to calculate the image's aspect ratio and prevent jank.
date: 2020-07-06
tags:
    - Development
    - Performance
    - HTML
summaryImage: image-aspect-ratio.png
summaryImageAlt: A screengrab of the image on my About page loading in, showing that the browser has calculated the aspect ratio and left space for the image to load into.
---

In last week's [roundup of the most interesting features announced at WWDC 2020](/blog/wwdc-2020-roundup) I mentioned that Safari would now be supporting image aspect ratios, but what does that mean?


## Remember image size attributes?

Let me start with another trip down memory lane. I remember when it was best practice to add image size attributes to every image. I think there was even an SEO benefit to specifying the `width` and `height`. But then [responsive web design and high definition screens](/blog/the-briefest-of-histories-of-responsive-images) arrived and those attributes became superfluous.

Because the responsive web squashes and stretches things depending on the size of the screen, images weren't going to be what we specified in the `width` and `height` attributes except at *very specific* screen widths. So we stopped adding image dimensions to our HTML and even removed them from existing code.

Well it turns out they're useful again, so we can start adding them back!


## Image dimensions are useful again

Before I go into why image dimensions are useful again, and how to use them, it's worth illustrating a common annoyance on the web: 'jank'.

### Janky web pages

There's only one thing more more frustrating than a slow website, and that's a slow website where you have to chase content down the page as images load in above.

The problem is that the browser knows the width of the image as it's usually set to `100%`, but the height has to be worked out once the image itself has arrived from the server and the browser has worked its dimensions out. Until then the browser has no idea, so it goes with `0` until it knows. So that `0` height suddenly jumps to the height of the image in relation to its width once the image has been downloaded, shunting the content below it down the page.

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

The hack is possible because vertical padding is calculated based on the *width* of the element, rather than the height, as you might expect. The `%` value is based on the 16:9 aspect ratio: 9 ÷ 16 = 0.5625, which works out as 56.25 when expressed as a percentage. The height is 56.25% of the width.

The problems here are:

- extra markup -- you'll need a `<div class="image-wrapper">` around *every* image on your site
- extra styling -- the CSS above
- all images would be stuck with the same aspect ratio (16:9 in the above example)
- any images with a different aspect ratio (say 4:3) would need:
    - a dedicated class in the HTML
    - some more CSS to style that class

### A proper fix

`width` and `height` attributes in our markup are now meaningful again. The browser uses them to calculate the *aspect ratio* of an image at the same time it requests the image, so it can ensure the correct amount of space is left ahead of the image arriving from the server.

What's more, if the CSS is telling the image to be `width: 100%;` or `max-width: 100%;`, it knows the width, which allows it to set a height using the aspect ratio it has gleaned from the `width` and `height` in the markup.

So you can carry on reading that paragraph under it the image---or even click that link!---without worrying about the contents jumping down.

{% set youtubeVideoTitle = "Image loading behaviour when aspect ratio is derived from size attributes (video)" %}
{% set youtubeVideoID = "iHCopqc8kWc" %}
{% include "youtube-embed.html" %}


## What our code should look like

So now we know what to do, how do we do it?

### HTML

Let's start with the markup. All you need to do is add a `width` and `height` to your images!

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

No need to anticipate the aspect ratio in your CSS!


## The limitations of Markdown

One thing to mention is that Markdown can't add image attributes so if you're adding images to a blog post written in Markdown, instead of `![My alt text](/my-great-image.jpg)` you'll have to use HTML: `<img src="/my-great-image.jpg" alt="My alt text" />`. That's fine as Markdown allows HTML to be mixed up in there.

I don't mind that at all partly because I'm not a big fan of the `![]()` notation for images (too close to the `[]()` used for links), but mainly as it means I can [throw a tactical `loading="lazy"` attribute on certain images](/blog/lazy-loading-images-without-javascript).


## Browser support

Firefox, Chrome (therefore Edge and Opera) support aspect ratios based on the `width` and `height` attributes today. Apple announced that Safari will support them when the next versions of Safari are released in (likely) September, but to get a sneak preview, [Safari Technology Preview](https://developer.apple.com/safari/technology-preview/) supports it now.

This is a progressive enhancement, so older browsers will just get the jank they always got. Not great, but not a worse experience than they were already getting.
