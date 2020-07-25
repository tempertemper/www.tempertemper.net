---
title: A handy Git shortcut to fetch and prune
intro: |
    I'm still writing my Git commands long-hand. Turns out a fetch and prune can be more concise than I've previously suggested, all without aliases.
date: 2020-07-08
tags:
    - Development
    - Git
---

I'm still writing my Git commands long-hand. No aliases. In revisiting an article from last year, it struck me that there's a built in shortcut to perform a [command that I gave as an example](/blog/why-im-not-using-git-aliases):

> It bugs me that pruning doesn’t happen with a fetch, like it does in Tower, so aliasing `git fetch && git remote prune origin` to `gf` or something like that would be lovely

Well, it turns out there's a `-prune` flag for the `fetch` command in Git! So while aliasing a fetch and a prune to `gf` would be going too far (for me), `git fetch && git remote prune origin` can be written as `git fetch -prune`, and that's a proper Git command. Even better, the `-prune` flag can be shortened to `-p`! So the shortcut is:

```git
git fetch -p
```

Now I can refresh my view of the remote repo while at the same time getting rid of 'dead' branches up on my remote, all less than half the characters!
