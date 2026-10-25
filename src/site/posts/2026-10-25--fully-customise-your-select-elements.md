---
title: Fully customise your select elements
intro: For years and years, developers have had to come up with all sorts of creative workarounds to style select elements, but now you can do it properly.
date: 2026-10-25
tags:
    - HTML
    - CSS
    - Accessibility
---

For years and years, developers have had to come up with all sorts of creative workarounds to style `<select>` dropdown menus.

I covered this a good while ago in [Form styling limitations are an accessibility issue](/blog/form-styling-limitations-are-an-accessibility-issue), and I can't tell you how excited I was when [Safari 27](https://webkit.org/blog/18325/webkit-features-for-safari-27-0/) included 'Customisable Select', following Chrome's lead from early last year.


## A false start

Some years ago, there was a proposal for a new [`<selectmenu>` HTML element](https://css-tricks.com/the-selectmenu-element/). That would have meant replacing existing `<select>`s to take advantage of it, and working out what to show people whose browsers didn't support the new element.

The approach we've ended up with builds on `<select>` instead, which makes things much simpler.


## Now we can do it properly

If you're already using `<select>` and have styled it as far as browsers allowed, there's just some CSS to rewrite. Now, browsers that support the new styling get your custom design; those that don't show their default `<select>`. A bit ugly, perhaps, but entirely usable.

Of course, developers who've needed fully custom-styled selects have long reached for `<div>`s, `<span>`s, JavaScript, and ARIA instead. If that's what you're working with, there's still some replacement work to do, just as there would have been with `<selectmenu>`. You'll need to swap the hand-built component for a `<select>`, then add your styles.

Either way, we now have a native HTML element we can style properly, with a fallback built in.

For reference, this is the markup at its simplest:

```html
<label for="fruit">What is your favourite fruit?</label>
<select id="fruit" name="fruit">
    <option disabled selected>Choose one</option>
    <option>Apple</option>
    <option>Banana</option>
    <option>Orange</option>
</select>
```

### A new normal

First, we need to opt into the new base styling. This applies to both the `<select>` button and its dropdown menu, which is called the 'picker':

```css
select,
::picker(select) {
  appearance: base-select
}
```

This gives us a more neutral starting point:

- Inherited fonts
- Simple borders
- A downward-pointing chevron/arrow on the button
- The picker positioned underneath the button

Some things look slightly different between browsers, but the good news is we can now override those styles easily.


### Styling the button

This is super straightforward. Just use whatever styles you like, for example:

```css
select {
  background-color: rebeccapurple;
  border: none;
  color: white
}
```

You can replace the chevron using the `::picker-icon` pseudo-element:

```css
select::picker-icon {
  content: "➡️"
}
```

And use the `:open` pseudo-class to change the icon when the picker is open:

```css
select:open::picker-icon {
  content: "⬇️"
}
```

### Styling the picker

And the picker is just as easy to style:

```css
::picker(select) {
  background-color: rebeccapurple;
  border: none;
  color: white
}
```

Now it matches its button! We can use `margin-top` to put a bit of distance between it and its trigger, add animations; whatever we like!

We can style the options using `option {}`, and target the checkmark with the `::checkmark` pseudo-element:

```css
option::checkmark {
  content: "👉";
}
```

Use `:checked` if you want to make the selected option stand out a bit more:

```css
option:checked {
  font-weight: bold;
}
```

You can go further by adding images and other markup to your options, and use [`<selectedcontent>`](https://developer.mozilla.org/en-US/docs/Web/HTML/Reference/Elements/selectedcontent) to display that richer content in the button too, but I'll stick with plain old text for now.


## Browser support

The features we've used to customise our `<select>`s ([`appearance: base-select`](https://caniuse.com/mdn-css_properties_appearance_base-select), [`::picker-icon`](https://caniuse.com/mdn-css_selectors_picker-icon), and [`::picker(select)`](https://caniuse.com/mdn-css_selectors_picker)) aren't supported in Firefox yet, but work in Chrome and Chromium-based browsers like Edge and Opera, as well as Safari 27.

At the time of writing, that covers around 72% of global browser usage. Safari 27 is still new, so there's plenty of room for that to grow as people update to the latest operating system; Apple appears to be [rolling out iOS 27 more slowly than usual](https://daringfireball.net/linked/2026/10/08/apple-is-slow-rolling-ios-27-adoption-so-far).

But we don't need to wait for every browser to catch up. Those that don't support the new styling show the unstyled `<select>`, so people can still choose an option and complete the form.


## Will I be using it?

I don't use `<select>` elements very much as `<input type="radio" />` is usually a better alternative, but, where a `<select>` is needed, I'll be using this new technique. It gives me control over the styling and falls back nicely if browser support isn't there.
