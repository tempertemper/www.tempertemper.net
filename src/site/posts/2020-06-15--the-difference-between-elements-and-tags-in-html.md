---
title: The difference between elements and tags in HTML
intro: |
    People often refer to HTML 'tags' and 'elements' interchangeably. They're related, but very much different things. Here's the deal.
date: 2020-06-15
tags:
    - Development
---

People often refer to HTML 'tags' and 'elements' interchangeably, but *there is a distinction*. That distinction has been really important to me as I've given talks on HTML within UK Government over the last year or so.

So what's the deal?


## Tags

Tags are the bits that are encapsulated in those angular brackets (which are basically 'less than' and 'greater than' symbols): `<` and `>`.

So this is a tag:

```html
<blockquote>
```

This is also a tag:

```html
</blockquote>
```

The first is an *opening* tag, the latter, with forward slash before the word, a *closing* tag.


## Elements

On the other hand, this [excellent quote from Steve Jobs](https://www.nytimes.com/2003/11/30/magazine/the-guts-of-a-new-machine.html) is an element:

```html
<blockquote>Design is not just what it looks like and feels like. Design is how it works.</blockquote>
```

An element (usually) consists of two tags: the opening tag and the closing tag. It starts with the opening tag, continues until it's closed, and consists of everything in between, including other elements:

```html
<blockquote>
    <p>Design is not just what it looks like and feels like. Design is how it <em>works</em>.</p>
</blockquote>
```

In that example, the `<blockquote>` element contains a paragraph of text with an emphasised word, but it can be a lot, lot more -- think of the `<html>` element, which contains an entire webpage and all of its behind-the-scenes metadata!

So if you're ever talking to someone about an HTML element, you're talking about *the whole thing*, where if you're talking bout a tag, you're referring to just the bits where an element begins and ends.
