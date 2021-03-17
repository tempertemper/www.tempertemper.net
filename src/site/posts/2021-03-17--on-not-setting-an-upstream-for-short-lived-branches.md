---
title: On not setting an upstream for short-lived branches
intro: |
    I only set upstream Git branches when I need to push and pull a lot, otherwise I prefer to write out my target branch manually. Here's why…
date: 2021-03-17
tags:
    - Git
    - Development
---

I [tried GitHub Flow a while ago](/blog/simplifying-branching-and-deployment-with-github-flow), and, although it's a simpler process, it involved some automation (using GitHub Actions) which I'm yet to delve into, so I'm back using my trusty old Git Flow workflow.

A few weeks ago, [I wrote about setting an upstream](/blog/setting-an-upstream-git-branch) on a local Git branch, where I said:

> if I know I’ll be pushing and pulling a fair bit over the life of a feature (or setting up a ‘forever’ branch like develop or staging), setting an ‘upstream’ makes things quicker

There's a bit more to say about my work where I *don't* set an upstream. All I'd have to do is add the `-u` flag and never have to write the ` origin my-great-feature` bit of the command again, so why wouldn't I add it?

Since I'm using Git Flow, I rarely need to push more than once; after that first push I head to GitHub, raise a pull request, and so on.

The only time I might have to push from a branch a second time would be if I've forgotten to add something, or if some tests failed and I have to fix something. In those situations, I sort of like the extra effort of having to type out `git push origin my-great-feature` in full. That tiny bit of extra effort helps train me to avoid making the mistake again in future, meaning fewer commits overall and, therefore, an easier to read history.

Also, if you add up the overall number of times I'd have to type those three characters (` `, `-`, `u`) versus the characters in ` origin my-great-feature` (or whatever the branch name might be), I'd *definitely* end up typing more if I set an upstream every time. So not setting an upstream is more efficient!

My stance on setting an upstream will probably change when I made a concerted effort to use get to grips with GitHub Actions and use the GitHub Flow workflow again; GitHub flow involves lots of pushing to the server as work is committed. But, for now, I won't be setting any upstreams for short-lived feature branches.
