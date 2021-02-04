---
title: Dashes, asterisks and plus signs
intro: |
    As a designer, I enjoy details. Markdown has lots of ways of doing the same thing and this is kind of nice, but also makes me sweat!
date: 2018-08-22
tags:
    - Tools
---

As a designer, I enjoy details. Markdown has lots of ways of doing the same thing and this is kind of nice, but also [makes me sweat](https://media.giphy.com/media/LRVnPYqM8DLag/giphy.gif).

I've always been of the mind that each bit of syntax should be distinct. e.g. Because asterisks are used for `<strong>` emphasis, I prefer not to use them elsewhere. So I use underscores for regular emphasis (`<em>`) and `+` for lists. This also nicely avoided using dashes for lists, as they're too close to underscores for my liking: `_` versus `-`.

Before I used Markdown, I used Textile. Textile is fine, but Markdown is better and much more widely used, so at one point I made the switch. Textile uses underscores for regular emphasis so maybe that's why I've always preferred that syntax.

I suppose an underscore on each side of some text *looks* more slanted, like italicised text. That's probably why Textile uses them, and why Markdown supports them.

## Going against the grain

My commitment to the cause has made my life a wee bit more difficult, in that most writing apps output asterisks with `⌘` + `i`, not underscores. So I've been bloody minded and insisted on typing the underscores myself, rather than using *every app's* built-in keyboard shortcuts.

## Semantics

But italics aren't the point. The *semantics* of it are simply 'emphasis' and 'strong emphasis'. Not 'forward-leaning in the same weight font' and 'upright letters but in a heavier weight font'.

## My conclusion

You've got regular and strong emphasis, not italics and bold (though this is normally how it looks visually). So asterisks should be used for both: one asterisk for regular and two for strong.

You can even use three asterisks for extra strong (which would output `<strong><em>content like this</em></strong>`. I'm more comfortable with `***content like this***` than `**_content like this_**`. Plus it stops me wasting energy on whether the underscores should be on the outside or the inside of the asterisks!

## Can of worms

So now that I'm not using underscores in my Markdown, does that mean I should start using dashes for lists…?

(I think I will -- most Markdown writing apps insert `- ` with the `⌘` + `l` keyboard shortcut.)
