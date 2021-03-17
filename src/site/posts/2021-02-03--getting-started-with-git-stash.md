---
title: Getting started with Git stash
intro: |
    I've put it off for the longest time, but it turns out stashing changes with Git on the command line is surprisingly easy to get the hang of.
date: 2021-02-03
tags:
    - Git
    - Development
---

Normally, switching branches when there are some uncommitted changes is fine; the modified files just follow me when I checkout another branch. But sometimes I get this:

```git
error: Your local changes to the following files would be overwritten by checkout:
  src/site/_layouts/home.html
Please commit your changes or stash them before you switch branches.
Aborting
```

I've always been a bit scared of `git stash` since [I started using Git on the command line](/blog/getting-to-grips-with-git). It was easy in Tower, where it nice and visual, so I've been doing naughty stuff like:

1. copying the file
2. undoing the changes
3. switching branches, error-free
4. pasting or redoing the changes back in

Aside from being mistake-prone and messy, sometimes that method is just not possible, like when I've [used Patch mode](/blog/staging-different-parts-of-the-same-file-with-git) where the most recent changes to the file might already have been committed, so undoing changes doesn't undo the right stuff.


## Stashing

Git stash gives us a place to store those `git checkout`-preventing changes so we can switch branch then apply them somewhere else.

```git
git stash
```

That takes everything you've changed and makes a 'stash' with it.

But it doesn't stash anything new, so if you have any untracked files, they won't be stashed. To include untracked files in your stash, add the `--include-untracked` flag; luckily there's a shorthand:

```git
git stash -u
```


## Applying the stash

Once you've moved to the branch you want to be on, just use:

```git
git stash pop
```

This takes the changes out of your stash and applies them to the new branch; nothing is staged or committed.

At its most basic, it's as simple as that. There's a lot more you can do with `git stash`, but to move from branch to branch without any errors, or move some work out of the way to tackle something else, that's all that's needed. Not as scary as I'd thought!
