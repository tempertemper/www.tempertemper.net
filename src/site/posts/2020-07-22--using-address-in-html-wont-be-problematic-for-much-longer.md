---
title: Using address in HTML won't be problematic for much longer
intro: |
    There's a bug in Safari that adds an implicit role to `<address>` which causes problems for screen readers. The good news is, a fix is very close!
date: 2020-07-22
tags:
    - Development
    - HTML
    - Accessibility
---

There's a bug in Safari (WebKit). It adds [an implicit role](/blog/implicit-aria-landmark-roles) of `contentinfo` to any instance of an `<address>` element. This means that VoiceOver reads "content information" for an `<address>`, which is the landmark role normally associated with the main `<footer>` of a website.

So if your website has a footer (it almost certainly does!) and an `<address>` somewhere, this is going to be confusing for screen reader users. There [should only be one](https://developer.mozilla.org/en-US/docs/Web/Accessibility/ARIA/Roles/Contentinfo_role#One_contentinfo_landmark_per_page) `contentinfo` landmark on a webpage, so a second will make people wonder what's going on. And when they navigate to the `<address>`, they'll be given an address, rather than the full content they'd usually expect from a footer. Not a great experience.

[Scott O'Hara has written about the problem](https://www.scottohara.me/blog/2019/02/14/addressing-contentinfo) of `<address>` having the implicit `role="contentinfo"`, and even proposed a fix. If we override the built-in semantics by adding `role="group"` or `role="presentation"` to the `<address>` element, VoiceOver doesn't treat an `<address>` as content information.

I did this to [the address in my website's footer](https://github.com/tempertemper/tempertemper.net/pull/39/commits/48f5cc3a438b1c80df34f0bbefb06b37308775e5), but as Scott points out in his article, it's a *hack*.

But there's good news! The WebKit team were listening, have recognised this as a bug, and [the fix was made last month](https://bugs.webkit.org/show_bug.cgi?id=212617). I've tested this with the most recent version of Safari Technology Preview (release 110) and the implicit `role="contentinfo"` on `<address>` is gone!

So [I'll soon be removing `role="group"`](https://github.com/tempertemper/tempertemper.net/issues/394) from my website's addresses. I just need to wait for usage of pre-fix versions of Safari to drop off a bit, but as it's an 'evergreen' browser, I don't see that taking too long.
