---
title: Dark Mode websites on macOS Mojave
intro: |
    macOS Mojave comes with a Dark Mode feature, but how do we get our websites to do the same? Turns out there's a handy CSS media query that does the job!
date: 2018-11-28
tags:
    - Design
    - Development
---

I'm an unashamed fan of Apple. Good design is at the heart of everything they do so, as I always do, I rushed home from work to catch the opening Keynote at this year's WWDC, eagerly anticipating the new software updates we'd all be getting in the autumn.

The updates that were coming with macOS Mojave (version 10.14) were pretty underwhelming: Desktop Stacks and Complete Meta Data, Continuity Camera, News, Stocks, Home and Voice Memos apps, Gallery View, the redesigned App Store. They all looked nice, but wouldn't be all that useful to me.

Quick Actions, on the other hand, would save me time *every day*, [Safari tab icons](/blog/safari-tab-icons) were very exciting, and Group FaceTime has been a long time coming and looked great.

But the most interesting change was **Dark Mode**.


## What's so great about Dark Mode?

Novelty is a thing, and Dark Mode is new and shiny. It feels a bit like iOS 7 or Yosemite on the Mac, where the whole system user interface was overhauled -- a fresh, clean, exciting new experience.

Accessibility is also a thing. Light text on a dark background has been shown to [tire the eyes less](https://usabilitygeek.com/light-dark-ui-usability-perspective/) than the dark text on a light background.

Also, it's a jarring experience when your whole OS is dark but the websites you use aren't; isn't it a good thing to respect our users' colour preferences?


## Using Dark Mode on the web

I'm one of those people who fly three sheets to the wind and install a brand new operating system the minute it is released. And, being one for novelty, I've had my Mac in Dark Mode since being presented with the option when I upgraded to Mojave on the 24th of September.

One of the big disappointments of the new release was the lack of a way to implement Dark Mode on the web, so imagine my excitement when I came across [Paul Miller's post](https://paulmillr.com/posts/using-dark-mode-in-css/) pointing towards Apple's documentation for [Safari Technology Preview 68](https://webkit.org/blog/8475/release-notes-for-safari-technology-preview-68/) which introduces the `prefers-color-scheme` media query!


## Updating my website with Dark Mode

Will Moore from 1Password wrote an excellent article on [Dark Mode](https://blog.1password.com/from-dark-to-light-and-back-again/) and I've borrowed his Sass mixins as a starting point for my website.

### Colours

The first thing I worked on was my Dark Mode colour palette. White text on a black background is way too stark a contrast, making it difficult to read anything beyond headings; not helped by using a [serif font body copy](/blog/tempertempers-typefaces), the letterforms of which start to blur into one another. So I tried darkening the blue I use for tempertemper (`#0097db`), but it felt too cold. Darkening a complementary colour (orange) left it feeling a bit muddy. In the end, I borrowed the background colours from Apple's Dark Mode itself, to provide some consistency with the rest of the apps running on my Mac.

It's worth mentioning that I used the Graphite colours (System Preferences → General → Accent colour) to ensure it was neutral -- non-Graphite colours use Desktop Tinting, which tints the grey to match the colours from your desktop picture.

With the background taken care of, I turned my attention to the  text itself. I softened the white text colour to a very light grey to reduce the contrast further as pure white was still slightly stark.

### Typography

It wasn't just a case of changing to dark background colours and making the text off-white, though. There are a bunch of other typographical tips and tricks for using light text on a dark background.

A slightly increased `line-height` for body text gives it all a bit more breathing space. I also took a leaf out of [Andy Clarke's ~~book~~ blog](https://stuffandnonsense.co.uk/blog/redesigning-your-product-and-website-for-dark-mode) and increased the spacing between words slightly too.

As for the body text itself, I'd've like to have lightened its weight for Dark Mode. If I could've, I would've, but I have to draw the line somewhere. And the line was drawn (for now) at extra expense. You see, I deliberately use a system font to save on http requests, and the system installations of Georgia only have Regular and Bold weights (and italics of each). Since `font-display` is so [well supported](https://caniuse.com/#feat=css-font-rendering-controls) (just Edge left to join the party…), maybe one day I'll invest in [Georgia Pro](https://www.myfonts.com/fonts/ascender/georgia-pro/) so I can get some lighter weights, but for now the adjustments I've already made are enough.


## Wide-spread adoption

All this might be a bit moot as it might not catch on. It's a brand-new, as-yet unreleased addition to a web designer's design toolkit, and it's only in one browser on one operating system.

Admittedly, Apple's upgrade adoption rates are pretty great, but Safari on Mac is still only a small fraction of the overall browser usage ([1.36% worldwide](https://gs.statcounter.com/browser-version-market-share/desktop-mobile-tablet/worldwide/#monthly-201810-201810-bar)).

At the time of writing, neither Chrome nor Firefox (nor Opera) for Mac have adopted Dark Mode for their browser itself, so it's unlikely that they'll be supporting the `prefers-color-scheme` media query any time soon.

I'm surprised iOS 12 didn't come with a Dark Mode too, especially as the iPhones X, XS and XS Max come equipped with OLED, which provides true black (those black pixels don't get switched on!). Many app makers have [tweaked their dark themes to use true black](https://9to5mac.com/2018/10/18/tweetbot-true-dark-mode-gifs/), so that they look great on the OLED iPhones. A system-wide Dark Theme, as well as looking nice and being easier on the eye at night, would also conserve battery---in similar fashion to Apple Watch and watchOS---by only switching on the pixels it needs to. Surely it can't be too far around the corner. And I imagine `prefers-color-scheme`  media queries will ship with it. Of course, that'll mean the grey I've used will have to be a black on small screens, but I'll cross that bridge when I get to it!

Safari on iPhone currently accounts for nearly 13% of browsing world-wide, so totalling 14% with Safari for Mac thrown in there, support for supports `prefers-color-scheme` would be significant. At that point we'll start to see more web designers thinking about a Dark Mode theme for the sites they design and build. Maybe even Windows will follow suit and offer a Dark Mode of some sort. And at one point I hope Chrome and Firefox will also jump on board.

But in the meantime it's a really nice progressive enhancement; a cool thing to do for those who are currently using their Mac with Dark Mode switched on.
