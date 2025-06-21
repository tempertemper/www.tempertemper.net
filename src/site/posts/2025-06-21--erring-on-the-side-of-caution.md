---
title: Erring on the side of caution
intro: At some point a third party will audit the accessibility of your product; being strict now makes that process smoother and far less stressful.
date: 2025-06-21
tags:
    - Accessibility
---

The organisation I'm currently working in has *lots* of digital products, many of which have some form of integration with UK Government services (tax, payroll, etc.) and, as a result, they come under regular scrutiny by various Government departments.

You might be in a similar position. Maybe your product integrates into a larger software ecosystem and your contract with that company says you must be compliant with, or making progress towards, a certain level of accessibility.

The measure most organisations use is the Web Content Accessibility Guidelines (WCAG) version 2.1, level AA; hit this and you have a solid [baseline](/blog/accessibility-doesnt-stop-at-wcag-compliance) accessibility.


## WCAG conformance can be open to interpretation

There are only a handful of rules (WCAG calls them 'success criteria', or SCs) that you can measure with a clear 'yes' or 'no'. The overwhelming majority are woolly, grey, and open to interpretation.

Think of an SC and I bet there's some wiggle room on what could be considered a pass or fail:

- [1.3.1 Info and Relationships](https://www.w3.org/TR/WCAG/#info-and-relationships)? Yep.
- [2.4.6 Headings and Labels](https://www.w3.org/TR/WCAG/#headings-and-labels)? Absolutely.
- [3.2.1 On Focus](https://www.w3.org/TR/WCAG/#on-focus)? You betcha.

Even relatively straightforward SCs like [1.1.1 Non-text Content](https://www.w3.org/TR/WCAG/#non-text-content) and [2.4.3 Focus Order](https://www.w3.org/TR/WCAG/#focus-order), can sometimes be argued one way or the other.


## Some things come down to opinion

Granted, most things are still an obvious pass or fail, and the grey areas are often narrow, but there's a danger there. Let's say you audit your product and there's a borderline issue where you could go either way; let's say you're feeling permissive, and you give it a 'pass'.

There will come a time when you need to validate you and your team's hard work with an objective third party accessibility audit. But it might be that their auditors are stricter, and could view that same issue you passed as a marginal fail.


## The benefits of a stricter approach

The good news is that having stricter approach to those 'is it/isn't it' issues has a good number of benefits.

### Fewer awkward conversations

I don't mind a difficult conversation, but I don't relish them. I want to avoid a situation where I might have to defend my position where I've been lenient and a third party auditor hasn't.

The external auditor has no skin in the game, where my colleagues and I could be seen to have, so the perception is that *of course* we'd go easy on ourselves. Stakeholders may then wonder what else we've been happy to let slip through, which erodes their confidence and the internal accessibility team's credibility.

### Confidence

Speaking of confidence, letting fewer grey-area issues through means you can be confident in your work, regardless of how strict a third party auditor is. No need to fret when it comes to outside scrutiny.

### A deeper understanding

When the line is drawn on the stricter side, it also means the conversation with designers and developers goes deeper, which enriches their understanding of accessibility and the various approaches they might take to overcome a potential issue for users.

### Better results for users

And, of course, having a stricter position on internal accessibility has the happy effect of making the product that little bit easier for disabled people to use.

WCAG has its (many!) problems, but it's the standard measure of accessibility. So when we encounter those areas where it's open to interpretation, there are so many reasons why it's better to err on the side of caution to ensure compliance when third party audits become part of the process.
