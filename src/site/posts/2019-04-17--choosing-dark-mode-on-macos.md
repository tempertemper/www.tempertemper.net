---
title: Choosing Dark Mode on macOS
intro: |
    It's nice to dust the cobwebs off with a UI overhaul every so often, so when macOS Mojave was released last year I couldn't wait to try Dark Mode.
date: 2019-04-17
tags:
    - Design
    - Apple
---

It's nice to dust the cobwebs off with a user interface overhaul every now and then. iOS 7 (which was admittedly more than a superficial change), macOS Yosemite (which modernised the long-standing Aqua theme), even a fresh Sublime Text theme every now and then; I'm a sucker for a look-and-feel overhaul!

So when macOS Mojave was released late last year I wanted to try Dark Mode right away.

As a developer I'm used to working in apps with a dark background---I've used a dark theme in my text editor for as long as I can remember and terminal comes with a default white text on a black background---but it still felt a bit disorienting when the *whole operating system* was dark.

In fact, I think I might have quickly abandoned it in favour of the familiar light grey UI 'chrome' but for one thing: the menu bar.

Yosemite introduced the dark menu bar and I've always preferred it. Mainly because I use a MacBook Pro which has a black bezel around the screen, and the black menu bar felt *right* as it felt more like a part of the hardware than the app being used. This separation made sense to me as the battery percentage, date and time, wifi icon, etc. aren't part of the app you're using -- they're system-wide. Also, having the app name and its menu items up there felt more distinct from the rest of the UI, making it easier to know which of the many apps I'm normally running currently had focus.

I've since found out there's a way to [run the light theme with a dark menu bar](//osxdaily.com/2018/10/15/dark-menu-dock-light-theme-macos/):

1. Go to System Preferences → General → Appearance
2. Choose 'Light'
3. Go to Terminal and run `defaults write -g NSRequiresAquaSystemAppearance -bool Yes`
2. Log out of your user account
3. Log back into your user account
4. Go to System Preferences → General → Appearance
5. Choose 'Dark'

But I'm glad I didn't know about that hack. I don't have the distinction between the menu bar and the apps I'm running, but I quickly got used to Dark Mode and, as more and more third party apps and even [websites](/blog/dark-mode-websites-on-macos-mojave) add support, adding more consistency, it feels good to have a choice!
