---
title: Using light-dark() without getting caught out
date: 2026-09-28
intro: The `light-dark()` CSS function makes defining colour schemes tidier, but older browsers need some care. Here's how I've handled the fallbacks.
tags:
    - CSS
---

It's important that we [respect our users' preferences](/blog/respecting-your-users-preferences), which includes their choice of Light or Dark Mode. Recently, I've been looking at a tidier way to do that in CSS.

In 2024-ish, the `light-dark()` CSS function quietly became available in most modern browsers. It caught my attention as it's a really efficient way of defining Dark Mode colours without using media queries (and [SCSS mixins](/blog/sass-mixins-for-increased-contrast-mode-and-dark-mode)).

This is how I've been tackling Dark Mode, using a media query:

```css
color: black;
background-color: white;

@media (prefers-color-scheme: dark) {
  color: white;
  background-color: black;
}
```

That would produce black text on a white background for Light Mode, and white text on a black background for Dark Mode.

The `light-dark()` function tidies that up a wee bit:

```css
color: light-dark(black, white);
background-color: light-dark(white, black);
```


## First things first

Before `light-dark()` can do its thing, you have to tell the browser which colour schemes your website supports. You can do this in one of two ways; first, in the `<head>` of your HTML using:

```html
<meta name="color-scheme" content="light dark">
```

This allows the browser to use dark defaults for the colours and interface elements it controls when the user has Dark Mode turned on. I often use this for CSS-light demo sites, like my [HTML Playground](https://playground.tempertemper.net).

Alternatively, you can set your colour scheme in CSS using the `:root` pseudo-class:

```css
:root {
  color-scheme: light dark;
}
```

In most cases, CSS is probably the way to go. More on that later!


## Making it safe

With the media query approach, if the browser doesn't support `prefers-color-scheme`, it just uses the Light Mode colours, but `light-dark()` makes things a bit fiddlier.

The [browser support for `light-dark()`](https://caniuse.com/wf-light-dark) is pretty good, sitting at just under 90% at the time of writing. But it's not quite close enough to 100% for me to feel comfortable using it without a fallback.

My hope-against-hope was that if the browser didn't understand `light-dark()` it would:

1. Read the values inside the function
2. Apply the first
3. Override it with the second

Let's have another look at that earlier example:

```css
color: light-dark(black, white);
background-color: light-dark(white, black);
```

In my head, this would have produced white text on a black background, since `white` and `black` were the latter values in `color` and `background-color` respectively, but CSS functions don't work like that.

The browser skips the function altogether if it doesn't understand it, so in our example the browser would fail to define values for `color` and `background-color` and just use its default text and background colours.

### Defining a fallback

The best way to reduce the risk is to define a fallback colour before each function:

```css
color: black;
color: light-dark(black, white);

background-color: white;
background-color: light-dark(white, black);
```

Here, browsers that don't support `light-dark()` would end up with black text on a white background:

1. They apply the first colour declaration
2. They ignore the second declaration because they don't know what to do with `light-dark()`

### Or not defining a fallback

Sometimes there's no need for a fallback. Slightly riskier, but it could be worthwhile if you:

- want to minimise repeated colour declarations
- don't mind how the default browser colours look, for browsers that don't support `light-dark()`

Here's an example, using some colours from my website:

```css
color: light-dark(#0b0c0c, #f2f2f2);
background-color: light-dark(#fff, #262626);
```

In Light Mode, this would be dark grey text (`#0b0c0c`) on a white background (`#fff`); in Dark Mode it would be very light grey (`#f2f2f2`) on a dark grey background (`#262626`).

Without fallbacks, browsers that don't support `light-dark()` typically use black text on a white background in Light Mode, and white text on a dark grey background in Dark Mode. They're not the exact colours I'd want, but my website looks fine.

Again, it's risky, so test thoroughly.

### Implementation timeline issues

The actual implementation I've used on my own website is one step further than I covered earlier. I've combined the two approaches, falling back on browser defaults where possible, and using the double colour declaration technique where necessary. My code looks more like this:

```css
color: #0b0c0c;
color: light-dark(#0b0c0c, #f2f2f2);
background-color: light-dark(#fff, #262626);
```

What I'm doing here is saying:

- Use slightly off-black text on a white background in Light Mode
- Use off-white text on a dark grey background in Dark Mode
- Keep the off-black text as a fallback if `light-dark()` isn't supported

The reason I'm doing this is the off-black text colour. High-contrast black text on a white background can cause [visual stress for some dyslexic readers](https://business.scope.org.uk/how-to-write-better-website-content-for-people-with-dyslexia/#Use_dyslexia_friendly_colours), so I'd prefer to keep my chosen very dark grey text colour even when `light-dark()` isn't supported.

But that exposes a new gotcha:

- There was a two-and-a-half year gap between `color-scheme` getting solid support (in early 2022) and `light-dark()` (mid 2024)
- Even to this day, some browsers (for example Opera Mini) support `color-scheme` but not `light-dark()`

That leaves some visitors with a support mismatch: their browser understands `color-scheme` but not `light-dark()`. In my example, visitors with that mismatch who prefer Dark Mode would see my dark grey fallback text on the browser's default dark background.

To avoid the grey-on-grey issue, we can replace either of the earlier colour-scheme setups with this conditional CSS declaration:

```css
@supports (color: light-dark(black, white)) {
  :root {
    color-scheme: light dark;
  }
}
```

Now the website only enables both colour schemes when the browser also supports `light-dark()`. Browsers with the support mismatch skip this declaration, leaving my fallback text colour on the browser's normal light background.


## Is it worth the bother?

`light-dark()` is tidier than defining a colour and, in my opinion, more readable than using a media query, but we're designing for users, not developers, so is there a user-facing benefit?

Yes. Sort of.

When I changed my codebase to use `light-dark()`, the rendered CSS was smaller, but the compressed file size was actually ever-so-slightly larger. Fewer lines of text don't necessarily translate to better compression; the previous media query-heavy CSS squashed down better. But the increase in compressed file size wasn't large enough to cause any real concern (less than 150 bytes), so there's still no meaningful improvement for visitors.

Overall, the differences are pretty small:

<section class="table-wrapper" aria-labelledby="css-comparison-caption" tabindex="0">
    <table>
        <caption id="css-comparison-caption">Changes with <code>light-dark()</code></caption>
        <thead>
            <tr>
                <th scope="col">Metric</th>
                <th scope="col">Change</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Generated CSS</td>
                <td>−0.923 kB</td>
            </tr>
            <tr>
                <td>Compressed transfer: Gzip</td>
                <td>+149 bytes</td>
            </tr>
            <tr>
                <td>Compressed transfer: Brotli</td>
                <td>+132 bytes</td>
            </tr>
            <tr>
                <td>Parsing</td>
                <td>−0.156 ms</td>
            </tr>
        </tbody>
    </table>
</section>

Ultimately, `light-dark()` won't make a meaningful difference to performance, but it does make colour-scheme styles shorter and easier to understand. With sensible fallbacks where they matter, that improvement in maintainability is enough to make it worthwhile for me.
