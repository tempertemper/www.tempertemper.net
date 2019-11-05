---
title: Netlify Deploy Previews
intro: |
    I've become a bit of an unashamed fan of Netlify recently, and Deploy Previews are something I've been making a quite a bit of use of.
date: 2019-10-24
tags:
    - Development
    - Serverless
---

I've become a bit of an unashamed fan of Netlify recently. I've been enjoying taking baby steps with the functionality they offer, and something I've been making a quite a bit of use of are their <i>Deploy Previews</i>.

A Deploy Preview is a fully generated clone of your website, but on a pretty obscure URL. This is my most recent preview: [https://deploy-preview-167--tempertemper.netlify.com](https://deploy-preview-167--tempertemper.netlify.com).

They're generated automatically every time a Pull Request is raised with the intent to merge to `master` (assuming your master branch is the one you deploy from; use whichever branch triggers your website's deployment). Netlify runs a handful of tests inside GitHub/GitLab/Bitbucket to ensure everything looks good to deploy, and a Deploy Preview is part of that.

It's a great way to double check everything is in order before you hit the 'Merge' button:

- If you're working on a personal project, it gives you some peace of mind that you haven't done anything that will break in a live environment
- If you're working in a team it allows anyone you've added to approve your PR to have a poke around or run any visual tests on the URL
- If you're working on a small-scale client site and a staging branch/URL is overkill, it could be a good way to get approval before publishing

They also make a nice archive, so you can go back through your closed release PRs/MRs in GitHub/GitLab/Bitbucket and see what the website you're working on looked like at those points in time. You could also do this by checking out the release commits with Git, but it's nice to be able to open all the versions in separate browser tabs and have a flick through.

Anyway, Deploy Previews are a handy feature and another reason I'm falling in love with Netlify.
