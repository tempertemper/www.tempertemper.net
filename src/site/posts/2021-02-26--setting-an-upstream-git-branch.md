---
title: Setting an upstream Git branch
intro: |
    There's no such thing as syncing in Git, but setting an upstream branch is about as close as it gets.
date: 2021-02-26
updated: 2021-04-01
tags:
    - Git
    - Development
---

There's no such thing as syncing in Git, but setting an upstream branch is about as close as it gets. What that means is you match a branch on your local development environment to a branch on the remote repository (repo), up in GitHub, GitLab or wherever.


## Pushing and pulling without an upstream

By default, you can push and pull changes from any branch on your remote to the local branch you're currently sitting on.

So if you're checked out on a branch called `my-great-feature` you can push a branch of the same name to the remote like this:

```git
git push origin my-great-feature
```

If a branch called `my-great-feature` doesn't already exist up there, that command will create one with that name, based on your local branch.

To push more changes up there, just repeat the command.

If you're working with someone else, or have been working on the same branch from two separate machines, you can pull changes down like this:

```git
git pull origin my-great-feature
```

I tend to push my work up to the remote when it's ready for PR (Pull Request), so my feature branches don't typically live all that long once they're on the remote repo. That means I'm generally happy to write those longer commands when pushing and pulling.


## Pushing and pulling with an upstream

Every now and again, if I know I'll be pushing and pulling a fair bit over the life of a feature (or [setting up a 'forever' branch](/blog/setting-up-a-staging-site-with-netlify) like `develop` or `staging`), setting an 'upstream' makes things quicker.

The flag to set an upstream branch is `--set-upstream-to`; that's a lot to type! Luckily there's a shorthand:

```git
git push -u origin my-great-feature
```

This creates a branch on your remote called `my-great-feature` and links it to your currently checked out local branch via the `-u` flag. If the `my-great-feature` branch already exists up on your remote, it just creates the link between it and your local branch.

It's worth mentioning here that your local branch has to be called `my-great-feature` for this to work. If you want your remote branch to have a different name to your local branch, say `our-great-feature`, you need to be explicit:

```git
git push -u origin my-great-feature:our-great-feature
```

From there on in, when you push and pull from your local `my-great-feature` branch, you won't need to tell Git the name of the the remote repo and branch you're pushing/pulling to/from again: all you need is `git push` and `git pull`!
