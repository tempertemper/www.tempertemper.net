---
title: Fixing your last Git commit
intro: |
    Since I've started using Git on the command line, there's one 'new' thing that I've used more than any other: amending my most recent commit.
date: 2019-08-21
tags:
    - Development
    - Git
---

Since I've started using Git on the command line, there's one 'new' thing that I've used more than any other: `--amend`. I can now amend the most recent commit on the branch I'm on. So far, I've used it for two things:

1. Fixing typos in a commit message
2. Adding missed files into a commit

First up, a big thank you to my friend [Sam Beckham](https://twitter.com/samdbeckham) for putting me on to `--amend` -- it has been invaluable.


## Typos

Like most humans, I make typos. Most of time I know when I've made a spelling mistake in a commit message and fix the mistake, but every now and then I don't notice until I've hit <kbd title="Return">⏎</kbd>.

A simple `git commit --amend` opens up the last commit in my editor, allowing me to fix those typos or even change the whole commit message if I wasn't happy with it before.


## Adding missed files

This one's pretty common too -- I'll have been working on a bunch of files but only select few make sense as a single commit. I'll stage (`add`) them, commit, then realise I missed one. Luckily, it's possible to add a file (or several files) to that last commit:

1. First, add the file(s):
    - For all remaining unstaged files, use `git add .`
    - If only one file has to be added in, `git add path/to/file.html`
    - To add multiple files, run `git add path/to/file1.html path/to/file2.html`, adding as many files as needed
2. Amend the commit with the files you've added: `git commit --amend`

You then get the chance to amend your commit message, but you can just save the commit and it's done.

If you're having a particularly bad day and make a new typo while you're fixing that first one, or you add one new file and realise there's a second one too, don't fret! You can repeat the process as many times as you need to!


## Removing files

"But what about *removing* files from a commit!?" I hear you ask. I've only needed to do this once or twice and it's a wee bit different (and trickier) than using `--amend`.

1. First, undo the commit you just made with `git reset --soft HEAD~1`, which brings all the files from your last commit back to staging
2. Next, remove the files you didn't want in that commit from the staging area: `git reset HEAD path/to/file.html`, or if there are several, use `git reset HEAD path/to/file1.html path/to/file2.html`
3. Now you want to make that commit again; this time without those files. Run `git commit -c ORIG_HEAD` which creates a new commit using the same (amendable) commit message that you undid in step 1

As for those files you took out of the commit, feel free to do whatever you want with them!
