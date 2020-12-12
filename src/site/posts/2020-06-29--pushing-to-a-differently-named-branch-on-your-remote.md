---
title: Pushing to a differently named branch on your remote
intro: |
    When using Git, you'll normally push work to an identically named branch on your remote, but what if you want to push to a different branch?
date: 2020-06-29
tags:
    - Development
    - Git
---

When working with Git, it's pretty normal to work on a branch locally and push it to an identically named branch on your remote (in GitHub/GitLab/Bitbucket/etc.).

```git
git push origin my-great-feature
```

This command tells Git to push the work from your local feature branch to the remote repository, using the same branch name (`my-great-feature`) to either:

- create the `my-great-feature` branch if it doesn't already exist up there
- add the work since your last push to the `my-great-feature` branch you already created

But sometimes you might want to push to a branch that's not called the same thing as your local branch.

Admittedly, it's not very often I have to do that, but here are a couple of recent examples:

- I needed to demo some functionality for a client on a central staging URL that was linked to a `staging` branch
- I wanted to test a redirect on a remote server

In both cases, I could create a `staging` branch locally, merge the work from the feature branch to it, then push, but that feels like extra steps when there's a simpler way:

```git
git push origin my-great-feature:staging
```

This tells git to push the work from your local `my-great-feature` branch to a remote branch named `staging`.

You might run into conflicts if you've used the staging branch for experimental things before, in which case just force your branch to overwrite what's on your remove with the `-force` (or `-f`) flag:

```git
git push -f origin my-great-feature:staging
```

Even better, if you've set up [automatic deployments to your staging branch with something like Netlify](/blog/setting-up-a-staging-site-with-netlify), the work will appear on your staging site automatically!
