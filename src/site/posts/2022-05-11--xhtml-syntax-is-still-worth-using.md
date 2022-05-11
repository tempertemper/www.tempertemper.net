---
title: XHTML syntax is still worth using
intro: A few weeks ago I read an article on CSS-Tricks about writing HTML the HTML way, not the XHTML way, and it has been bothering me a bit.
date: 2022-05-11
tags:
    - HTML
    - Development
---

A few weeks ago I read an article on CSS-Tricks about [writing HTML the HTML way, not the XHTML way](https://css-tricks.com/write-html-the-html-way-not-the-xhtml-way/); in it Jens Oliver Meiert suggests we drop technically unnecessary XHTML writing practices in our HTML5 documents. Here's Meiert's list:

> - Start and end tags are not always required.
> - Empty elements don't need to be closed.
> - Element and attribute names may be lower or upper case.
> - Attribute values may not always be quoted.
> - Attribute minimization is supported.

Me? I'm not so sure… Many of the syntax practices XHTML introduced are worth hanging on to. Let's go through that list and I'll tell you why.

<i>Before I do, it's worth mentioning that I'm not saying you should make wholesale changes to existing codebases, or change the HTML linting rules on collaborative projects. Also, this is about developer experience and the code we *write*, so I'm not too concerned about the generated/output code.</i>


## Start and end tags are not always required

I'd argue that [end/closing tags for elements are always worth adding](/blog/optional-closing-tags-in-html):

- Of over 100 elements, only 15 don't require an end tag
- Getting it wrong can break things
- An end tag makes it easier to read the code


## Empty elements don't need to be closed

This is talking about the trailing slash on elements like `<hr>` and `<img>`. For me, [the trailing slash is worth adding](/blog/self-closing-elements-in-html):

> It provides a clear marker that this element won't contain content or have a closing tag. There's no need to look for where the element ends, because it doesn't


## Element and attribute names may be lower or upper case

I don't have any real issue with all-caps in HTML, but it looks weird. I imagine most developers write HTML in lowercase so, for consistency, it's probably best to stick with that.


## Attribute values may not always be quoted

[HTML attributes](/blog/an-introduction-to-html-attributes) with multiple values (like `class="snippet featured"`) need quotes, where those with single values don't. This is another consistency thing, but it also lends itself to ease of reading:

> I prefer to keep things consistent, so I always use straight double quotes (") so that every attribute is easily identifiable.


## Attribute minimization is supported

This is for boolean attributes like `required` that are either true or false, and is the one point I wholeheartedly agree with!

[HTML booleans are not like other attributes](/blog/sometimes-when-its-false-its-true), like `id`, `class` and `href`; they're even [different to ARIA booleans](/blog/booleans-in-aria)! They should look distinct.


## Think carefully about the code you write

Syntax choice should be [deliberate and meaningful, even when there are multiple options](/blog/dashes-asterisks-and-plus-signs). Just because you *can* use capitals and omit the closing tag on some elements, the trailing slash on self-closing elements, the value from booleans, and quotes on a single value attribute, doesn't mean you *should*.

> Although XHTML is dead, many of these rules have never been questioned again. Some have even been elevated to “best practices” for HTML.

I'm glad Meiert has questioned their use but, for me, many XHTML rules remain best practices for HTML. It's totally valid *not* to use them, but there are advantages to doing some things the XHTML way.
