---
title: Version tagging with Releases in GitHub Flow
intro: |
    I've started using GitHub Flow for some projects and the process is much simpler than GitFlow, but one hurdle I encountered was tagging.
date: 2019-12-27
tags:
    - Git
    - Development
---

In order to keep things as simple as possible and release work as soon as it's done, I've started using GitHub Flow instead of GitFlow for work on my personal website. The flow itself is much simpler but one of the [hurdles I encountered](/blog/simplifying-branching-and-deployment-with-github-flow#the-drawbacks) was tagging.


## Why tag?

If it presents a problem, couldn't tagging just be dropped from the process? This is always a good way to approach a problem -- maybe I was over-engineering things before!

Tagging definitely *could* be removed, but I think tags serve a useful purpose: they provide nice way to see a snapshot of a codebase at a particular point in time with something like:

```git
git checkout -b branch-name v5.6.1
```

This creates a new branch at a particular tag (just change `v5.6.1` to whatever your tag name is), so you can have a poke around and even make changes if you like. When you're done, just get rid of the branch with:

```git
git branch -d branch-name
```

<i>(Use a capital `-D` to force the delete if you've committed changes.)</i>

I use Netlify, so I could just use [Deploy Previews](/blog/netlify-deploy-previews) to check out what my website looked like at a particular release, but I'd have to dig through the .changelog file to get the date, then find it in the Deploys list. Deploy Previews don't allow you to play around with the code itself, either -- just the generated website. But my real issue with this is that it's proprietary and separate from the codebase -- if Netlify ever falls out of favour I'd lose those snapshots. With tags, they're always going to be there.


## How to tag with GitFlow

With GitFlow, tagging is done as part of a release, which is a branch and a pull/merge request (PR) of its own. Anyone reviewing the PR would be very unlikely to raise an objection that required a code change and a new commit, so the tag was always going to be on the final commit of the PR. In exceptional circumstances, the tag would have to be deleted and re-added to a more recent commit. A bit of a fiddle, but a rarity so not a big deal.

If the tagging is happening with every PR, as it is with GitHub Flow, the likelihood of having to remove and re-add a tag is much higher. Any changes required as a result of some feedback on a PR would mean tags would have to be removed from the previous 'final' commit and re-added to the *new* final commit. It's not the faff itself, it's that that makes it not the *right* way to do it.


## How to tag with GitHub Flow

The solution with GitHub Flow is to move the tagging step of the process from the PR to *after* the PR has been successfully merged back into `master`, adding the tag to the merge commit on the `master` branch. No more deleting and re-adding the tag to a later commit in the PR.


## GitHub Releases

[GitHub's Releases](https://help.github.com/en/github/administering-a-repository/creating-releases) are a really powerful way to tag ([GitLab also has this feature](https://docs.gitlab.com/ee/user/project/releases/)). A Release creates a tag, so instead of tagging locally, it's all done on GitHub.

This is a good idea because:

- I often forget to run `git push --tags`, and wonder why my tags aren't appearing on the remote repo -- not a huge problem when I'm working on my own, but with a team it's not something I want to forget
- You can view the Releases in a handy timeline in the Releases section of your repo, alongside Commits, Branches and Contributors
- You can do more than just tag in a Release -- you can give your release a title, message (including images!), and add other files (like a database)
- Each Release gets [its own URL](https://github.com/tempertemper/tempertemper.net/releases/tag/v5.6.1) that you can tweet, send to your boss, etc.


## Almost there

Fixing tagging doesn't solve all my challenges with switching to GitHub Flow for certain projects. I'll still have issues with: conflicts in the .changelog file when multiple people are working on the same codebase. Again, I've got a feeling that this can be solved with [GitHub Actions](https://github.com/features/actions) to build the changelog automatically.

I'll also see if I can use Actions to automate each Release, but for now I'll be doing it manually.
