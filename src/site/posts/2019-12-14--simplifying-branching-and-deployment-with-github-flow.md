---
title: Simplifying branching and deployment with GitHub Flow
intro: |
    GitFlow is great but it's not quite right for every project. GitHub Flow is simpler and means I'll publish a feature or fix as soon as it's ready.
date: 2019-12-14
updated: 2019-12-27
tags:
    - Git
    - Development
---

I've been using [GitFlow](https://www.tempertemper.net/blog/version-control-for-articles-and-blog-posts) for a long time. It's a solid system for developing collaboratively and ensuring changes are made in a controlled, granular fashion. But it's not unusual that I sit on a release for over a week; all the while building little features and fixing the odd cheeky bug, but holding onto it all until it feels ready to be called 'release'.

This is fine on larger projects, but for my own website and one or two other projects I work on it's too heavy. Ideally I'd want the bug fixes and features to be released the moment they are ready.

[GitHub Flow](https://githubflow.github.io) is the answer for those projects where I want to stay light-weight and ship as regularly as possible.


## No more release branches

With GitHub Flow, a release should be made every time a feature or fix is made, instead of raising pull requests (PRs) for features and fixes and a separate PR when a release feels appropriate.

This means that updates happen the moment they're ready, rather than waiting to be bundled in a release.


## Simplified branching

Rather than maintaining both a `master` and `develop` branch, GitHub Flow only needs `master`. Features, not just hotfixes, are branched from `master` and merged back into `master` via their PR.

With my website's setup, when the work is merged into `master` on the remote, a deployment is triggered and the work goes live. I'll then pull `master` back down and continue work.

I'll probably still prefix my branch names with `hotfix/`, `feature/`, `post/`, etc. so that it's easier to identify what type of work was involved at a glance.


## The drawbacks

My only concern is versioning. With GitFlow, I use the release branches to:

1. update the changelog
2. bump the version number
3. tag the commit

I've been doing 1. manually, and 2. and 3. using the [version-bump-prompt npm package](https://github.com/JS-DevTools/version-bump-prompt). I'll carry on doing this at the end of every piece of work, but for collaborative projects that's going to cause both merge conflicts and versioning issues. ~~And if a PR needs work after the branch has been tagged, the tag will have to be deleted and re-added to the final commit.~~ <b>Update:</b> I've sorted the tagging part of my process by [adding the tag *after* the PR has been merged](/blog/simplifying-branching-and-deployment-with-github-flow#the-drawbacks).

I'm going to have to work out how to automate this as part of the PR/merge process. I've got a feeling that running some scripts via [GitHub Actions](https://github.com/features/actions) will be the answer, but that's another story for another blog post!

In the meantime, I'll be shipping each thing I build the moment it's ready.
