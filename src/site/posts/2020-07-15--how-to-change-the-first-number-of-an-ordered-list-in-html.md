---
title: How to change the first number of an ordered list in HTML
intro: |
    What happens when starting an ordered list at 1 doesn't make sense? HTML has an attribute that lets you start your count at any number!
date: 2020-07-15
tags:
    - Development
    - HTML
---

So we know [how to reverse the order of a list](reversing-an-ordered-list-in-html), but there's another thing you can do with ordered lists in HTML: change the starting number of the list.

A list that starts at a number other than the lowest (or the highest if the list is reversed) might not be something you need very often, but it's good to know the option's there.


## A couple of examples

If you want to break your list up with content that isn't related to the list itself you can do this:

```html
<p>My favourite colours are</p>
<ol>
    <li>Blue</li>
    <li>Green</li>
</ol>
<p>And, don’t forget</p>
<ol start="3">
    <li>Purple</li>
</ol>
```

`<p>And, don’t forget</p>` doesn't belong in the list as it's not one of my favourite colours, but it does help create a conversational tone, if that's what I was after.

You might also begin a list by introducing the first item in the preceding paragraph, letting the list would cover the remaining items:

```html
<p>Everyone knows that blue is my favourite colour; here are the runners-up:</p>
<ol start="2">
    <li>Green</li>
    <li>Purple</li>
</ol>
```


## Screen readers

As with a reversed list, you might not display the list markers visually, but screen readers should pick them up. Using the same example from yesterday's article, a straightforward ordered list reads out like this:

> List 3 items.
>
> 1 Blue, 1 of 3.
>
> 2 Green, 2 of 3.
>
> 3 Purple, 3 of 3.

Starting the list at 2 and removing the first item would read like this:

> List 2 items.
>
> 2 Green, 1 of 2.
>
> 3 Purple, 2 of 2.

And if we want to get funky, we can even specify the start number of a reversed list (we'd use `start="3"`):

> List 2 items.
>
> 3 Purple, 1 of 2.
>
> 2 Green, 2 of 2.
