---
title: How to write a link using Markdown
intro: |
    It's all very well knowing the principles behind writing a link, but how do you actually write a one in your blog or other webpage?
date: 2013-11-04
tags:
    - Content
    - Markdown
---

It's all very well knowing the [principles behind writing a link](/resources/how-to-write-a-link), but how do you _actually_ write a link?

Copying and pasting a link into your text will just write link as another word in your article. I'll use that last link as an example-- if I copy and paste it into this paragraph it looks like this: /resources/how-to-write-a-link

That's better than nothing as your visitors can highlight it, copy it and paste it into their web browser, but that's pretty clunky. We all know that links on the web look like text but there is something that sets them apart from the rest of the text that indicates that they're clickable/tappable and will take you somewhere else.


## How do I create a link then?

If you know me, you'll know I've got a [bit of a thing for Markdown](/resources/what-is-markdown), so I'll be using that as an example of how to create your links. The principles hold true, regardless of what type of text editor you're using to write your website text, so hold on in there if Markdown sounds like some kind of witchcraft!

A link is made up of two main parts:

1. The text that your visitors will read
2. The address of the page that you want your visitors go to

These two parts are written in two sets of brackets-- the first in square brackets [like this] and the second in normal brackets (like this). Oh, and the two sets of brackets should touch one another like this:

```markdown
[text](link)
```


## A neat little trick

The web was put together by extremely clever people and they built in some neat tricks to make linking even easier!

If you're linking to a page **on your own website** you don't need to type the full website address in your link text. This makes it much easier when typing links yourself.

Instead, just type a / (forward slash) at the beginning of your link, so a link to your homepage would just look something like this:

```markdown
[homepage](/)
```

So if you were linking to your contact page it would look like this:

```markdown
[contact page](/contact)
```

Links to pages within a particular section of your site often have a couple of forward slashes, for example:

```
https://www.my-great-website.com/blog/my-great-article
```

and all you have to do here type the bit just after your main website address, leaving something like:

```
/blog/my-great-article
```

Of course, if the address of the article is quite long, it's probably going to be easier to copy and paste the address from your web browser's address bar, rather than trying to remember exactly what the link was and typing it out with hyphens instead of spaces, `like-this`. That'll mean you've got your website's address at the beginning of the link, so you can just delete it. But you don't have to; it'll work one way or the other.


## Don't forget to test!

As ever, whenever you publish new content to your website, go to the article and click through each link you've added. One missing character could mean the link will break, leaving your customers frustrated!
