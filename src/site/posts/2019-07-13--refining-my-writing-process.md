---
title: Refining my writing process
intro: |
    Rebuilding my website with a static site generator has improved my writing workflow.
date: 2019-07-14
tags:
    - Workflows
---

I'm in the process of writing an article about the recent re-build of my website, but there's one thing that I fancy sharing ahead of the 'official' announcement.

My site used to be built with [a CMS called Perch](https://grabaperch.com), which was great when I was doing more client work, where Perch was my CMS of choice and I could try new features with myself as the guinea pig. Perch is great. But it being a database-driven CMS presented a bit of a hurdle when it came to writing and posting articles.

I've written about [the writing app I use](/blog/in-search-of-the-best-writing-app), iA Writer. It's where writing workflow starts. It's a pleasure to write in and every article I work on is automatically saved in iCloud so I can pick it up from another device.

When it comes to final edits and publishing, though, working in a CMS was fiddly. My process looked something like this:

1. Write the article in iA Writer
2. When it was ready, log into my site's CMS
3. Copy and paste the contents of the article into a new blog article in the CMS
4. Configure the meta-data
5. Save it as a draft
6. Preview the draft
7. Make any edits
8. Publish the article
9. Archive the draft by renaming the iA Writer file and moving it to a central `/articles/` folder in my iCloud Drive
10. Add the meta-data to the file as front-matter
11. If any updates are required, edit the archived file *and* the version on the CMS

My new site is a [static build with Eleventy](https://www.11ty.io/), which has removed duplication and simplified my process:

1. Write the article in iA Writer
2. When it's ready, create a new branch for it in my site's repository
3. Copy and paste the contents of the article into a new blog article in the CMS
4. Configure the meta-data in the file's front-matter
5. Preview the draft locally
6. Make any edits
7. Publish the article (along with any other site updates that have been made since the last release)
8. Delete the draft from iA Writer
9. If any updates are required, make them in the repo and publish when ready

A very developer-centric workflow, but it works well for me!
