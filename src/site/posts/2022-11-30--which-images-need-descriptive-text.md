---
title: Which images need descriptive text?
intro: Image alt text is extremely important to many users, but how do we know which images should be described, and which shouldn't?
date: 2022-11-30
tags:
    - Accessibility
    - Content
---


The first rule in the Web Content Accessibility Guidelines (WCAG) requires us to [describe non-text content](https://www.w3.org/TR/WCAG21/#non-text-content) like images:

> All non-text content … has a text alternative that serves the equivalent purpose, except … If non-text content is pure decoration

'Pure decoration' is then defined as:

> serving only an aesthetic purpose, providing no information, and having no functionality

Seems straightforward enough: describe images using accessible text (something like an `alt` attribute) so that they're available to assistive technology like screen reader software. Any image that is solely decorative and doesn't provide information can be safely hidden.

But 'information' is a pretty broad word… Is it referring to data? Photos, illustrations, and icons? Or maybe something more abstract?


## Types of image

First up, we're talking about <i>content</i>, so it's mark-up we're talking about: `<img>`, `<svg>`; that kind of thing. Decorative flourishes added with CSS via `background-image` can be discounted.

### Data

This one is easy. Data is definitely information, so things like graphs, charts, and diagrams need to include descriptive text.

### Photos, illustrations, and icons

Expanding the scope of what's considered to be 'information' to  include any image that conveys information that isn't also available elsewhere is the next logical step. This means photos and illustrations will *usually* count.

Icons, on the other hand, [are normally be paired with some visible text](/blog/what-i-wish-was-in-wcag-prohibit-icon-only-buttons) so that we can be sure our users will understand their meaning. [When used in this way, an icon should not have an accessible name](blog/buttons-with-icons-and-text).

### Tone

How about more abstract images that add to the overall tone of a page or part of the page; images that don't provide information overtly, but contribute to the overall message the visitor gets?

Talking about something abstract in the abstract is tricky, so it might be an idea to give you three examples of images that might at first seem like candidates to be accessibly hidden:

1. A friendly cartoony image on an 'page not found' page, where a person looks confused
2. A generic image at the top of a blog post of some business people looking at a laptop
3. Colourful, quirky, and simply drawn illustrations used throughout a website to convey brand identity

To me, there's still information in each of those examples that we gather visually:

1. The person looking confused offers some solidarity and softens the disappointment; the company realises it's frustrating not to have found your page, but they'll help you find it
2. Being slightly generic and corporate is useful information, whether it's considered positive or negative
3. If a company's identity is friendly and fun, that's information worth communicating

What might seem like a decorative photo or illustration should be at the very least *considered* as requiring an accessible name.


## What's the best approach?

There's always going to be an element of subjectivity as to whether a particular image should have descriptive text or not,  so how do we decide which images require accessible names?

The most inclusive experience would be to describe every image unless there's a clear reason not to.

Some people who use screen reader software might thank you for a more comprehensive experience, but others may consider it unnecessary noise. So keep descriptive text brief and to the point.

Finally, if it doesn't feel right to describe an image, perhaps there shouldn't be an image there at all!
