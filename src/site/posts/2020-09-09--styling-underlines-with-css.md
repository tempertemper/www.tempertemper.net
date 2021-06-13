---
title: Styling underlines with CSS
intro: |
    Never mind `border-bottom` for making your links a bit more visually engaging, here's how to do it properly with `text-decoration`.
date: 2020-09-09
tags:
    - CSS
    - Development
    - Design
summaryImage: large
---


If you're not underlining your links, [you should be](/blog/why-you-should-almost-always-underline-your-links). There're a couple of ways to underline links; `text-decoration: underline` is the most obvious as that's how links are styled by default, and `border-bottom` offers us a bit more flexibility. Using borders, it's possible to specify the thickness, style and colour, but did you know it's now possible to do that with `text-decoration: underline`?

Why bother though, if it's working for us already with borders? Well, `text-decoration: underline` is better *typographically* as the underlines sit nicely on the baseline of words, passing through descenders (the bits of letters that go below the baseline in letters like `g`, `p`, and `y`), whereas `border-bottom` underlines sit *under* the descenders, which means there's too much distance between the words and their underline when.

Not only that, but browsers now detect descenders and the `text-decoration` underline neatly skips them by default. This can be undone with [`text-decoration-skip-ink`](https://developer.mozilla.org/en-US/docs/Web/CSS/text-decoration-skip-ink) (or [`text-decoration-skip`](https://developer.mozilla.org/en-US/docs/Web/CSS/text-decoration-skip) on Safari), but I can't see what that would gain, which is why it's 'on' by default.

So how do we get that visual flair with `text-decoration: underline` that we've always had via `border-bottom`?


## Underline, overline

We're talking about underlines here, but it's worth prefacing this with a quick tangent: [`text-decoration-line`](https://developer.mozilla.org/en-US/docs/Web/CSS/text-decoration-line), just like  `text-decoration`, has 3 positions:

- Under the text (`underline`)
- Through the text (`line-through`)
- Over the text (`overline`)

`text-decoration-line` also allows multiple values, so we can have lines under, through and above the same block of text. A bit weird, but it's there:

```css
text-decoration: underline;
text-decoration-line: underline overline;
```


## What the underline looks like

Back to underlines. We now have control of how they look, via [`text-decoration-style`](https://developer.mozilla.org/en-US/docs/Web/CSS/text-decoration-style); there are 5 options:

- `dashed`
- `double`
- `dotted`
- `solid`
- `wavy`

They each look just as you'd expect, with a double line, wavy line, etc. Just add the `text-decoration-style` declaration:

```css
text-decoration: underline;
text-decoration-style: wavy;
```


## The underline colour

This one's great. [`text-decoration-color`](https://developer.mozilla.org/en-US/docs/Web/CSS/text-decoration-color) lets us change the colour of the underline. It accepts any normal CSS colour value, whether a keyword, hexadecimal, RGB, RGBA, HSL, etc.:

```css
text-decoration: underline;
text-decoration-color: red;
```

Just be sure that the underline [passes the colour contrast ratio](https://webaim.org/resources/contrastchecker/) you're aiming for!


## Thickening up those underlines

Finally, we can control how thick the underline is with [`text-decoration-thickness`](https://developer.mozilla.org/en-US/docs/Web/CSS/text-decoration-thickness), for example:

```css
text-decoration: underline;
text-decoration-thickness: 5px;
```


## Combinations

Of course, we can do lots at the same time:

```css
text-decoration: underline;
text-decoration-style: wavy;
text-decoration-color: red;
text-decoration-thickness: 5px;
```

Or we can use `text-decoration-line` instead of `text-decoration`:

```css
text-decoration-line: underline;
text-decoration-style: wavy;
text-decoration-color: red;
text-decoration-thickness: 5px;
```


## Shorthand

That's turning into a lot of CSS! Luckily, like margin, padding, border radius and loads of other CSS properties, [`text-decoration` is now a shorthand](https://caniuse.com/#feat=mdn-css_properties_text-decoration_shorthand) where we can declare multiple values:

```css
text-decoration: underline wavy red;
```

Notice I haven't added the `text-decoration-thickness` property in that example. There's a good reason for that: browser support.


## A progressive enhancement

Underline styling is well supported across all modern browsers. Older versions (and Internet Explorer 11 and below, of course) should get a bog standard but still very user-friendly underline, as long as there's nothing like this in your CSS reset:

```css
a {
  text-decoration: none;
}
```

But keep in mind, browser support for varies depending on:

- the property you use
- the values in the property

### Prefixes needed for shorthand

If you're using shorthand, you'll need vendor prefixes for Safari to do your bidding:

```css
text-decoration: underline wavy red;
-webkit-text-decoration: underline wavy red;
```

Easy enough to do if you're automating things with [Autoprefixer](https://github.com/postcss/autoprefixer), and not too much extra work if you're writing it by hand. Again, browsers that don't support `text-decoration` shorthand should just fall back to that default underline.

### `text-decoration-thickness` isn't as well supported as the others

The browser support for `text-decoration-line`, `text-decoration-style` and `text-decoration-color` is pretty good. Each browser implemented all three at the same time, but controlling an underline's *thickness* was introduced much later. In fact, [only Safari and Firefox currently support it](https://caniuse.com/#search=text-decoration-thickness), so we're still waiting on Chrome (and therefore Opera and Edge).

What's more, [Firefox is currently the *only* browser to support thickness in the shorthand](https://caniuse.com/#feat=mdn-css_properties_text-decoration_text-decoration-thickness), so if you want to use thickness in your shorthand you'll need to do the classic double-declaration so that other browsers get a value they understand:

```css
text-decoration: underline solid red;
text-decoration: underline solid red 5px;
```

### `text-decoration-thickness` and percentages don't play nicely

Also, [be careful using percentage values for thickness](https://caniuse.com/#feat=mdn-css_properties_text-decoration-thickness_percentage), as it's only Firefox that supports them.

The good news is that percentage values are calculated as a percentage of 1em, so `text-decoration-thickness: 0.1em` is the same as `text-decoration-thickness: 10%`, so you might as well stick to `em`s as they do effectively the same thing.

There are always pixels (`px`), but it's nicer typographically to keep the thickness of the line relative to the text it's underlining, so if the user increases the size of the text on their screen the underline increases proportionally.


## `text-decoration-thickness` messiness

Be careful when pairing `text-decoration-style` with `text-decoration-thickness`. Anything other than the default `solid` line (and maybe `double` line) can look messy when the thickness is set too high. Those `wavy`, `dashed` and `dotted` almost always usually cut off in untidy places when they skip descenders, and at the end of a word.


## Usability considerations

As with most CSS, remember: [with great power comes great responsibility](https://en.wikipedia.org/wiki/With_great_power_comes_great_responsibility), and from a usability point of view, I'd be wary of using anything other than a solid underline to style a link.

So you're probably fine if you're after:

- a different coloured underline
- a progressively enhanced thick underline
- maybe even a double underline

But be careful with everything else:

- `text-decoration-line: line-through` is basically how `<del>` elements look, with a strikethrough
- `text-decoration-line: overline` looks weird and could make the text above the link look like the link instead
- `wavy`, `dotted` or `dashed` underlines could look like spelling or grammatical errors in a word processing document, rather than links

Where I think `wavy`, `dotted` or `dashed` could work nicely, though, is for hover states.


## What I would do

Shorthand comes with vendor prefixes and dodgy double-declarations, so I'd start with the classic `text-decoration: underline;` and enhance it. I'd be inclined to avoid anything other than a straightforward underline, which users know well, so `text-decoration-line` is out; which leaves me with a splash of colour and a nice `em`-based thickness for non-Chromium browsers:

```css
a {
  text-decoration: underline;
  text-decoration-color: red;
  text-decoration-thickness: 0.1em;
}
```
