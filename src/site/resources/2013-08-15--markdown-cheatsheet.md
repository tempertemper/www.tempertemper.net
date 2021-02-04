---
title: Markdown cheatsheet
intro: |
    Markdown is a brilliant way to write content for the web. Here's a quick overview of the most commonly used Markdown, for your reference.
date: 2013-08-15
updated: 2019-06-24
tags:
    - Content
    - Markdown
---

Markdown is a [brilliant way to write content](/resources/what-is-markdown) for the web. Here's a quick overview of the most commonly used Markdown, for your reference.


## Let's start with headings

To make a heading, all you need to do is use # symbols before the heading itself, like this:

```markdown
# Level 1 heading

## Level 2 heading

### Level 3 heading

#### Level 4 heading

##### Level 5 heading

##### Level 6 heading
```

If you're like me and you want something that looks a little bit more like an actual heading, while you're writing your article, you can also pop a line of `=` or `-` symbols underneath the heading to make it a level 1 or 2 heading, like this:

```markdown
Level 1 heading
================

Level 2 heading
----------------

### Level 3 heading

#### Level 4 heading

##### Level 5 heading

##### Level 6 heading
```

My problem with doing it this way, aside from the lack of consistency, is that I can never remember which way round they are: using `#`s starts with one `#` and works up to six, but underlines start with two and goes *down* to one. Also, underlines shouldn't be used for anything other thank links on the web, so there's a bit of a disconnect.


## General in-paragraph highlighting

What about bold, italics and what-not? Easy! Here's how:

```markdown
Here’s some *italic text*.

Here’s some **bold text**.
```

Just wrap the word or phrase you want to italicise (emphasise) or embolden (strongly emphasise) in either single or double asterisks!

You can also use underscores (`_`) for italics if you prefer.


## Lists

### Bulleted lists

Bulleted lists are for when you have a list of items and there's no particular order to them. Just type a dash with a space between it and each item, like this:

```markdown
- an item
- another item
- yet another item
- one more item
```

Don't like dashes? You can also use plus signs (`+`) or asterisks (`*`). I used to use pluses as they stand out better, but I wrote an article on why I've [decided to switch to dashes](/blog/dashes-asterisks-and-plus-signs).

### Numbered list

If there's a definite order to your list items they should probably be numbered, rather than bulleted. All you do is type a number and a full stop, followed by a space and anything you write after that will be part of a numbered list:

```markdown
1. first list item
2. second list item
3. third list item
4. fourth list item
```


## Links

Links are a [hugely important part of the web](/resources/links-make-the-web-go-round). Here's how to link to other web pages in your articles:

```markdown
Sentences [containing links](https://twitter.com/tempertemper) are great.
```

Surround the text you want to be the link in square brackets and---without a space---write or paste the link to the page you're referencing in normal brackets. Piece of cake!

There's also a special way to [link to other pages in your site](/resources/how-to-write-a-link-using-markdown).


## Digging deep

If you really want to get into Markdown, a great place to start is with John Gruber's Daring Fireball website -- he's the guy who developed it and his [documentation](https://daringfireball.net/projects/markdown/) is extremely thorough!

And, by the way, in case you're curious, I write all of my blog posts in Markdown. Any questions, [just ask](https://twitter.com/tempertemper)!
