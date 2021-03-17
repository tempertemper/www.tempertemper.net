---
title: Viewing the changes in a Git stash
intro: |
    Naming stashes is a good idea if some time is likely to pass between stashing and picking up the work again, but sometimes we need even more info.
date: 2021-02-10
tags:
    - Git
    - Development
---

It's a good idea to [name our stashes](/blog/giving-your-git-stash-a-name) if some time is likely to pass between stashing and picking up the work again, but sometimes we need *even more* information, which is where the `show` command comes in:

```git
git stash show
```

This will tell you the following about the most recent stash:

- which files were changed
- the number of changes in each file
- the total number changes in all the files

It's possible to specify the stash too; just [find the index number of the stash](choosing-a-stash-from-the-list) you'd like more details on and run:

```git
git stash show stash@{2}
```

In this example, we'd be looking at the third most recent stash.

This is a nice overview and may be enough to jog your memory, but what if you need even more?


## Viewing the diff

To see the actual changes, line by line, to the files in a stash, we can use the `--patch` flag (or `-p` for short):

```git
git stash show -p
```

This will show the diff of the most recently stashed stash, but of course we can choose any from our list of stashes by adding the index:

```git
git stash show -p stash@{2}
```
