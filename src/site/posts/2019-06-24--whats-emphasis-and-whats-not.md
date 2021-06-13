---
title: What's emphasis and what's not
intro: |
    Semantic HTML is hard. We stopped using `<i>` and `<b>` elements in favour of `<em>` and `<strong>`, but are `<i>` and `<b>` still useful?
date: 2019-06-24
tags:
    - Development
    - Accessibility
summaryImage: large
---

We all know that we should be using `<em>` and `<strong>` to emphasise and strongly emphasise words and phrases; we've looked at `<i>` and `<b>` tags with disdain. But should that be the case?

Don't get me wrong -- `<i>` and `<b>` aren't elements I reach for very often; it's not like I'll be campaigning for them to be included in the [Markdown](/resources/markdown-cheatsheet)  spec any time soon. But they're worth knowing about as they do have their uses.

A quick caveat: if you're writing an HTML email and you want some important text to look italic or bold, you're probably best going old school and using `<i>` and `<b>` tags as they have the best cross-email client support. Where HTML email is concerned, rules and good practices are often out the window!


## Indicating another voice

`<i>` is not for icons. In fact, if you're still using icon fonts (rather than SVG) we really need to talk…

So what is the `<i>` element for? The [ever-excellent MDN web docs](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/b) say `<i>`:

> represents a range of text that is set off from the normal text for some reason. Some examples include technical terms, foreign language phrases, or fictional character thoughts

It's not emphasis, it's like another voice.

## Some examples

My wife is Spanish, so if I were writing about our frequent visits to Barcelona, where she's from, I'd use `<i>` to mark up a Spanish word. Notice I also used the `lang` attribute so that screen readers know which language we're using.

```html
<p>When we visit my wife’s family in Barcelona, we always go out for <i lang="es">tapas</i> and a few drinks.</p>
```

Another good use case is for inner monologue:

```html
<p><i>What shall I make for tea tonight?</i>, I wondered as I walked home from work.</p>
```

It's not speech, so quotation marks aren't appropriate; it's not described directly (e.g. "I wondered what I should make for tea that night, as I walked home from work").

There are some more great examples on the [HTML Living Standard](https://html.spec.whatwg.org/multipage/text-level-semantics.html#the-i-element).


## Bringing attention to content

`<b>` used to mean 'bold' and sometimes we might want to make something to stand out that doesn't convey strong emphasis or importance.

Drawing again from [MDN web docs](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/b), they say `<b>` should be used to:

> draw the reader's attention to the element's contents, which are not otherwise granted special importance

### A good use case

There's one place I reach for `<b>` every time: a bulleted list where I want to highlight the first word or phrase in each item.

```html
<h3>My favourite fruit</h3>
<ul>
    <li><b>Bananas</b>: I love banana on toast in the morning</li>
    <li><b>Strawberries</b>: there's nothing like the taste of perfectly ripe strawberries</li>
    <li><b>Apples</b>: who doesn't enjoy a juicy, crunchy apple?</li>
</ul>
```

Those fruit names aren't really headings. And they're not things that should be emphasised or given strong importance. And I don't think that's the right place for a definition list (`<dl>`) as it's we're not really defining the terms 'bananas', 'strawberries' and 'apples'. We're simply aiding the scanning of the list.

Again, there are [some great examples of use cases](https://html.spec.whatwg.org/multipage/text-level-semantics.html#the-b-element) on the HTML Living Standard website.


## Not common but not redundant

The `<i>` and `<b>` elements are not without their uses. They're not common enough to be included in [Markdown](/resources/markdown-cheatsheet), but since Markdown lets you pepper in some HTML, you can leverage more obscure elements like these when you need to.
