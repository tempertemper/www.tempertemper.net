---
title: Giving your Git stash a name
intro: |
    Naming your Git stashes can be really helpful, especially if you're stashing a lot or saving a stash to come back to another day.
date: 2021-02-05
tags:
    - Git
    - Development
---

Out of the box, `git stash` will automatically name the stash as follows:

1. Stash index number
2. The branch you were on when the changes were stashed
3. The commit hash commit name before the stash was created

Number 3 isn't all that useful if you're not planning on applying your stash any time soon. I prefer to name the stash more descriptively so that if I pick it up in, say, a month's time, I'll have a good idea of what I was doing.

```git
git stash save "Nicely descriptive name of the stash"
```

Now, when I'm [looking over my list of stashes](/blog/choosing-a-stash-from-the-list), I'll be able to remember what each one was for.
