---
title: Reversing an ordered list in HTML
intro: |
    When the order of a list matters, you might find yourself in a situation where you need to reverse the order. Fortunately, you can do that with HTML.
date: 2020-07-14
tags:
    - Development
    - HTML
---

There's not much you can do with an unordered list, but when the order *matters*, you might find yourself in a situation where you need to reverse the order. And that's where the `reversed` [HTML attribute](/blog/an-introduction-to-html-attributes) comes in.

Lists count up from 1 by default: 1, 2, 3… But what if it makes more sense to count *down*? 3, 2, 1…


## A couple of examples

This would make sense for countdowns:

```html
<p>It's time for my 'favourite colours' countdown!</p>
<ol reversed>
    <li>Hanging on at number 3, it's purple</li>
    <li>Straight in at number 2: green</li>
    <li>And at number one, my favourite colour of them all, blue!</li>
</ol>
```

A reversed list (in my opinion) also makes sense for lists of **blog posts**.

Blog posts are almost always listed by date, so should be presented in an ordered list. But the post at the top of the list, the first one you come to, isn't the first post; it's the *last* (or most recent) post! So if the last post is number 1, we have an issue!

With a straight `<ol>` this would make that latest post number 1 and the first post would be all the way at the end of the list and would have the highest number

To fix this, a reverse-chronological list calls for a `reversed` attribute so that it starts with the highest number and works backwards to the end of the list, where you'll find the first post at number 1.


## Screen readers

You may or may not show the list markers to your user, but they'll probably be picked up by software like screen readers. A normal ordered list reads out like this in a screen reader:

> List 3 items.
>
> 1 Blue, 1 of 3.
>
> 2 Green, 2 of 3.
>
> 3 Purple, 3 of 3.

But if we prefer a countdown, like in the earlier example, we'd use the `reversed` attribute and reorder the `<li>`s to match reads, which reads like this:

> List 3 items.
>
> 3 Purple, 1 of 3.
>
> 2 Green, 2 of 3.
>
> 1 Blue, 3 of 3.

Perfect.
