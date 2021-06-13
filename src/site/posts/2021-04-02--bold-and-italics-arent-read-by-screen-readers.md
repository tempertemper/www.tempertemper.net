---
title: Bold and italics aren't read by screen readers
intro: |
    Emphasis and other text-level semantics are normally ignored by screen readers, so it you're relying on them for meaning you could be in trouble.
date: 2021-04-02
tags:
    - Accessibility
summaryImage: large
---

Whether you call them italics and bold or `<em>` and `<strong>`, we're talking about *emphasis* and the HTML used to communicate it.

A sighted user will be able to pick out the italicised or heavier text that denote levels of emphasis, but unfortunately most non-sighted screen reader users won't know anything about it. [They can query a word](https://twitter.com/LeonieWatson/status/1057628879739805699?s=20), but if they don't know there's anything different about it, why would they query it?

Interestingly, the NVDA screen reader [added support for emphasis, only to remove it](https://github.com/nvaccess/nvda/issues/4920#issuecomment-161162498) following complaints by users:

> Having emphasis reported by default has been extremely unpopular with users and resulted in a lot of complaints about NVDA 2015.4 … As such, we've now disabled this by default, though the option is still there for those that want it

So it can be switched on, but I doubt a high percentage of users use the feature because:

- people [don't tend to change default settings](https://www.nngroup.com/articles/the-power-of-defaults/)
- the extra noise would mean it was quickly turned off again

In the old GOV.UK Elements, they were very clear about [how bold and italics should be used in body copy](https://govuk-elements.herokuapp.com/typography/#typography-body-copy):

> avoid using bold and italics

The GOV.UK Design System that superseded Elements is less prescriptive, though they talk more about [highlighting critical information](https://design-system.service.gov.uk/styles/typography/#bold-text), than semantic emphasis:

> You can use bold to emphasise particular words in a transaction. Use it to highlight critical information that users need to refer to or you’ve seen them miss.

The example they give draws attention to a reference number and an email address, so the use of the word "emphasise" is misleading; [the `<b>` element would be more appropriate](/blog/whats-emphasis-and-whats-not) than `<strong>` here.

It's also worth noting that the [GDS Transport](https://design-system.service.gov.uk/styles/typography/#font) doesn't have an italic/oblique variant, just Light and Bold, so emphasis (if indeed it is emphasis) doesn't come in more than one flavour.

The take away here is: if you're relying on emphasis to convey meaning you're on dangerous ground, as some users won't know it's there.

Like many tools we reach for as web designers, text-level semantics like italics and bold should be treated as a [progressive enhancement](/blog/minimalism-and-progressive-enhancement). In other words, your sentences should make sense without emphasis; those `<em>` and `<strong>` wrappers should just offer a nice added extra for users that know they're there.


