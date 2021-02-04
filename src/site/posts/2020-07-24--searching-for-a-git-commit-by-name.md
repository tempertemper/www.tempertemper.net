---
title: Searching for a Git commit by name with grep
intro: |
    When you need to dig out a commit you made a long time ago, you're going to need something a bit more powerful than a standard `git log`.
date: 2020-07-24
tags:
    - Development
    - Git
---

Sometimes I need to find a commit. It's easy if the commit I'm looking for was very recent -- a quick `git log` will do the job.

If the commit I'm after was a few weeks ago, it's likely that I'm going to have some scrolling to do. `git log` is quite noisy, so I might add `--oneline` to keep it easy to scan.

But what if the commit I'm after was last year? I'm going to have to do a *lot* of scrolling! That's where `--grep` comes in.

I wanted to reference some work I did on the `<address>` element in an [article I wrote earlier this week](/blog/using-address-in-html-wont-be-problematic-for-much-longer), so I filtered my `git log` like this:

```git
git log --grep="address" --oneline
```

This gave me a dozen or so results. Much easier to find what I was after!

Once I had the commit, I copied the commit hash and took a closer look with `git show d773c20`, and this was what the commit looked like:

```git
commit d773c20fd45d993d96abe7f42e0336b8f3ff8e35
Author: Martin Underhill <martin@tempertemper.net>
Date:   Thu Jun 27 21:13:26 2019 +0100

    Adds role of group to address to stop duplicate contentinfo

diff --git a/src/site/_includes/footer.html b/src/site/_includes/footer.html
index b7a1e44..247838b 100644
--- a/src/site/_includes/footer.html
+++ b/src/site/_includes/footer.html
@@ -12,7 +12,7 @@
     </nav>
     <div class="copyright">
         <p>&copy;&nbsp;copyright 2009&nbsp;to&nbsp;{{ "" | getCurrentYear }}</p>
-        <address class="adr">
+        <address class="adr" role="group">
             <span class="org">tempertemper Web Design Ltd</span>,
             <span class="extended-address">Clavering House</span>,
             <span class="street-address">Clavering Place</span>,
(END)
```

Exactly what I was after! I then used the date to find the [PR that the commit was part of](https://github.com/tempertemper/tempertemper.net/pull/39) and, in turn, the [link to the commit](https://github.com/tempertemper/tempertemper.net/pull/39/commits/48f5cc3a438b1c80df34f0bbefb06b37308775e5) on GitHub.

This exercise was also a good reminder to keep commits small and regular, and to name them descriptively!
