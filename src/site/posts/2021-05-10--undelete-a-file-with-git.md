---
title: Undelete a file with Git
intro: |
    I've talked about how great `git restore` is, but I missed a really obvious use of `git restore`: restoring a deleted file!
date: 2021-05-10
tags:
    - Development
    - Git
---

I've talked about [how great `git restore` is](/blog/git-restore-to-discard-changes), but I missed a really obvious use of `git restore`: restoring a deleted file!

Let's say I'm making some changes and I either:

- accidentally delete a file
- delete a file and decide I shouldn't have

I could go digging around in my Trash folder and move the file back to the directory I deleted it from, but there's an easier way!

As long as you I haven't committed the changes, all I need to restore my deleted homepage file is:

```git
git restore index.html
```
