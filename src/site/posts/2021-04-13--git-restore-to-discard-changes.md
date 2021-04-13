---
title: Git restore to discard changes
intro: |
    I no longer use `git checkout` to switch branches; I've also stopped using it to discard uncommitted changes: let me introduce `git restore`!
date: 2021-04-13
tags:
    - Git
    - Development
---

`git checkout` does a lot, from switching branches, viewing a codebase at a point in its history, to discarding uncommitted changes to a file. Just as I've [stopped using `checkout` to switch branches](/blog/using-git-switch-to-change-branches), I've found a better way to clear changes than using `checkout`: `git restore`.

Let's say I've made some changes to my homepage that I don't want to keep; I'd get rid of them with:

```git
git checkout index.html
```

I could also restore the file to its pre-edited state, as it was at the last commit using:

```git
git restore index.html
```

This command feels more intuitive. My guess is that `checkout` was used for this because we'd be checking out a commit at a particular point in time. So here we're moving back to the most recent commit of the index.html file, discarding anything that has happened since then. But that's a total guess, and it's not very easy to remember that that's one of the things `checkout` is for.

`restore`, on the other hand, is exactly what we want to do: restore the file to its state before we started tinkering.


## More power

It's not just a like-for-like, as `switch` is. `git restore` can do much more than `git checkout` when it comes to restoring files. You can:

- discard changes within a folder/directory with `git restore src/site`, for example
- use an asterisk as a wildcard with `git restore *.md` to discard all of your changes to Markdown files
- combine a directory and a file type with `git restore src/site/*.md`
- throw out all of your changes, regardless of filetype or directory using `git restore .` (actually, you can do that with `git checkout --`, but I like that the `.` follows the same pattern as `git add`)
- discard changes to staged files by using the `--staged` (or `-S`) flag: `git restore --staged index.html` (or `git restore -S index.html` using the shorthand)
- restore a previously committed version of a file by using `--source` (or `-s`): `git restore --source abc1234 index.html` (or `git restore -s abc1234 index.html` using the shorthand)

That extra power together with the easier to remember command make `git restore` a better choice than `git checkout`. Using `git restore` and `git switch` also means `git checkout` is left for the thing it sounds like it's for: checking out previous commits!



