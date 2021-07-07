---
title: Text underline thickness on Chrome
intro: |
    Link underlines are thicker on Chromium-based browsers than on Safari and Firefox; so much so that they can look odd on larger text like headings.
date: 2021-07-07
tags:
    - Development
---

I use big headings on my website. Some of those headings, like those on the [blog listing](/blog/) are also links. Something that has bugged me for a while is that the thickness of the link underline on Chromium browsers (like Chrome, Opera, and Edge) is greater than that on Safari and Firefox.

People don't generally jump from browser to browser, comparing a website to ensure it looks the same everywhere, but when 'thicker' is actually 'too thick', even in isolation, there's a problem.

I wondered whether it might be the default thickness; once I manually specified a value with `text-decoration-thickness` it would rectify itself.

Unfortunately it didn't. It seems the way Chromium browsers calculate `text-decoration-thickness` is different. I tried pixels, ems, and all the others, but those underlines are always thicker in Edge, Chrome and Opera.

I considered looking into how the calculation is done, and even exploring *why* it's that way, but life is currently too hectic to delve that deep. I accepted that it's not right and looked for a fix.

It's only really second level headings with links in them that look unwieldy. `<h1>`s never have links, and the slightly thicker underline doesn't look too odd on `<h3>`s and below, so I targeted links in `<h2>`s:

```css
h2 a {
  text-decoration-thickness: .06em;
}
```

The underline still looks different cross-browser but with this value it's neither too thick nor too thin anywhere. A bit of a pain that I have to add it at all, but sometimes the web is messy like that.
