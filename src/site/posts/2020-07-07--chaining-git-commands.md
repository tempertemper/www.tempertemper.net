---
title: Chaining Git commands
intro: |
    Writing a sequence of Git commands is really handy and much quicker than running one, waiting for it to finish, writing the next, etc. Here's how.
date: 2020-07-07
tags:
    - Development
    - Git
---

Something I like to do to fill the time while waiting for tests to run (and hopefully pass!) on a branch that I'm going to merge to `master` is write a chain of Git commands.

Here's an example. I use GitFlow ([GitHub Flow didn't quite stick](/blog/simplifying-branching-and-deployment-with-github-flow)) so here's how I tidy up after a hotfix:

1. Jump from my `hotfix` branch back to `master`, where the fix has been merged to on the remote
2. Pull the fix down to keep my local `master` in sync
3. Delete the local hotfix branch, now that the work's in `master`
4. Jump to `develop` with a view to merging the fix over there
5. Merge `master` into `develop`
6. Push `develop` to its remote namesake to avoid any potential remote merge conflicts
7. Fetch the remote repo and prune any dead branches up there

Here's what the command would look like:

```git
git checkout master && git pull && git branch -d hotfix/fixing-a-thing && git checkout develop && git merge master && git push && git fetch -p
```

So all you've got to do is write `&&` between each command and you can get Git to do a whole bunch of things in sequence.

Note: I could make this an Alias in my `.gitconfig` file, but [doing it longhand that feels better](/blog/why-im-not-using-git-aliases).
