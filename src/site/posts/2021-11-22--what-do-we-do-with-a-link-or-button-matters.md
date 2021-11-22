---
title: What do we do with a link or button matters
intro: Language is a powerful thing; if the metaphor we use to describe actions to our users is 'click' we tend to forget about all those who don't.
date: 2021-11-22
tags:
    - Accessibility
    - Design
summaryImage: button.png
summaryImageAlt: A big blue button with the word ‘Activate’ with a question mark.
---

I see it all over the place, in guidance and instructions:

- "Click 'Continue'"
- "Click the 'Close' button"
- "Click the text box and enter your name"

But do we all click? *I* do, but I also <i>press</i>, since I use my keyboard to navigate; I do a lot of tapping on my touch screen device too. Some people [speak commands](https://webaim.org/articles/motor/assistive#voicerecognition), some [gesture](https://thoughtbot.com/blog/an-introduction-to-macos-head-pointer), others [sip and puff](https://webaim.org/articles/motor/assistive#sipnpuff).

'Click' is not only a redundant metaphor, it describes what the user does to their pointing *device itself*; a better verb would describe what the user does *on the interface*.

More pertinently, though, [language is a powerful thing](https://rorueso.blogs.uv.es/2010/10/28/manipulation-of-language-as-a-weapon-of-mind-control-and-abuse-of-power-in-1984/), and if everything's a 'click' we tend to forget about all those users who don't.


## What do we say instead?

In order to design, develop, and prioritise users who don't or can't click, it would be nice if we had one verb that:

- works for all of the links, buttons, and form fields we might interact with
- describes how the user interacts with the interface rather than their hardware

We do, but it's not a very good one. The [W3C tend to use the word 'activate'](https://www.w3.org/WAI/WCAG21/Understanding/target-size.html) but, as [Eric Bailey observes](https://www.getstark.co/blog/the-endless-search-for-here-in-the-unhelpful-click-here-button):

> "Activate" is jargon---it isn’t used that much in everyday conversation. While technically accurate, the perceived stodginess of this phrase might confuse or scare off someone, especially if they're not digitally literate

The best way is probably to use language that makes sense for each control:

- 'Press' for a button, as that fits with the general button metaphor
- We 'follow' a link, so that could work
- Form controls might be a bit more nuanced, we might 'enter' some, 'open' or 'select' others, 'choose' options, and so on

Even then, what works for the users of one product won't necessarily work for those of another, so be sure to carry out user testing.

Carefully considering the words we use for actions on the interfaces we design and build helps us think about our users￼; if our mental model of our users is that they only click, our products will reflect that.
