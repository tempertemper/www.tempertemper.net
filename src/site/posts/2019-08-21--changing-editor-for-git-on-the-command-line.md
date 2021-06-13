---
title: Changing editor for Git on the command line
intro: |
    Something that has been bugging me since moving from a GUI to command line git has been the default editor for writing commit messages.
date: 2019-08-21
tags:
    - Development
    - Git
summaryImage: large
---

Something that has been bugging me since moving from a GUI to command line git has been the default editor for writing commit messages.

Most of the time I use a `-m` flag and type the message in the command, between `"`s, e.g. `git commit -m "This is the commit message"`. But when I [amend a commit](/blog/fixing-your-last-git-commit) or just run `git commit` on its own (I can sometimes be a bit trigger-happy with that <kbd title="Return">⏎</kbd> key!) [I'm in VIM](https://www.freecodecamp.org/news/one-out-of-every-20-000-stack-overflow-visitors-is-just-trying-to-exit-vim-5a6b6175e7b6/)…

From there I have to remember to hit `i` to insert text, then when I'm ready, hit `esc`, then `:`, then `wq` to write and quit. I'm not *opposed* to Vim; I've even got it on my 'Things to learn' list, but now's not the time to learn it -- learning Git on the command line is quite enough right now!


## Enter Nano

I'm much more comfortable with Nano. Its commands are familiar for a Mac user like me, and I've been using it for years to edit config files on servers. So until the time comes to [learn Vim](https://vim-adventures.com), Nano's the most sensible editor to use for my commit messages.


## How prescriptive?

You can change your git editor at one of three levels:

1. Per computer
2. Per user
3. Per repo

I'm the only person who uses my Mac, so option 1 would work, but it feels like the wrong approach. I don't want to dictate that kind of thing to any other potential users of my machine.

I'm a big fan of [.editorconfig files](https://editorconfig.org) but being *too* prescriptive about how another developer edits a project isn't right. Option 3 is out.

So option 2 strikes the right balance -- it's about *me*, not about my Mac or the project.


## Making the switch

Changing the editor from Vim to Nano is pretty straightforward. It can be done in a couple of ways:

- Running a command
- Editing the your user's git config file

In truth, running the command just updates the config file anyway, so it's kind of six-and-two-threes. I could just tell you the command, but I found it quite interesting to see the config file.

### Via the command line

Just run `git config --global core.editor "nano"` and that's it! From now on you'll edit your git commit messages using Nano rather than Vim!

### Via your .gitconfig file

If you're in your terminal, head to your .gitconfig file with `nano ~/.gitconfig` and make your changes.

If you prefer to use Finder, head to your user's home directory and hit <kbd title="Command">⌘</kbd> + <kbd title="Shift">⇧</kbd> + <kbd title="Full stop">.</kbd> to show your hidden files, if you haven't got this set already (Hide them again with another <kbd title="Command">⌘</kbd> + <kbd title="Shift">⇧</kbd> + <kbd title="Full stop">.</kbd>). You'll see the .gitconfig file, which you can right-click and 'Open with' your favourite text editor. Add `editor = nano` to `[core]` like this:

```
[core]
  editor = nano
```

Oh, and don't forget to save the file!

If you're just having a look at your .gitconfig file having already run the command, that line will already be there.
