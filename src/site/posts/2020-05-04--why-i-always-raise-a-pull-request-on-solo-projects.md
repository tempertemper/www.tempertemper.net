---
title: Why I always raise a pull request on solo projects
intro: |
    The whole point of a PR is to get feedback and approval on a piece of work from someone else before it's published. But what if it's just you?
date: 2020-05-04
tags:
    - Development
    - Git
---

The whole point of a pull request (PR) is to get feedback and approval on a piece of work from someone else in your team before it gets tested or published.

But what if you don't have a team? What if you're a freelancer working on a project for a client alone? What if you're the only dev in a small company? What if you're working on a personal project? If that's the case, PRs still come with a load of benefits, so they're very much worth doing.


## First, the 'downsides'

PRs take effort. Not a lot of effort, but still effort. Even at its simplest, there's always some kind of workflow involved, where you have to:

- ensure you make a branch for each piece of work you embark on
- hope your internet connection is ok or that work's not going to make it to your main branch!
- go to GitHub (or GitLab, Bitbucket, etc.) to create the PR from your branch
- take a wee bit of time to write a heading/title

You then do that thing everyone tells you you should never do: *merge your own PR*!

But why did we go to all of that effort and break the golden rule? You might argue that well written and timely commits are enough, and it's true that that would give you a solid history that you can to roll back to, but PRs are much better.


## Why PRs are better

Relying on commits is possible but takes effort to decipher. Say you're looking to roll back to a piece of work you finished a month or so ago. Running something like `git log --oneline` would allow you to easily scan through your commits, but which one was the one you finished that job on?

- You could search through your commit timeline and figure out which was the last commit
- If you're doing work on branches you could pick out the merge commit where you brought the work from your working branch into your main branch
- If you tag the end of every job, you could find the tag you added to the last commit

But if you're branching and tagging, you might as well be doing PRs since they give you so much more.

### Isolation

Branching for a PR isolates work from the main branch, meaning you can do whatever you like without risk, keeping any potential bugs away from your main body of work and giving you time to double check things.

### Double checking

A PR constitutes a distinct chunk of work; a feature or fix from beginning to end. Most Git services like GitHub or GitLab make it a cinch to see what changes were made. This opportunity to view your work in one convenient place also provides a fresh context, which can make finding mistakes or unnecessary extra code easier.

### Hitting 'undo'

Again, most good Git services allow you to re-open a closed PR and 'revert' the work, if it turns out you need to go back to where you were. A convenient extra layer of protection on top of what Git already offers you.

### Automated testing

PRs allow automated tests to be run on that branch, ensuring everything's in good shape before you hit merge. You might have a bunch of unit tests, or regression tests, accessibility tests or just something as simple as Netlify's build tests.

### Client previews

The projects I work on on my own are not always personal projects. If I'm working for a client and it's a static/JAMStack site that sits on Netlify, every PR that's destined for my main branch generates a [Deploy Preview](/blog/netlify-deploy-previews). This allows me to send a link to my client so they inspect the changes that have been made on a real website.

### Task history

It's dead easy to look through your closed PRs to see what work has been done. This can be really useful if, for example, you're looking for a particular piece of code that you removed but might want again. There should be many fewer PRs than commits, so it's not only much easier to remember the block of work where the code was removed, narrowing your search down first; you can then sift through the commits inside the PR. The alternative---filtering through thousands of individual, ungrouped commits---is not much fun.

### Task management

If you manage your project tasks in the same Git service via Issues, you can hook PRs up to Issues, so that when the PR is merged into the main branch it closes the Issue. Not only is that convenient, but the connection between the two means:

- You can jump to the closed Issue from the PR
- You can jump to the PR from the closed Issue

Very convenient when doing some detective work to unpick an issue or bug, or find some code you'd like to reinstate.

## Discipline

As I mentioned, creating PRs for every bit of work on a solo project is more work than just committing to your main branch or merging your work locally. But it could save you *hours* in the long-run; providing extra reassurance that things are looking good, and, if they turn out not to be quite right after merging, can be undone easily.

And once you're in the rhythm of doing it that way, it feels wrong to do it any other!

