---
title: Keeping git status short
intro: |
    `git status` is one of the Git commands I use the most, but I've always thought that it overshares. Well, I've found a way to make it more readable!
date: 2020-06-22
tags:
    - Development
    - Git
---

`git status` is one of the Git commands I use the most. I use it for a quick overview of what I've done since that last commit:

- Which files have changed?
- Which files have been added?
- Which files have been removed?
- Have I staged any of the files already?

But it outputs a lot of info…

```
➜  tempertemper.net git:(codeblock-layout) ✗ git status
On branch feature/pre-spacing
Your branch is ahead of 'codeblock-layout' by 2 commits.
  (use "git push" to publish your local commits)

Changes not staged for commit:
  (use "git add <file>..." to update what will be committed)
  (use "git checkout -- <file>..." to discard changes in working directory)

        modified:   src/scss/base/typography/_code.scss
        modified:   src/scss/style.scss
        deleted:    src/scss/components/_pre.scss

Untracked files:
  (use "git add <file>..." to include in what will be committed)

        src/scss/base/_code.scss

no changes added to commit (use "git add" and/or "git commit -a")
```

In this example, there're only 4 lines that are of use:

- the two "modified" files
- the file I deleted
- the untracked file

All the rest is noise:

- I probably don't care how many commits ahead of the remote branch I am at this point
- The instructions are repetitive -- the `git add <file>...` command is listed twice
- The instructions are unnecessary once you know how to stage files, discard changes and commit all changes
- I know what branch I'm on as it says it right there on the line where I typed the command


# Enter `--short`

Seems like cheating, but just appending the `--short` flag to your `git status` command makes things *a lot* easier to look at. Even better, you can even shorten `--short` to `-s`!

```git
git status -s
```

This outputs just those 4 lines I'm interested in:

```
➜  tempertemper.net git:(codeblock-layout) ✗ git status -s
 M src/scss/base/typography/_code.scss
 M src/scss/style.scss
 D src/scss/components/_pre.scss
?? src/scss/base/_code.scss
```

`M` markers tell me it's a modified file, `D` a deleted file, and `??` tells me it's a newly added file.

If I add a couple of those to the staging area, they look like this:

```
➜  tempertemper.net git:(codeblock-layout) ✗ git status -s
 M src/scss/base/typography/_code.scss
M  src/scss/style.scss
 D src/scss/components/_pre.scss
A  src/scss/base/_code.scss
```

The `M` moves to the left for staged files, to show they're ready to commit, and the `??` becomes a left-aligned `A`.

So a simple `-s` filters out all of the noise, leaving just the information I'm interested in.
