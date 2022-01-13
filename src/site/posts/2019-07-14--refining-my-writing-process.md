---
title: Refining my writing process
intro: |
    I've rebuilt this site with a static site generator and it has been great so many reasons, but there's one I want to share ahead of the 'official' announcement: my writing workflow.
date: 2019-07-14
tags:
    - Workflows
---

I'm in the process of writing an article about the recent rebuild of my website, but there's one thing that I fancy sharing ahead of the 'official' announcement.

My site used to be built with [a CMS called Perch](https://grabaperch.com), which was great when I was doing more client work, where Perch was my CMS of choice and I could try new features with myself as the guinea pig. Perch is great. But it being a database-driven CMS presented a bit of a hurdle when it came to writing and posting articles.

I've written about [my writing app of choice](/blog/in-search-of-the-best-writing-app), iA Writer; that's where my writing workflow starts. There's no extra thinking involved when putting some new ideas down -- I just open a new document and start typing. And every article I work on is automatically saved in iCloud so I can pick up exactly where I left off, even from another device.

It was the stages after that where things got fiddly using a CMS: final edits, publishing, making updates, archiving files, etc.


## My previous CMS-driven process

1. Write the article in iA Writer
2. When it was ready, log into my site's CMS
3. Copy and paste the contents of the article into a new blog article in the CMS
4. Configure the meta-data
5. Save it as a draft
6. Preview the draft
7. Make any edits
8. Publish the article
9. Archive the draft by renaming the iA Writer file and moving it to a central `/articles/` folder in my iCloud Drive
10. Add the meta-data to the file as front-matter (not that it'd be used anywhere, but it means that I know how I categorised the post, what unique page description I gave it, etc.)
11. If any updates are required, edit the archived file *and* the version on the CMS


## My new file-based process

My new site is a [static build with Eleventy](https://www.11ty.dev/), which has removed duplication and simplified my process:

1. Write the article in iA Writer
2. When it's ready, create a new branch for it in my site's repository
3. Copy and paste the contents of the article into a new blog article in the CMS
4. Configure the meta-data in the file's front-matter
5. Preview the draft locally
6. Make any edits
7. Publish the article (along with any other site updates that have been made since the last release)
8. Delete the draft from iA Writer
9. If any updates are required, make them in the repo and publish when ready

A very developer-centric workflow, but it works well for me and means I'm not updating the same content in more than one place at any given time.
