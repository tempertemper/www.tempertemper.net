---
title: Bag some AAA wins where you can
intro: Complying with WCAG AA is hard, and AAA seems a lot harder, but there are actually plenty of AAA rules that are relatively easy to meet.
date: 2022-07-13
tags:
    - Accessibility
related:
    - wcag-aaa-in-language-i-can-understand
    - accessibility-doesnt-stop-at-wcag-compliance
---

Accessibility isn't just about achieving and maintaining compliance with the Web Content Accessibility Guidelines (WCAG) 2.1 AA; there are lots of things that [aren't covered by WCAG that are worth doing](/blog/accessibility-doesnt-stop-at-wcag-compliance). There are also lots of hugely beneficial AAA success criteria (rules) that are relatively easy to meet.


## You probably satisfy some already

The good news is that, if you've met AA, you probably already satisfy a handful of AAA rules:

- If you're compliant with the AA [2.1.1 Keyboard](https://www.w3.org/TR/WCAG21/#keyboard), it's likely you also satisfy the AAA [2.1.3 Keyboard (No Exception)](https://www.w3.org/TR/WCAG21/#keyboard-no-exception)
- You probably use headings to organise content and, as long as you're [doing it right](/blog/using-the-html-document-outline), I bet you meet [2.4.10 Section Headings](https://www.w3.org/TR/WCAG21/#section-headings)
- If users can use a mouse, keyboard, screen reader, etc. interchangeably, you've probably hit [2.5.6 Concurrent Input Mechanisms](https://www.w3.org/TR/WCAG21/#concurrent-input-mechanisms)


## Easy to build into your workflow

Now for some easy ones:

- It's rare to find images of text outside of blogs, so if you've been careful to avoid them in your blog's hero images, you've met [1.4.9 Images of Text (No Exception)](https://www.w3.org/TR/WCAG21/#images-of-text-no-exception)
- Explaining an abbreviation the first time you use it on each page is an easy way to achieve [3.1.4 Abbreviations](https://www.w3.org/TR/WCAG21/#abbreviations)
- Like abbreviations, if jargon is necessary, it should be explained the first time it appears on a page; do that and [3.1.3 Unusual Words](https://www.w3.org/TR/WCAG21/#unusual-words) has been satisfied


## Harder, but worth the effort

Some success criteria need a bit more effort to integrate but are very much worth the effort:

- Nice big buttons, links, and other touch targets (at least 44px by 44px) will mean you're on course to hit [2.5.5 Target Size](https://www.w3.org/TR/WCAG21/#target-size)
- If your content can be understood by people with a '[lower secondary education level](https://www.w3.org/TR/WCAG21/#dfn-lower-secondary-education-level)' (usually around 11 years old), you're in good shape to meet [3.1.5 Reading Level](https://www.w3.org/TR/WCAG21/#reading-level)
- Ensuring every content change on the page is triggered by an explicit button press (not simply by changing the value of a select dropdown, for example) will mean you meet [3.2.5 Change on Request](https://www.w3.org/TR/WCAG21/#change-on-request)


## Meeting some with 'modes'

Using CSS's [User Preference Media Features](https://www.w3.org/TR/mediaqueries-5/#mf-user-preferences), we can provide styling specific to when users have set certain operating system preferences:

- [1.4.6 Contrast (Enhanced)](https://www.w3.org/TR/WCAG21/#contrast-enhanced) is notoriously hard to meet as it's often at odds with brand guidelines, but using [Increased Contrast Mode](/blog/using-the-increased-contrast-mode-css-media-query) is a good compromise, allowing us to hit those higher contrast ratios, even if it might go slightly off-brand
- [`prefers-reduced-motion`](/blog/accessible-animated-content-without-the-compromise) is a mechanism to remove animation, helping us to comply with [2.3.3 Animation from Interactions](https://www.w3.org/TR/WCAG21/#animation-from-interactions)

*Full* AAA compliance might be out of reach for most projects, but bagging as many AAA success criteria as possible can be pretty straightforward, and is definitely where we should be aiming.
