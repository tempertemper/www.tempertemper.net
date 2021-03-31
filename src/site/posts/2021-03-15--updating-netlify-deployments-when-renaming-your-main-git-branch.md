---
title: Updating Netlify deployments when renaming your main Git branch
intro: |
    When you rename your Git branch, you're going to need to reconfigure any Netlify deployments that are set up to watch your old `master` branch.
date: 2021-03-15
tags:
    - Git
    - Development
---

When you [rename your Git branch](/blog/empathy-and-renaming-my-master-branch-to-main), it's going to break any deployments you've got set up. That's because they're configured to trigger when you push changes to your old `master` branch, which doesn't exist any more.

In Netlify, it's pretty straightforward to change the deployment branch to `main` (or `primary` or whatever you like!); just head to your site in the control panel then:

1. Navigate to 'Site settings' from the main menu
2. Choose 'Continuous Deployment' ([inconsistent case](/blog/sentence-case-versus-title-case) sic) in the side menu, in the 'Build & deploy' grouping
3. Scroll to 'Deploy contexts', the second section down the page
4. Where you see 'Production branch' with the old branch name, hit the 'Edit settings' button
5. Change the value 'Production branch' to the new branch name and save the changes

That's it! The next time you push to your renamed branch, Netlify will publish your site.
