---
title: Styling list markers the right way
intro: |
    Simple list styling like changing the bullets' colour has always felt like a hack, involving a lot of CSS. But now there's a proper way to do it!
date: 2020-10-07
tags:
    - CSS
    - Development
summaryImage: large
---

Styling list markers has always been a bit of a fight. Even simple styling like changing the colour of the bullets/numbers, or making them bigger involved a fair bit of CSS.

In the early days, CSS offered us very little in the way of customisation, which was frustrating for a lot of designers. All we could do was change:

- the default filled-in bullet (`disc`) to a square (`square`) or hollow circle (`circle`)
- the numbers in an ordered list to Roman numerals or alphabetical ('a', 'b', 'c'), either uppercase or lowercase

Later, we were able to replace the default characters, but as ever, for lack of the right tools, we resorted to a hack to make those designs happen.


## The way we've been doing it

For a long time, styling lists by replacing the default markers with custom `::before` pseudo elements is the way we got those bullets and numbers looking fancy.

First we'd get rid of the default list styling and add the custom bullets back in. Here I'm just using a ASCII circle character (`\25CF`) as the marker, but you could use any ASCII character you like:

```css
ul,
ol {
  list-style: none;
  padding-left: 0;
}

li {
  padding-left: 1.5em;
  position: relative;
}

li::before {
  content: "\25CF";
  color: green;
  position: absolute;
  left: 0;
}
```

But that means ordered lists will have a bullets too, which isn't what we want. Building on the previous CSS, we'd give `<ol>`s a number by adding the following:

```css
ol {
  counter-reset: item;
}

ol li::before {
  content: counter(item) "\002E";
  counter-increment: item;
}
```

Note: `"\002E"` adds a full stop after the number to replicate the default styling, but, again, you can add any ASCII character you like here.


## The way we can do it now

That's a *lot* of code when you compare it to how we can do it now, using the relatively new `::marker` pseudo element:

```css
li::marker {
  color: green;
}
```

Easy to write, easy to read, and does exactly the same thing; making list markers `green`, but without:

- removing and replacing the default spacing
- removing and replacing the marker
- the fancy CSS counter for ordered lists

You can style the marker any way you'd style ordinary text, so there's loads of control. And, of course, you've still got access to the `list-style-type`, so you can make the bullet a square or count in Roman numerals.


## Browser support is good

At the time of writing, this new technique to style list markers works beautifully in Firefox and Safari. It isn't supported in Chrome, Edge or Opera yet, but the **good news** is that [it's in the next version of Chrome](https://caniuse.com/css-marker-pseudo) (version 86), and that means that support in Edge and Opera won't be far away, since they both piggy-back on the same browser engine as Chrome.
