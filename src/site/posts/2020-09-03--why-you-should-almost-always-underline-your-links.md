---
title: Why you should (almost) always underline your links
intro: |
    A link should look like the text around it, but with a couple of differences: maybe a bit of colour and (almost) always an underline.
date: 2020-09-03
tags:
    - Design
    - Accessibility
summaryImage: large
---

A link should look like all the rest of the text around it, but with a couple of differences. It should maybe have a bit of colour, and it should almost definitely have an underline. Let me explain why.


## Handwriting versus digital text

First up, let's think about how we use underlines in the wild. You've probably used them when taking notes by hand:

- to highlight/emphasise words or phrases
- to mark headings

When writing by hand, underlining is the easiest way to draw attention to text. I bet you'd make a right old mess trying to write in italics, overwriting some text several times to make it look bold could get very tedious, and if your writing isn't consistently the exact same size, larger text for headings might be difficult to pick out.

With a digital format like HTML, using italics, bold or a larger text size is not only much more effective visually, but it's easy to do.


## The underline is already taken

HTML gives us so much more than plain old text; the ['H' in HTML stands for 'Hyper'](https://www.w3.org/WhatIs.html), after all!

The conventions established at the outset of the web are essentially what we should still be aiming for visually. Think of what HTML looks like before you add any styling:

- emphasis is conveyed with italic or bold text
- headings are differentiated by larger, bold text
- links are marked with an underline

Do some usability testing and you'll quickly see that using an underline for anything other than a link on the web is problematic. Users will assume underlined content is a link and wonder why it's broken when they press it and nothing happens.

<i>Yes, [there's a `<u>` element](https://html.spec.whatwg.org/multipage/text-level-semantics.html#the-u-element), but [MDN is very clear](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/u#Other_elements_to_consider_using) that <q cite="https://developer.mozilla.org/en-US/docs/Web/HTML/Element/u#Other_elements_to_consider_using">In most cases, you should use an element other than `<u>`</q>.</i>


## Alternatives

Over the years, as web designers have attempted to add some flair to their interfaces, we've seen a couple of other link designs crop up regularly:

- A heavier font
- A different colour

But both of these approaches can suffer from accessibility issues.

### A heavier font

The problem with making links bold is that they look like strongly [emphasised text](/blog/whats-emphasis-and-whats-not). This means visitors will be:

- less likely interact with them, not realising they're links
- confused if they inadvertently press one, having thought it was emphasised text, not a link

There's also the issue that [bold fonts can be more difficult to read](https://pressbooks.ulib.csuohio.edu/accessibility/chapter/chapter-2-4-formatting-font-for-readability/), so if a bold-styled link covers a longer phrase, you might be causing accessibility problems.

> If you apply a Strong Style (bolding), to an already bold font, it makes the font so thick, it becomes difficult to read on screen

### A different colour

[Colour blindness affects just over 8% of men](https://www.colourblindawareness.org/colour-blindness/) and 0.5% of women worldwide, so around 4.5% of the people browsing the web will have some form of colour vision deficiency.

Then there are people with less than perfect eyesight, who rely on decent contrast to read content in their web browser. From [the University of Iowa Hospitals &amp; Clinics](https://uihc.org/health-topics/what-2020-vision):

> Only about 35 percent of all adults have 20/20 vision without glasses, contact lenses or corrective surgery. With corrective measures, approximately 75 percent of adults have this degree of visual acuity while the other 25 percent of the population just doesn't see very well

If we add 75% of our original 4.5% (the percentage of people with some form of colour blindness who have 20/20 vision), that's going to be a *lot* of people that could struggle to make out a link amongst its surrounding text.

Finally, remember that the web can be viewed on any number of devices, with wildly different screen qualities; cheap monitors and tired old displays, and all of those dropped phones and tablets with cracked glass and dead pixels.

Using colour alone to convey meaning is a bad idea.


## Combinations

A combination of colour and bold text is *better* but there are still those pretty hefty downsides when we start mixing visual metaphors (bold is emphasis, etc.).

If you want to make your links accessible *and* engaging, the best combination is *colour and an underline*, just like the default browser styling. But remember to [check the contrast ratio of your colours](https://webaim.org/resources/linkcontrastchecker/):

1. The non-link text against its background
2. The link text against its background
3. The link in comparison to the regular text it sits within

The third can be mitigated with the underline, as long as the first and second are ok.


## Exceptions

The only place you can get away without underlining a link is somewhere that the user *expects* normal text to be a link, like [navigation in a header or footer](https://design-system.service.gov.uk/styles/typography/#links-without-underlines). But even then it's always a good idea to test this assumption with users.
