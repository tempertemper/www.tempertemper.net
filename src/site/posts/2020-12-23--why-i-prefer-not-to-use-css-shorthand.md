---
title: Why I prefer not to use CSS shorthand
intro: |
    Instead of using the `padding` declaration, I use the longhand `padding-top`, `padding-right`, and so on. Why? A few of good reasons: let me explain.
date: 2020-12-23
tags:
    - CSS
---

I like to write my CSS declarations out longhand. So, for example, if I want to add some padding to an element, instead of:

```css
padding: 1em 0.5em 1em 0.5em;
```

Or the even shorter:

```css
padding: 1em 0.5em;
```

I prefer to use:

```css
padding-top: 1em;
padding-right: 0.5em;
padding-bottom: 1em;
padding-left: 0.5em;
```

Seems like a lot of extra typing, but I have some good reasons.


## Longhand is easier to understand

Although it takes me longer to write, anyone skim-reading my code will know at a glance exactly what I intended. No need for the user to do any calculation:

- "Ok, the shorthand starts from 'top', so the first of the four values is `padding-top`, then it goes clockwise…"
- "Just two values? Better just do a quick search to check which does what."
- "*Three* values!? Is that even possible?" (It is: `padding: 1em 0.5em 1em;` would achieve the same as the code above)

My longhand code isn't trying to cut corners or be clever, which means it's more self-documenting; all the information is all just *there*.


## Shorthand syntax can get complicated

Speaking of corners, did you know that we're not limited to perfectly circular rounded corners? We can make them more squished and oval if we prefer:

```css
border-radius: 2em / 1em;
```

In this example, our rectangle would have rounded corners that are flatter on the top and bottom than on the sides, and the syntax uses two values to set first the width of the oval, then the height.

But I'd be concerned that many developers may be unfamiliar with this syntax with the forward slash, and might need to search the web for documentation.

Taking it a bit further, if (and I know this pretty unlikely) we want to get more individual with each of our corners, we can use the same kind of shorthand syntax we used for padding, adding values before and after the forward slash:

```css
border-radius: 0.5em 1em 2em 0.75em / 1em 2em 3em 0.5em;
```

Here, the first value in the list before the forward slash maps to the first after it, the second to the second, and so on. This disconnection between the pairs is a fiddle, and if a *different* shorthand is used either side of the forward slash, which is perfectly valid, things could get even more complicated to read:

```css
border-radius: 0.5em 1em 2em 0.75em / 1em 3em 0.5em;
```

The longhand, however, is much easier to read; even the the order of the words in the declaration and the values match:

```css
border-top-right-radius: 2em 1em;
border-bottom-right-radius: 2em 1em;
border-bottom-left-radius: 2em 1em;
border-top-left-radius: 2em 1em;
```

And it's just as easier to understand in that unlikely scenario that we want to use a whole bunch of different values for each corner:

```css
border-top-right-radius: 1em 2em;
border-bottom-right-radius: 2em 3em;
border-bottom-left-radius: 0.75em 0.5em;
border-top-left-radius: 0.5 1em;
```

Another point of confusion is that, padding, margin and border shorthands all start at the top and go clockwise, but with `border-radius` we're talking about *diagonals*, so do the values start with the top-right (nope) or top-left (yep)? Some searching would probably be needed to check.

So when rounded corners go beyond the simple, it's much more straightforward to use the individual values than the shorthand.

It's also possible to [accidentally reset a longhand border radius](https://developer.mozilla.org/en-US/docs/Web/CSS/border-top-right-radius) with a subsequent shorthand declaration, as MDN Web Docs explains:

> As with any shorthand property, individual sub-properties cannot inherit, such as in `border-radius: 0 0 inherit inherit;`, which would partially override existing definitions


## Shorthand can break things

Inadvertent overriding goes further than just lack of inheritance, though, as Harry Roberts explains in his [CSS Shorthand Syntax Considered an Anti-Pattern](https://csswizardry.com/2016/12/css-shorthand-syntax-considered-an-anti-pattern/) article, where he says:

> [shorthand] often unsets other properties that we never intended to modify

Harry gives `background` as an example, where using `background: red;` will do more than just give you a red background; it'll reset *every other* background property.

By using the much more specific, longhand `background-color: red;` we avoid inadvertently resetting the `background-image`, `background-size`, etc.


## Shorthand can start a specificity tangle

Harry Roberts also gives a great example of using the following code to centre something horizontally on the page:

```css
margin: 0 auto;
```

Along with centering the element, this removes any default top and bottom margin. You could throw something like `1em` in there, instead of the `0`, but the point is that a blanket statement like this means we're almost certainly going to have to use some `margin-top` and `margin-bottom` overrides somewhere down the line.

So we're not really saving any code, and at the same time adding weight to our CSS: the explicit top and bottom margins are also more *specific* than the user agent defaults, so we'd have to do more work to override those styles.

The cleanest and least-problematic way to centre the element would be to forget shorthand and only use left and right margins, leaving the top and bottom separate:

```css
margin-right: auto;
margin-left: auto;
```


## Good habits

I'm all about creating good habits, and avoiding shorthand in CSS has lots of advantages, even if it's a wee bit more verbose.
