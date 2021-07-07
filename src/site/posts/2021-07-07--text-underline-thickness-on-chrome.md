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

I wondered whether it might be something to do with a different default thickness; once I manually specified a value with `text-decoration-thickness` (which has [great browser support](https://caniuse.com/mdn-css_properties_text-decoration-thickness)) it would hopefully rectify itself.

Unfortunately it didn't. It seems the way Chromium browsers *calculate* `text-decoration-thickness` is different, so it doesn't matter whether you override the default value or set it manually. And the units don't seem to make any difference either: I tried pixels, ems, you name it; those underlines always looked ungainly in Edge, Chrome and Opera.

I considered delving into how the calculation is done, and even exploring *why* it's that way, but life is currently too hectic, and sometimes it's enough to accept something for what it is and try to fix it.

It was only really second level headings with links in them that were too chunky. `<h1>`s on this site never have links, and the slightly thicker underline doesn't look wrong on `<h3>`s and below, so I targeted links in `<h2>`s:

```css
h2 a {
  text-decoration-thickness: .06em;
}
```

The underline still looks different cross-browser but this value seems to hit the sweet spot. It's neither:

- too thick on Chromium browsers and right everywhere else
- proportional in Chromium and too thin on other browsers

It's a pain that we still have to think about these kinds of things, but I suppose non-standard browser behaviour is in much better shape than it was only a few short years ago. And the fact that we've got fine control over things like underlines makes me very happy indeed.
