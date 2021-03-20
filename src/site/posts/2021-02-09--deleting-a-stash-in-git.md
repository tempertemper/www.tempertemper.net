---
title: Deleting a stash in Git
intro: |
    If you stash a lot, or need to apply a stash non-destructively you might eventually want to clear things down to keep your stash list tidy.
date: 2021-02-09
tags:
    - Git
    - Development
---

If you've [applied a stash non-destructively](applying-a-git-stash-non-destructively) you might eventually want to clear things down to keep your stash list tidy.


## Delete the most recent stash

To get rid of the most recent stash in your list:

```git
git stash drop
```

You won't get any "Are you sure you want to delete this?" warnings, so be sure you're happy to lose the changes in that stash.


## Delete a specific stash

If you want to keep your most recent stash and get rid of an earlier stash instead, you can [check your list of stashes](choosing-a-stash-from-the-list) add the stash index to the `drop` command:

```git
git stash drop stash@{1}
```


## Delete all stashes

If you're happy to delete *all of your stashes at once* there's a command for that. Again, bear in mind there are *no warnings*, so if you run this command you're going to lose all of your stashed changes.

```git
git stash clear
```
