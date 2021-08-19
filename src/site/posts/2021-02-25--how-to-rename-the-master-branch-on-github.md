---
title: How to rename the 'master' branch on GitHub
intro: |
    So renaming `master` to `main` is a good idea, but how do we do it? Fortunately, it's really straightforward if your repository lives on GitHub.
date: 2021-02-25
tags:
    - Git
    - Development
updated: 2021-02-26
---

So [renaming `master` to `main` is a good idea](/blog/empathy-and-renaming-my-master-branch-to-main), but how do we do it? It's really easy if you're using GitHub:

1. Go to your repository (repo) and find the 'Branches' link (including a wee branch icon and the number of existing branches), or navigate to `https://github.com/myUserName/myRepoName/branches`
2. In the 'Your branches' section, hit the pencil icon on the `master` line
3. In the 'Rename this branch' dialog, get rid of the word `master`, type `main`, and press 'Rename branch'

That updates the branch name centrally; next we need to update the branch name on all of the machines that connect to this repo, run three commands:

```git
git branch -m master main
git fetch origin
git branch -u origin/main main
```

You can just blindly copy and paste that code block to run all three, but let's break down what each line is doing:

1. First we rename the local branch from `master` to `main`, to match what we've just done on the remote repo: `git branch -m master main`
2. Next, we get the most up to date info from the remote repo (in other words, the fact that `master` is gone and there's a 'new' `main` branch): `git fetch origin`
3. Lastly, we [set the 'upstream' branch](/blog/setting-an-upstream-git-branch) to `main` for your local `main` branch, so that pushing and pulling without specifying the branch is possible `git branch -u origin/main main`

Don't forget that anything you've got that watches for changes to your `master` branch, like [Netlify build hooks](/blog/updating-netlify-deployments-when-renaming-your-main-git-branch), will need to be updated to watch `main` instead.
