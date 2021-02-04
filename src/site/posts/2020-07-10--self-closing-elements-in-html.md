---
title: Self-closing elements in HTML
intro: |
    Some elements don't look like the others; those are self-closing elements, which are just an opening tag with no content and no closing tag.
date: 2020-07-10
tags:
    - Development
    - HTML
---

I covered the most common type of element in my [article on tags versus elements](/blog/the-difference-between-elements-and-tags-in-html), where an element is made up of:

- an opening tag
- a closing tag
- all of the content in between

But I'm sure you've used an image before, and they don't look like that! This is where the distinction between tags and elements gets a bit blurry -- some elements *are* tags!

An image is just an opening tag. No closing tag; no content inside. This type of element is known as 'self-closing' (or sometimes 'void') and look like this:

```html
<img src="/img/my-great-image.jpg" alt="My alt text" />
```

So they're just a tag with some [attributes](/blog/an-introduction-to-html-attributes) and a forward slash right before the end.

It's not just images. You'll have used plenty of `<input />` elements, I'm sure. And up there in your document's `<head>`, there're probably plenty of `<meta />` elements and the odd `<link />`. You might even use `<br />` and `<hr />` every now and again (although, I have opinions on break tags and [horizontal rules](/blog/using-horizontal-rules-in-html)).


## Optional, but highly recommended, trailing slash

I should mention that the `/` at the end is optional (well, it is in HTML5). You can write your image element image without one, like this:

```html
<img src="/img/my-great-image.jpg" alt="My alt text">
```

But I always use the 'trailing' slash. It provides a **clear marker** that this element won't contain content or have a closing tag. There's no need to look for where the element ends, because it doesn't, and I find the distinction makes debugging easier.
