---
title: Adding files for a commit with asterisks
intro: |
    It can be fiddly to stage files for a commit using Git on the command line. Or so I thought! I found a shortcut, so thought I'd write about it.
date: 2019-10-01
tags:
    - Development
    - Git
---

One thing that has frustrated me about [using the command line for Git](/blog/getting-to-grips-with-git) is that it is a long-winded process to stage individual files for a commit. In a GUI you just check the checkboxes next to the files you want to stage -- easy. Well, I found a shortcut that I've been using a lot over the past couple of days, so thought I'd write about it.

Imagine we have made changes to the following 4 files:

1. this/is/an/awfully/long/path/file1.html
2. this/is/an/awfully/long/path/file2.html
3. this/is/an/awfully/long/path/file3.css
4. this/is/quite/a/long/path/file4.html

Staging them *all* is easy.

To stage and commit them all in one move, use `git commit -a` or `git commit -a -m "This is the commit message"` and you're good. This won't work for newly added files, though, just files that existed and were changed.

When there are new files involved, we have to use `git add .` to stage all of our files, ready to commit with `git commit` or `git commit -m "This is the commit message"`.

But what about when we need to get a bit more granular? That's where things got tedious.


## Staging individual files

If we just want to stage, say, file number 4 we could say `git add this/is/quite/a/long/path/file4.html`. But that's quite a lot of typing. Tab completion makes it a bit easier (`git add this/is/quite` then <kbd title="tab">⇥</kbd> will zero in on the file), but if you've got to do this every time it could get tiresome.

It would be cool if we could just type `git add file4` and it would know what we meant, but there's a path and a file extension involved, so it won't let you. That's where asterisks come in!

- A *single* asterisk (`*`) acts as a wildcard for characters in a file name
- A *pair* of asterisks (`**`) acts as a wildcard for a series of directories/folders

So if we want to stage that fourth file in our list above, we could use the double asterisk to look through all directories, going as many levels as it can, until it finds a file that matches: `git add **/file4.html`.

We could make that even quicker by sticking a single asterisk after the `4`: `git add **/file4*` -- that second asterisk tells git not to worry about anything after the '4'.

And we could be even lazier by typing `git add **/*4*` if we knew that would get us a match!

So we can target a file in a group if we know what's *unique* about it, for example `git add **/*css` would stage the third file as it's the only CSS file.


## Staging several files

The long way to stage multiple files in one go would be to could use their full paths in the `git add` command: `git add this/is/an/awfully/long/path/file2.html this/is/quite/a/long/path/file4.html`. Again, that's quite a lot to type.

I *was*, where I could, using a shared path to stage more than one file: if I wanted files one, two and three, I'd use `git add this/is/an/` since those files all share that bit of path and the last bit (the `an/`) doesn't exist in file four. But that's about all we can do here without asterisks.

Since we know those three files are the only ones with an `an/` in their path, we can stage them like this instead: `git add **/an`.

To stage files with something in common, like just the HTML files (one, two and four), we could run `git add **/*html`.

We can even combine those two things -- to get the HTML files in the directories that share the `an/` path, running `git add **/an/*html` will stage files one and two, but not four.

This has saved me a load of time, so hopefully it will you too!

