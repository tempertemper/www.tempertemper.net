---
title: Safari tab icons
intro: |
    I'm a fan of small and seemingly trivial changes that make a big difference, so Safari tabs getting icons in macOS Mojave was cause for celebration.
date: 2018-09-20
tags:
    - Tools
    - Design
---

I'm a fan of small and seemingly trivial changes that make a big difference, so Safari tabs getting icons in macOS Mojave was cause for celebration.

I like Safari. It's fast, doesn't burn through my laptop's battery like Chrome does; I like that bookmarks, favourite and history sync with my iPhone; I like iCloud tabs; I love autofill via my Keychain. It's my go-to for all my general web browsing. (Not for web development, though -- I use Chrome or Firefox interchangeably for work.)

But I have a bad habit. I leave a *lot* of tabs open at any given time. And that means I have to scan through a lot of tabs to find the one I'm looking for. Scanning text is pretty hard -- you have to read at least one word from each of the tabs, and when they're moving as you scroll through, it's even trickier!

Chrome and Firefox have a solution: icons (favicons, to give them their proper name) in the tabs; this makes it quick and easy to identify that page you're looking for. Safari *used* to show favicons, but they removed them in---I assume---a bid to keep the UI as clean as possible.

In August of last year [Daring Fireball wondered](https://daringfireball.net/2017/08/safari_should_display_favicons_in_its_tabs) how many users were choosing Chrome over Safari simply because it showed favicons in the tab:

> There are a huge number of Daring Fireball readers who use Chrome because it shows favicons on tabs and would switch to Safari if it did.

It looks like this got Apple's attention and Mojave shipped with a setting to activate icons to tabs (worth noting they're turned off by default).


## How they did it

I get the whole design purity thing, but sometimes compromises have to be made, and Apple have pulled this one off well. Instead of looking for a favicon first, Safari looks for a [Pinned Tab icon](https://developer.apple.com/library/archive/documentation/AppleApplications/Reference/SafariWebContent/pinnedTabs/pinnedTabs.html). This means:

1. There’s less chance of a fuzzy, non-Retina favicon being used
2. The icon will be monochrome, providing some kind of minimalism that a favicon might not

This update to Safari addresses my only real gripe and goes to prove that the small changes sometimes have the biggest impact.

