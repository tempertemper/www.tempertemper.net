---
title: I deleted 1Password
intro: I've been dragging my heels a bit as it's such a big job, but this week I deleted 1Password.
date: 2022-04-29
tags:
    - Apple
related:
    - going-all-in-on-icloud-passwords
---

This week, I deleted 1Password.

I've been thinking about it since it was introduced in iOS 15 last year and with [the addition of iCloud Keychain Notes in iOS 15.4](/blog/going-all-in-on-icloud-passwords), for things like memorable words; I was sold. But 1000+ password the migration task ahead felt huge.

I already had plenty of [motivation to leave 1Password](/blog/going-all-in-on-icloud-passwords#the-problems-with-1password), but tipping point finally came when [an advert popped up in the sidebar of 1Password for Mac](https://twitter.com/tempertemper/status/1518512884753616897). An app I've paid for. It was selling a 3 year 50% discount on their subscription plan, so it looks like they're getting desperate to feed those hungry investors…

So I spent a good amount of time [readying my 1Password library for export](https://simonbs.dev/posts/moving-from-1password-to-icloud-keychain/):

- Moving software licenses, membership numbers, and other useful codes and keys to Apple's Notes
- Checking all credit card info was saved in Safari
- Storing things like website database connection details securely
- Ensuring all website usernames and passwords had a URL (Apple needs these three things)

Once all of that preparation was done, though, it was a piece of cake to export my 1Password library and import it to Apple Passwords.


## How it's working out

I'm now happily 1Password free!

Logging in and signing up for services in Safari is now a lot slicker. No more messy clashes with 1Password pop-ups and iCloud Passwords.

Admittedly, it's not as slick when using a browser other than Safari, which is thankfully not all that often. The first time I log in somewhere I need to:

1. open Passwords in System Preferences (Settings on iOS)
2. manually copy and paste login credentials

But browsers do a good at storing those, so it's only really two factor authentication codes I have to copy and paste with any regularity.

I've used Apple's [Shortcuts to create a fake Passwords.app](https://www.icloud.com/shortcuts/73729e64eab14dbf99b5fd74e7e41913) that I can run from Spotlight, my Dock, Menu Bar, or even Touch Bar if I want. This works particularly nicely on iOS where it really does feel like an app of its own. In fact, having used it like this for a few days, Passwords feels like something Apple should consider moving out of System Preferences/Settings and into its own stand-alone app.

Finally, leaving 1Password behind has meant I can [cut my ties with Dropbox](/blog/stop-the-ride-i-want-to-get-off) too. So I no longer have to worry about my MacBook's fan no longer firing up and my battery draining when I'm not doing something where I would expect it (like using a Chromium-based app like Miro).

I'm very glad I put the legwork in to make the switch. Not only did it prove a good way to prune old passwords, and even reminisce over some long dead services and apps, but it's two less apps running in the background. And fewer in-app adverts!
