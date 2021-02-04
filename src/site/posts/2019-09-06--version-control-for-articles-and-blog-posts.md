---
title: Version control for articles and blog posts
intro: |
    Git workflows like GitFlow are great, but how does publishing articles fit in when using a static site generator? Here's how I'm doing it.
date: 2019-09-06
tags:
    - Development
    - Git
---

I recently [refactored my website](/blog/website-version-5) with Eleventy, a static site generator. I've been using [GitFlow](https://www.git-tower.com/learn/git/ebook/en/command-line/advanced-topics/git-flow) and [semantic versioning](https://semver.org/spec/v2.0.0.html) which has been great for general development but has felt a bit *off* for publishing articles.

This was never an issue when using a CMS, as the content was all dealt with by logging in and adding a blog post/article. Code---and therefore version control---free. But a CMS comes with its own draw-backs; I've written about how a databaseless workflow has [improved my previously messy writing process](/blog/refining-my-writing-process), and this newest development represents another jump in efficiency!


## Publishing an article with GitFlow

Here's the process for creating and shipping a new feature with GitFlow:

1. Create a `feature/feature-name` branch from `develop`
2. Do the work
3. Push the branch to the remote
4. Raise a pull/merge request
5. Merge the work into `develop` on the remote
6. Pull `develop` locally
7. Create a release branch
8. Update changelog, version number, and tag branch
9. Push the release to the remote
10. Raise a PR/MR
11. Merge the work into `master` on the remote
12. Pull `master` locally
13. Merge `master` into `develop` locally
14. Push `develop` back up to the remote

That's great for design refinements, code refactors, and other new features as steps 1 to 6 are usually repeated several times and bundled together as a single release, meaning steps 7 to 14 only happen once in a while. But it's a lot of work just to publish an article, let alone several in one week…

When I was working on the rebuild of my site I would bundle a few feature updates with an article and release them together, but I recently reached a point where I'm happy to stop development for a while, so releasing articles in that same way suddenly felt very odd.

If we're versioning our articles, there's the question of *how*; the way I version is with major, minor and patch, where the first number in the `*.*.*` sequence is major, the second minor and the third patch.

1. <b>Major</b> updates is for something big -- a wholesale refactor, rebrand, etc.
2. <b>Minor</b> is something obvious by nothing major like a rearranged homepage layout, the introduction of a secondary, supporting brand colour, etc.
3. A patch is a small code refactor or a small feature that’ll go, for the most part, unnoticed: rewording some paragraph content,

Is an article a minor change or a patch? Maybe it's neither…


## Is an article a feature?

If I was changing a header or any other content on the site, I'd cut a relase. Why? Because it's *design*. I'm a big advocate of [content design](https://gds.blog.gov.uk/2014/03/14/what-we-mean-when-we-talk-about-content-design/), so rewording an introductory paragraph or the text on a button is definitely design work -- it can have a profound (or subtle) effect on how users interact with a website.

But repeating content that fits inside a template, like articles, resources, testimonials and so on, isn't *design*. It's *editorial*.


## How should things change?

So we're agreed that repeating content like articles or blog posts shouldn't adhere to the normal GitFlow process. But what process *should* we be following?

Let's take a quick diversion into hotfixes. Hotfixes aren't features, so they don't follow the same feature/release process; instead, they're quick bug fixes for something that has already been built. This is the process:

1. Create a `hotfix/hotfix-name` branch from `master`
2. Make the fix
3. Push the branch to the remote
4. Raise a PR
5. Merge the work into `master` on the remote
6. Pull `master` locally
7. Merge `master` into `develop` locally
8. Push `develop` back up to the remote

No versioning, tagging or updating of the changelog as it's a fix for something that went out on a previous release. It's taken from and merged straight back into `master`.

To me, this is how easy adding an article should be.


## My solution

1. Create an `article/article-name` branch from `master`
2. Add the article into the codebase
3. Push the branch to the remote
4. Raise a PR
5. Merge the work into `master` on the remote
6. Pull `master` locally
7. Merge `master` into `develop` locally
8. Push `develop` back up to the remote

This way, like a hotfix, publishing an article is independent of any other features that are being worked on and releases that might be made.

One thing to note, I still prefer to create a PR as they allow an easy revert if things go awry, and keep a nice audit trail on Github.

So for me, GitFlow is now GitFlow+, consisting of features, hotfixes, releases *and articles*!
