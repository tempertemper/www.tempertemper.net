---
title: Git rebase versus merge
intro: |
    There are two ways to get a branch up to date with master before raising a PR: merge and rebase. Here are pros and cons with each.
date: 2019-12-31
tags:
    - Git
    - Development
---

I've been thinking a lot about Git over the last 6 months since moving from a GUI to the command line. One thing I've heard a bit of noise about whether `git rebase` is better than `git merge` to get a branch up to date and pre-emptively fix any merge conflicts before a pull/merge request (PR) is raised.

With the application I used for Git, it was easiest to drag a branch (e.g. `master`) onto the currently checked-out branch to bring it up to date – it would merge the changes in. You *can* rebase in GUIs like [Tower](https://www.git-tower.com), but merging was so easy that I never really felt the need to take it for a test run. Merging is what I always did, so I continued when I moved to the command line.

Now that I've been using the command line for Git for over 6 months, I've begun to think differently about using `merge` like that. Maybe `rebase` is better. Here's a quick run-down of the pros and cons of each.


## Pros of merging

- <b>Easy to push and pull</b> to/from remote
- <b>Respects the timeline</b> of when things were done


## Cons of merging

- <b>Clutter</b> – merging one branch into another creates a merge commit, so there will be merge commits whenever work has been merged in
- It can be <b>difficult to find the new work</b> with `git log`, as the commits may be peppered amongst other work in the timeline; this is especially true if changes have been made sporadically over a long-ish running branch


## Pros of rebasing

- Rebasing is <b>tidy</b> it doesn't create merge commits, so there's not clutter in the log
- It's <b>easy to see what has been done</b> as it puts new work at the top of the `git log`


## Cons of rebasing

- Rebasing <b>rewrites history</b>, so even if commits that are being merged in were made after those in the feature branch they're placed before them
- <b>You can't push commits to the remote</b> as the histories are different – a force push (`git push -f origin example-branch`) is needed to which overwrite the history on the remote with that of the local


## Where I sit

For me, the big advantage of using `rebase` over `merge` is that I can quickly and easily see exactly what I've been doing with a `git log`. Yes, it rewrites history, but it doesn't change the time stamps on the commits, just the order that the commits appear in the log. Merging respects the timeline of when things were done, but I'm happy to sacrifice that authenticity in the name of tidiness and scanability.

