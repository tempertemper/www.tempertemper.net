---
title: Abbreviations can be problematic
intro: We can all agree that abbreviations like acronyms usually need to be defined, but what if the HTML method we use isn't accessible to all users?
date: 2022-07-18
tags:
    - Accessibility
---

CSS-Tricks is full of interesting articles, and a recent one about [designing for people with situational impairments](https://css-tricks.com/increase-your-reach/) was no exception. But there was something that caught me in my tracks as I began reading in my RSS app:

> NGL, I was a little overwhelmed when I sat down to write this article.

"NGL"…? What's "NGL"!? I dutifully highlighted the text and tapped 'Look Up'; nothing, as is often the case with colloquial acronyms like this. So 'Search Web' was my fallback.

Thankfully, Duck Duck Go's first couple of results provided the answer: "Not Gonna Lie". But it got me thinking about how to use abbreviations in an accessible way.

I've done lots of work in big organisations, and they're notorious for using acronyms internally. It's an efficient way to operate if you *know* everyone is familiar with the acronyms, but I can't tell you how many times I've:

- missed parts of conversations while trying to figure out what an acronym means, or surreptitiously look it up
- interrupt to ask someone to clarify an acronym

As it happens, Acronyms are covered by the Web Content Accessibility Guidelines (WCAG) in [3.1.4 Abbreviations](https://www.w3.org/TR/WCAG21/#abbreviations), which requires that:

> A mechanism for identifying the expanded form or meaning of abbreviations [including acronyms and initialisms] is available.

This is a AAA (the highest level) concern but, as I mention in a recent article about [bagging WCAG AAA wins where you can](/blog/bag-some-aaa-wins-where-you-can), explaining acronyms the first time they're used is a very easy way to go above and beyond the standard AA goal and improve many people's experience.


## A not-quite ideal mechanism for explaining an acronym

Going back to the article that prompted this post, the author has anticipated that some people might need a definition of "NGL", so they helpfully used [the `<abbr>` element](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/abbr), in conjunction with the `title` attribute, containing the abbreviation's definition; something like this:

```html
<p>
    <abbr title="Not gonna lie">NGL</abbr>, I was a little overwhelmed when I sat down to write this article.
    <!-- the rest of the paragraph -->
</p>
``` 

This is great, as the browser adds a dotted underline on the text to signify there's a definition, and a tooltip is shown when a mouse/pointer is hovered over the text. Unfortunately, it doesn't work for all users:

- RSS reader software generally doesn't pull that kind of HTML detail through; neither does browsers built-in 'reader mode'
- It doesn't show the tooltip when a touch screen user taps it
- Browsers don't include `<abbr>` elements in the tab index, so keyboard users can't access the definition; ditto for speech recognition software users
- Screen reader users don't hear the definition as, [like bold and italics](/blog/bold-and-italics-arent-read-by-screen-readers), `<abbr>` isn't announced by screen reader software (it can be turned on, but the downsides far outweigh the benefits)
- The dotted underline can be difficult to spot for users with blurred vision
- The tooltip covers other content, which could be slightly annoying for accidental mouse-overs


## So what do we do?

HTML is great, but `<abbr>` and `title` aren't the right solution here. Instead, we have two solid, accessible-to-all solutions.

### Just use the words

If the acronym is used once, simply using the words is usually the right approach. In our example, the flow and tone and flow of the opening would be lost if the acronym were defined in brackets, but spelling it out still reads well:

```html
<p>
    Not gonna lie, I was a little overwhelmed when I sat down to write this article.
    <!-- the rest of the paragraph -->
</p>
```

### Define the first appearance with brackets

When an acronym is used multiple times, though, it's likely it's being used for practical reasons rather than tone of voice. Reading can be clunky if the words are written in full each time, so all we have to do is define it the first time it's used on each page; from there on in it can be used freely.
