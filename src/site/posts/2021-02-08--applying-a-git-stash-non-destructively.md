---
title: Applying a Git stash non-destructively
intro: |
    You'll almost always want to delete a stash when you apply it, but if for some reason you need to keep the stash around, Git lets you do that.
date: 2021-02-08
tags:
    - Git
    - Development
---

`git stash pop` grabs a stash and dumps the changes on your current branch. It also *deletes* the stash. It can sometimes be useful to keep the stash if it's something that you want to reapply on another branch.

```git
git stash apply
```

Like `git stash pop`, you can [apply a particular stash](choosing-a-stash-from-the-list), rather than the most recently stashed stash. So if you want to apply the changes made in the next-to-last stash, as well as keep the stash in your stash list:

```git
git stash apply stash@{1}
```
