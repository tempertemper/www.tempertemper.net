---
title: An introduction to HTML attributes
intro: |
    An HTML attribute lives on the opening tag of an element and gives that element powers it might not otherwise have had.
date: 2020-07-09
tags:
    - Development
    - HTML
---

So now you know [what HTML elements and tags are](/blog/the-difference-between-elements-and-tags-in-html). The next bit of HTML anatomy I'd like to introduce you to are attributes.

An attribute lives on an element's opening tag and usually involves a <i>key</i> and a <i>value</i>. Let me show you:

```html
<div class="boxout">
```

Here, `class` is the key and `boxout` is the value.

If the value is a single word we can simplify this to:

```html
<div class=boxout>
```

But I prefer to keep things consistent, so I always use straight double quotes (`"`) so that every attribute is easily identifiable.

You can also use straight single quotes (`'`), but hardly anyone does, so if you're sharing a codebase or might ever work with anyone else in the future you'll probably want to go with what's standard practice and use double quotes.


## Multiple values

If we use quotes, our values can include more than one thing, which is common with classes:

```html
<div class="boxout highlight some-other-thing and-another">
```

It's worth mentioning two things here:

- multiple classes are added not by using many `class` attributes, but many values in a single `class` attribute (if you were to use multiple `class` attributes, the browser uses the *last* one and ignores the ones that came before)
- attribute values are separated using spaces; hyphens (`-`), underscores (`_`), etc. all count as part of a word


## Multiple attributes

Of course, we're not limited to just one attribute per tag -- we can use multiple! Just separate them with spaces:

```html
<div id="boxout" class="boxout">
```

There are lots that I prefer not to list here as they:

- should be used with caution (like `role=""` and `tabindex=""`)
- would need a blog post all of their own (e.g. `data-something=""` and `itemprop=""`)
- are best avoided (like `style=""`)
- can be problematic for accessibility (like `title=""`)

And there are a million more (well, maybe not quite a *million*) that are used in conjunction with specific elements, for example:

- `href=""` on an `<a>`
- `alt=""`, `src=""`, `width=""` and `height=""` are used with an `<img />` element
- `type=""`, `name=""`, `inputmode=""`, `required` and lots more are specific to `<input />` elements
- `for=""` to associate a form `<label>` with its `<input />`
- `reversed` and `start=""` with `<ol>`s
- `colspan=""` and `rowspan=""` on `<th>` and `<td>`s


## Booleans

Did you spot the odd ones out in that last list? That's right: I didn't put values (`=""`) on `required` and `reversed`. That's because they're what's known as <i>boolean</i> attributes.

That's when an attribute represents a toggle -- if it exists, it's true; if it doesn't, it's false:

```html
<form novalidate="true">
```

Here, we're telling the form not to validate any of its inputs -- let the user put a phone number in the email field, or leave the name input empty; we'll validate it all on the backend.

Of course, we can also write it without quotes:

```html
<form novalidate=true>
```

We can even get daft:

```html
<form novalidate="bananas">
```

`bananas` isn't a real value, but it shows that if a boolean attribute *exists*, it returns 'true'. In fact we can go one step further, like the `required` and `reversed` examples above:

```html
<form novalidate>
```

Because the attribute exists, it's counted as being true. In order to make the form validate on the frontend in this example, we'd simply omit the `novalidate` attribute. Again, if it's there, it's true, if it's not, it's false.

And this is where my consistency goes out the window! I don't use the `="true"` value with booleans, preferring to leave the 'naked' key on there.


## So what are attributes for?

As you've seen, an attribute extends a simple element. They can:

- be used as a unique identifier, like an `id=""`
- be used to apply styling, like a `class=""`
- be used to alter or refine the semantics, like `reversed` on a `<ol>`
- disable default browser behaviour, like `novalidate`
- enable built-in browser behaviour, like `required`
- reference other files, like `href=""`
- enhance accessibility (things like the `alt=""` text to describe images)
- change the semantics of elements (`role=""`)
- improve (or break, if you're not careful!) things for visually impaired users with [ARIA](https://developer.mozilla.org/en-US/docs/Web/Accessibility/ARIA)
- help touch screen users with `type=""` and `inputmode=""`
- associate two elements (`for=""` and `id=""` on form fields)

…and lots more besides.
