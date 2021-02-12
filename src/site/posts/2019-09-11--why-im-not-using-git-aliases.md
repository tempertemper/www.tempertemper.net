---
title: Why I'm not using Git aliases
intro: |
    Git aliases are incredibly useful, but there are five good reasons I've decided not to make use of them.
date: 2019-09-11
tags:
    - Development
    - Git
---

I've found my perfect Mac set up. Over the years, I've adjusted the trackpad tracking speed so it's *just right*, [switched to Dark Mode](/blog/choosing-dark-mode-on-macos), added Hot Corners to show my desktop, set up my own keyboard shortcuts; that kind of thing.

I know people who go even further, using [Apple Script](https://developer.apple.com/library/archive/documentation/AppleScript/Conceptual/AppleScriptLangGuide/introduction/ASLR_intro.html) and programs like [Keyboard Maestro](https://www.keyboardmaestro.com/main/), customising and automating every little detail of their operating system.

On the other hand, I have a friend who switches on a brand-new Mac and doesn't change a thing. If a company like Apple, that cares so much about user experience and the *right* way to do things, decides that the mouse pointer should move a certain speed, that's good enough for him. One area I wholeheartedly agree with his philosophy is Git commands.

In my (so far successful) quest to [ditch the GUI and dig into command line Git](/blog/getting-to-grips-with-git) my attention has been grabbed a few times by aliases.

Not only do they look like a very quick way of doing things like `git remote prune origin`, but they'd stop mis-commands like `git prune remote origin` or `git prune origin`. It bugs me that pruning doesn't happen with a fetch, like it does in Tower, so aliasing `git fetch && git remote prune origin` to `gf` or something like that would be lovely. But there are a few reasons I don't want to.

1. Maybe there's a good reason for things like my fetch/prune example happening the way it does; as I continue my Adventures in Git™, maybe one day I'll work it out. People that are way cleverer than me designed it that way, so I trust their judgement.
2. There's something in doing a thing *properly* that appeals to me. I don't want to take shortcuts -- I want to get as close to the metal as possible. I knew CSS extremely well before SCSS came along, and I've never been keen on writing JQuery -- where possible, give me vanilla JavaScript any day.
3. If I don't alias *everything*, how will I remember what's an alias and what's not!?
4. I like to teach people what I know, so if I start using aliases, I'll have to translate the aliases I use to the *actual* Git commands. Sometimes I might refer to the alias accidentally and cost someone time and energy as they work out that the command I've given them doesn't actually exist outside of an alias.
5. I always liked that [Tower](https://www.git-tower.com) uses proper Git terminology, where [GitHub Desktop](https://desktop.github.com/), for example, changes 'push' and 'pull' to 'sync'. There's risk that, with my `gf` example earlier, I'd forget what is actually happening under the hood.

Aliases feel like the same thing as a GUI -- a layer of abstraction. And that's something I'm looking to get away from.
