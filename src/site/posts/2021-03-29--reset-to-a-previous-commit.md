---
title: Reset to a previous commit
intro: |
    Have you ever made a bunch of commits on the wrong branch? I certainly have… Luckily, there's an easy way to put things right.
date: 2021-03-29
tags:
    - Git
    - Development
---

Have you ever made a bunch of commits before realising you've been working directly on the [`main` branch](/blog/empathy-and-renaming-my-master-branch-to-main)? I certainly have…

If you've committed work on a branch that you shouldn't be committing work to directly, don't panic: there's an easy way to put things right!

Let's assume the work is good and you want to keep it, but:

- it should have been committed on its own feature branch
- you need to put `main` back the way it was before you made the mistake


## Make a branch with your work

The first thing you need to do is create a branch with all your work on it, which'll keep it safe:

```git
git checkout -b feature-i-forgot-to-make-a-branch-for
```

You can then jump back to the branch you were on:

```git
git checkout main
```


## Starting again

Next we need to go back in time to the last commit before we started committing straight to the `main` branch.

It's worth mentioning that what we want to do is different to rolling back to a previous commit for a quick look around, before jumping back to the 'present day' again, which I mention in my article on [changing your Git history](/blog/changing-your-git-history). Instead, we're looking to move back to a commit and *throw out* all of the commits you added in error.

So we need to view the list of commits and grab the hash/ID of the one immediately before the work we did, so let's have a look at the log:

```git
git log --oneline
```

As long as you haven't run a `git fetch` when more work has been committed to the remote by another team member, you should spot something like:

```git
abc1234 (origin/main, main) This is the commit message
```

This is telling you where things were before you started work on the wrong branch, so it should be easy to pick out of the list.

If some work has been committed and you've run `git fetch`, you'll still be able to find that commit; just work your way back from the top to the commit before your first erroneous commit.

Copy the hash for the commit and the next step is to tell git to go back to that commit on this branch and discard all the commits that were done after it. Remember, all that work we're losing on this branch has been safely moved to your new `feature-i-forgot-to-make-a-branch-for` branch.

```git
git reset --hard abc1234
```

Your `main` branch should now be exactly the way it was before you committed that work directly to it, so you can now submit a pull request using your new branch in the normal way.
