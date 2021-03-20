---
title: Choosing a stash from the list
intro: |
    Viewing all of a Git repository's stashes and choosing one from the list is the next step I took in my Git stash on the command line journey.
date: 2021-02-04
updated: 2021-02-09
tags:
    - Git
    - Development
---

[Git stash is easy to get started with](/blog/getting-started-with-git-stash) but what if you stash more than once? You might want to have a look at your stashes:

```git
git stash list
```

This brings up a list of all of the stashes in your current repository (which is much easier understand if you've [named your stashes](/blog/giving-your-git-stash-a-name)); to choose a particular stash, all you have to do is find the index of the stash:

1. View your list of stashes
2. Copy the stash index number (which looks something like `stash@{2}`)
3. Close the list with <kbd>⌃</kbd> (Control) + `z`

Now we just add the stash's index to our [`pop` command](/blog/getting-started-with-git-stash#applying-the-stash):

```git
git stash pop stash@{2}
```

If you know without having to look where the stash will be in the list, you might not want to view the list before applying the stash, but bear in mind:

- Stashes are numbered in reverse order, so the most recent always has the lowest index number
- The index numbers change with each new stash that's added, effectively getting pushed back in the queue
- Stashes are 'zero indexed', so the most recent stash would be `stash@{0}`, the next most recent `stash@{1}`, and so on

So, for example, if you've stashed four times and you want the second stash you made, you'd run `git stash pop stash@{2}`.

If you stash a fifth time before grabbing that second stash, you'd run `git stash pop stash@{3}` as there are now 5 items: 0, 1, 2, 3, and 4.
