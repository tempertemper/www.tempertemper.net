---
title: How to diff branches in Git
intro: |
    Turns out it's pretty easy to look at the differences between two branches in Git. This is useful when coming back to a feature branch after a while.
date: 2020-01-11
tags:
    - Git
    - Development
---

After posting my article on [rebasing versus merging](/blog/git-rebase-versus-merge) in Git, I decided to do a bit of digging around `git log`, and it turns out it can solve the issues I was having with merging: quickly and cleanly seeing what I've done relative to master.


## Branch diffs

We're talking about commits here, so a `git diff` isn't what we're after -- that would give us the unstaged changes in the files themselves. We want a higher-level overview of the changes we've already made and commmitted on a branch.

`git log` does more than I first thought -- you can use it to view *only the commits that're different* from the base branch. Very useful! This is how:

```git
git log master..
```

<i>Note: the `..` isn't a typo!</i>

That assumes you're currently checked out on your working branch. If you're not, all you have to do is specify the branch immediately after the two full-stops:

```git
git log master..working-branch-name
```


## Ignoring merge commits

As with most Git commands, `git log` comes with a whole bunch of options. People do all sorts of clever stuff using the `--pretty` flag, but it's so complex and fiddly that typing it all out each time would be near-impossible. That's where aliases come in , but while I'm learning Git on the command line, I'm trying to [steer away from customisation](/blog/why-im-not-using-git-aliases). Luckily there's a built-in option to remove the merge commits from the log view: `--no-merges`.

You can also make the commit log more readable at a glance with `--oneline`.

So to view a nice tidy version of the log, use:

```git
git log --oneline --no-merges
```

And to roll this up with our branch diff:

```git
git log master.. --oneline --no-merges
```

That solves the two [downsides I listed with merging](/blog/git-rebase-versus-merge#cons-of-merging). Is it a reason to go back to merging? It could be. I'm very early in my `git rebase` journey, so I'm going to stick it out for a bit longer.



