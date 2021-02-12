---
title: Using horizontal rules in HTML
intro: |
    The horizontal rule is pretty widely misunderstood and often abused. It's not an HTML element I reach for very much, but it's worth writing about.
date: 2020-07-20
tags:
    - Development
    - HTML
---


The horizontal rule is pretty widely misunderstood and often abused. It's not an HTML element I reach for very much, because of what it means semantically. I'll keep you in suspense a little and first explain how *not to use* an `<hr />`.


## How a horizontal rule shouldn't be used

I've seen `<hr />` elements used as [clearfixes](https://css-tricks.com/all-about-floats/#techniques-for-clearing-floats). Don't do that. If you have to clear floats, use CSS on a parent element; if you *really can't* use CSS, use a semantically meaningless `<div>`. But you can almost certainly use CSS.

`<hr />`s are often (ab)used to provide visual flair, where a border in CSS would be more appropriate. If you want a full-width line above every `<h2>`, for example, you'd use:

```css
h2 {
    padding-top: .5em;
    border-top: 1px solid #ccc;
    margin-top: 1em;
}
```

You'd be correctly using headings as a way to section up your document. That thin grey line above your heading, with some space between it and the heading itself (the padding) and also between it and the content above (margin) can work well and keeps your markup clean, allowing you to use `<h2>` whenever needed and you get the styling for free.

If you ever wanted to change the design, all you have to do is remove `border-top: 1px solid #ccc;` and `padding-top: .5em;` from your CSS and you're done.

On the other hand, if you're adding the line with the `<hr />` element, not only do you have to remember to add one whenever you use an `<h2>`, but you've got a lot of finding and replacing to do on your website if the design changes:

```html
<hr />
<h2>This is a heading</h2>
```

Remember, that `<hr />` is *semantic*, so screen readers and other software that reads your website's code (search engines, for example) will attach meaning where there is none, and the heading provides the meaning you're after already.


## How a horizontal rule should be used

So what semantic meaning does a horizontal rule have? I really like [MDN's definition](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/hr):

> a thematic break between paragraph-level elements: for example, a change of scene in a story, or a shift of topic within a section

They're often used in novels as a thematic break within a chapter; not a new chapter, but a much stronger division than a new paragraph would provide.

And that's why I rarely use them -- they're made for long-form content that wouldn't be suited to the way I write for the web. I know there are a handful publishers who make their books available on the web, but I've never been comfortable reading this way. Give me an .epub any day.


## How a horizontal rule should look

VoiceOver reads "horizontal separator", removing the suggestion of a line, which is what the `r` in `<hr>` stands for. An element that *separates* content.

Wikipedia defines [how a horizontal rule should look](https://en.wikipedia.org/wiki/Section_(typography)#Flourished_section_breaks) nicely:

> Space between paragraphs in a section break is sometimes accompanied by an asterism (either proper ⁂ or manual * * *), a horizontal rule, fleurons, or other ornamental symbols

So it's up to you! Don't feel you have to use that default 1px line -- use something a bit more creative. Or it's possible that, like me, you'll probably never reach for an `<hr />` again, now that you know their intended use!
