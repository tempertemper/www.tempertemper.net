---
title: WebKit has fixed the implicit role on footers
intro: |
    Last year, I wrote about implicit ARIA roles; an issue I encountered was that VoiceOver didn't give an implicit role to footers. Well, it's fixed!
date: 2020-08-24
tags:
    - Development
    - Accessibility
---

Around this time last year [I wrote about implicit ARIA roles](/blog/implicit-aria-landmark-roles#in-practice). One of the issues I encountered was that VoiceOver didn't give an implicit role of `contentinfo` to the footer of the document, as it was supposed to.

> the same principle is *supposed* to be true for the `<footer>` element, but it doesn’t add `role="contentinfo"` implicitly

Well, I'm happy to report that that's not the case any more! When I wrote about [Apple having fixed the problem with `<address>` elements](/blog/using-address-in-html-wont-be-problematic-for-much-longer), I thought I'd check the `role="contentinfo"` thing for good measure, and as luck would have it, removing `role="contentinfo"` from a footer that's a direct (or un-sectioned) child of the `<body>` element now works as expected!

I did a wee bit of digging and found the [WebKit bug report](https://bugs.webkit.org/show_bug.cgi?id=190138) and the [fix that was implemented in February 2019](https://trac.webkit.org/changeset/242051/webkit/). Funnily enough, the article I wrote was published right between the [fix making it into Safari Technology Preview](https://webkit.org/blog/8658/release-notes-for-safari-technology-preview-77/) in March 2019, and being released to the general public with macOS 10.15 (Catalina) and iOS 13 in September.

So when we [validate our pages](https://html5.validator.nu) and are warned <q>The contentinfo role is unnecessary for element footer</q>, we can now safely remove the explicit role, passing validation as well as knowing that our visitors using screen readers will be correctly told where the 'content information' for the page lives.
