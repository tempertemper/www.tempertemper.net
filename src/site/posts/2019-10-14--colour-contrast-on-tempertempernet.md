---
title: Colour contrast on tempertemper.net
intro: |
    Colour contrast on my site now meets WCAG AAA, in light or dark mode. There have been compromises but, if it's more useable, I'm happy to make them.
date: 2019-10-14
tags:
    - Accessibility
    - Brand
    - Design
---

Over the last year I've been keen to practice what I preach where it comes to accessibility on my website. It has always been pretty solid, but it's something I never quite made the time to give *proper* attention to. This has always a rankled as I'm a big proponent of accessibility on the projects I get paid to work on, so it should be the same for my personal stuff!

Accessibility is easy to design/build in when you're working from scratch, but when you're dealing with a years-old website that has had changed a *lot* over those years, it's trickier.

Well, I'm please to say that tempertemper.net is now [WCAG Standard](https://www.w3.org/WAI/standards-guidelines/wcag/) AAA compliant for colour contrast.

Over the course of this year, I've been busy fixing all sorts of  small accessibility issues on tempertemper.net but colour contrast felt a bit more complicated (you'll soon find out why), so I put it off. But I've finally bitten the bullet and addressed it, and I'm pretty pleased with the results.

It's all about that blue I use for my brand. It comes from an for-screen approximation of the CMYK (print) value for pure cyan -- `CMYK(255,0,0,0)`. That was something I thought was quite clever: I'd never have to pay the printers extra for a Pantone colour to avoid [those tiny dots](https://www.formaxprinting.com/blog/2018/09/printing-lingo-what-is-4-color-process-printing/). But I don't remember the last time I actually had anything printed, so I'm not sure how useful that has actually proved!


## The problem with cyan

The main issues I was facing were:

- `#0097db` against white (`#ffffff`) had a contrast ratio of 3.25:1, which passed WCAG Standard AA for large text but failed AAA
- `#0097db` against `#2c2c2c`, the background I use for the [Dark Mode variant of my website](/blog/dark-mode-websites-on-macos-mojave) had a contrast ratio of 4.28:1, which, again, passed WCAG Standard AA for large text but failed AAA

[Deque University defines large text](https://dequeuniversity.com/rules/axe/3.3/color-contrast) as:

> 18pt (24 CSS pixels) or 14pt bold (19 CSS pixels)

The smallest text on my website on the smallest screens comes out at 19.2px, so I'm more than confident I meet AA accessibility. *But I want AAA*.


## Damned if you do, damned if you don't

So I needed to find a new brand colour that:

1. Was accessible to AAA against a light background (`#ffffff`)
2. Was accessible to AAA against a dark background (`#2c2c2c`)
3. Still looked on-brand

I spent a long time trying colour after colour but anything that satisfied two of those criteria failed the third. It was clear that I was going to have to use **two separate colours** in order for my site to be accessible to AAA.


## The web is about flexibility

Designing for the web is an exercise in letting go of things looking exactly the same everywhere -- we have different screen sizes, operating systems, browsers, screen resolutions; the list goes on. Colour is one of the most wildly variable things, even when you look at higher-end displays where a 2015 MacBook Pro has a very different feel to a brand new MacBook Pro with True Tone.

Is it easy to tell the difference between the blue I'm using with a dark background and the blue I'm using with a light background? Maybe. One's brighter and the other's duller. But they'll always *be used in isolation*. Just as no *normal* user resizes the screen to see how a site might adjust for mobile, nobody (except the likes of me) is opening their System Preferences window over a website and toggling between light and dark modes.


## Loosening up

`hover`, `focus` and `active` states on links and buttons meant I was already using a slightly darkened or lightened variant of my brand blue, so I had already deviated slightly from that one colour in certain circumstances. This meant I felt comfortable enough to go a wee bit further use one colour against a light background and another against a dark in order to meet AAA and still *look* on brand in both cases.

I kept the same <i>hue</i> (the `H` in `HSB`) value and was able to keep the same <i>saturation</i> (`S`) value in all but one situation, then simply adjusted the <i>brightness</i> (`B`) to increase the contrast depending on the background colour.

This held true across the site, from links in text, header links, form inputs, buttons; even with variants of all those things on the box-out panels which use a very light blue background in Light Mode and a slightly lighter dark-grey in Dark Mode. I ended up with six colours in total: three for Light Mode and three for Dark Mode, with a primary, lighter variant and darker variant for each. Here's what my SCSS looks like:

```scss
// Default/Light Mode colours
$colour-primary: #007CBA; // HSB(200,100,73)
$colour-primary-lighter: #008DD1; // HSB(200,100,82)
$colour-primary-darker: #0073AB; // HSB(200,100,67)

// Dark Mode colours
$colour-dark-mode-primary: #00A0F0; // HSB(200,100,94)
$colour-dark-mode-primary-lighter: #19B3FF; // HSB(200,90,100)
$colour-dark-mode-primary-darker: #008FD6; // HSB(200,100,84)
```


## As close it's possible to get is good enough

It has to be said that the slightly darkened blue looks a bit less vibrant than the original against a white background. I'm conscious of this but not too worried -- there was always going to be a compromise somewhere.

I'm also aware that the lighter blue on a light background and the darker blue agains a dark background doesn't quite meet AAA, but I only use these for hover states, where they user has already identified a link or clickable area.


## Users first

So I'm ok with using two variants of that cyan if it means a better experience for some users of my site. And if I ever decide to get a run of business cards again, I'm going to steam ahead with `CMYK(255,0,0,0)` anyway -- it's close enough!
