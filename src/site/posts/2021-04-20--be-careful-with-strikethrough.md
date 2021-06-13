---
title: Be careful with strikethrough
intro: |
    Struck-through text isn't read by screen readers. This is true of all text-level semantics, but it's worth drawing attention to strikethough.
date: 2021-04-20
tags:
    - HTML
    - Accessibility
    - Development
summaryImage: large
---

[Like emphasis](/blog/bold-and-italics-arent-read-by-screen-readers), strikethrough text (`<s>`) doesn't get read out by screen reader software. This is true of all [inline text-level semantic elements](https://www.brucelawson.co.uk/2018/screenreader-support-for-text-level-semantics/), but it's worth drawing particular attention to strikethough.

Why strikethough? Well, although it [isn't part of the official Markdown specification](https://daringfireball.net/projects/markdown/syntax), it's common in Markdown *variants*. With these variants, the syntax is usually two tildes (`~~`) either side of the text to be struck through. This makes it very easy to write compared to other HTML elements, which would need to be written out in full.

```md
When he returned from the barbers with a terrible haircut, my first instinct was to ~~laugh out loud~~ console him.
```

That sentence containing struck-through text would compile to:

```html
<p>When he returned from the barbers with a terrible haircut, my first instinct was to <s>laugh out loud</s> console him.</p>
```

So with strikethough being so easy to write, what are the problems we might encounter?


## Not always the output you'd expect

Some Markdown compilers/variants incorrectly produce the `<del>` element instead of `<s>`. [GitHub Flavored Markdown is the main culprit](https://github.github.com/gfm/#strikethrough-extension-), and they even describe it as their "Strikethough extension". I cover the [distinction between `<s>` and `<del>`](/blog/the-difference-between-strikethrough-and-del) separately.


## Strikethrough is a difficult progressive enhancement

Again, from my [article about emphasis and screen readers](/blog/bold-and-italics-arent-read-by-screen-readers):

> text-level semantics like italics and bold should be treated as a progressive enhancement. In other words, your sentences should make sense without emphasis; those `<em>` and `<strong>` wrappers should just offer a nice added extra for users that know they’re there.

Unlike most text-level semantics, strikethrough *adds content*, rather than just wrapping text in some meaningful tags, so it's almost impossible to progressively enhance.

Our example from earlier would be read out like this:

> When he returned from the barbers with a terrible haircut, my first instinct was to laugh out loud console him

How do you "laugh out loud console" someone? Without the context of strikethrough, this doesn't make sense.


## Forcing the matter

There is [a way to force text-level semantics to be read out](https://www.tpgi.com/short-note-on-making-your-mark-more-accessible/) using CSS `::before` and `::after` pseudo elements; since `<s>` requires extra content that could change the meaning of a sentence without the semantics (`<del>` and `<ins>` do too), it could be the right approach here.

TPGi list some downsides to the technique:

> - It blurs the line between CSS for presentation and HTML for content
> - It could be overused and become an irritant rather than informative

The latter could be forgiven for `<s>`, given it's difficult progressive enhancement. There's the case where the NVDA screen reader [added support for emphasis but quickly removed it](https://github.com/nvaccess/nvda/issues/4920#issuecomment-161162498) after a backlash from its users, but strikethrough isn't as commonly used as `<em>` and `<strong>`.

The first bullet there is of most concern to me; what if:

- the styling fails to load and the user is left with just the HTML?
- the reader is using Safari's Reader mode which strips styling?
- your visitor saves the article for off-line reading later, using a service like [Instapaper](https://www.instapaper.com) or [Pocket](https://getpocket.com)?

CSS is a progressive enhancement, after all…


## Visually difficult to read

Moving from users who *hear* the contents of a document to those that *see* it, those with impairments like low vision or dyslexia already have a challenge reading text. Add a line through that text and we're making it even more difficult for them to read our content.


## Strikethrough is not for me

To sum up, strikethrough:

- has inconsistent---often incorrect---output when converted from Markdown to HTML
- is not read by screen readers by default
- is difficult (impossible?) to use as a progressive enhancement
- might not be read even when CSS is used to force the matter
- obscures text with that line-through, making reading difficult for some users

That's enough for me to avoid it entirely.
