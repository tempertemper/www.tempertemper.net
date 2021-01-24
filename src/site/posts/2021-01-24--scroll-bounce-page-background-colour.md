---
title: Scroll-bounce page background colour
intro: |
    In most browsers, if you scroll and hit the top or bottom of the page, there's a bounce. Did you know you can change the colour behind your page?
date: 2021-01-24
tags:
    - CSS
---

There's a nice feature in Safari and Chrome (and any Chromium-based browser, like Edge and Opera) where, if you scroll up or down the page and hit the top or bottom, there's a satisfying bounce, rather than an abrupt stop.

The bounce reveals a white background behind the main page canvas, but that's a bit boring so let's have a look at adding our own colourful flourish to our visitors scrolling.

I'd love to tell you it was as simple as this:

```css
html {
  background-color: blue;
}

body {
  background-color: white;
}
```

Actually, dealing with it cleanly in the CSS like this, with no extra HTML works well Chromium-based browsers, but you're going to want the same effect on Safari so that [everyone using iOS](https://www.howtogeek.com/184283/why-third-party-browsers-will-always-be-inferior-to-safari-on-iphone-and-ipad/) can enjoy your `blue` background too.

On Safari (on macOS and iOS), if the `<body>` element has a `background-color`, *that's* what's seen on the bounce, so in the above example, the `background-color` of the `<body>` is both the canvas *and* its background. The blue background of the `<html>` is ignored. Annoying.

So we need a separate canvas element that sits on top of both `<html>` and `<body>`:

```html
<!doctype html>
<html lang="en">
    <head>
        <!-- All the page meta data goes here -->
    </head>
    <body>
        <div class="canvas">
            <!-- All the page content data goes here -->
        </div>
    </body>
</html>
```

And the CSS will look like this:

```css
html {
  background-color: blue;
}

.canvas {
  background-color: white;
}
```

Now, with a little extra HTML, anyone using Chrome or Safari will see a white page with a hidden blue background that peeks out when they scroll to the top or bottom of the page.
