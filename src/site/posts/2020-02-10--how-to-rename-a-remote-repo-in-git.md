---
title: How to rename a remote repo in Git
intro: |
    Renaming a Git repository feels pretty fundamental -- surely something will break? Well, worry no more -- it turns out it's a piece of cake!
date: 2020-02-10
tags:
    - Git
    - Development
---

When I made the move to <abbr title="Command Line Interface">CLI</abbr>-only Git last summer I realised that I had been in the habit of naming my repositories after the project. So, for example, Project X's repo would be named `project-x`.

This was fine when I was clicking around a <abbr title="Graphical User Interface">GUI</abbr> but it quickly got annoying when working on the command line, where my fingers always went to type `origin` (the conventional remote name). So instead of `git push remote origin feature/feature-name` I would have to type `git push remote project-x feature/feature-name`.

I did have some teething problems with this one -- the first time I tried it, I must've done something wrong as it caused all sorts of problems that I had to go back to my GUI for and fix, then re-track all the remote branches. So having tried, I thought it was complicated, and resolved to put up with all the unique and often lengthy remote names. But the other day, I finally got fed up, took a deep breath, and tried again.

It turns out it's very simple to rename a remote… In fact *that's* the command: `rename`! And the good news is, once renamed, all the tracking you set up stays intact: no need to do any `git branch -u remote-branch-name` as `master` will still be tracking `master`, `develop` will still be hooked up to `develop`, etc.

Before you do this, be sure you tell anyone else who might be working in the same repo -- they'll need to re-attach to the newly named remote.


## How it's done

Start with a quick `git remote` to see the name of your remote repositories. Copy and paste the name of the one you want to change (maybe a typo was my problem that first time?) and run this command, pasting your remote name over the top of `project-x`:

```git
git remote rename project-x origin
```

And that's it!

Pushing, pulling and fetching will now work exactly as they did before, but when you need to type the remote name it'll be the consistent and standard `origin`.

<i>Note: you can call it whatever you want, of course. That last value of the command in the example is the name you want to rename the repo *to*. Just swap `origin` for `project-y` or whatever you like!</i>

