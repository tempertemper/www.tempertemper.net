---
title: Using Git restore to discard changes within a file
intro: |
    `git restore` is great, and one of its superpowers is its patch mode where we can restore parts of a file, rather than the whole file at once.
date: 2021-05-11
tags:
    - Git
    - Development
---

[I really like `git restore`](/blog/git-restore-to-discard-changes), and one of its superpowers is its patch mode, where we can restore parts (or 'hunks') of a file, rather than the whole file at once.

Just like [`git add`'s patch mode](/blog/staging-different-parts-of-the-same-file-with-git), we don't have to use the full `--patch` flag; we get a handy `-p` shortcut. The following command would enter patch mode for every file we've edited since our last commit:

```git
git restore -p
```

Alternatively, we can restore parts of a specific file with:

```git
git restore -p path/to/file.html
```

Once we're in patch mode, we just need to work our way through the changes by typing a letter from the multitude of options:

```git
Discard this hunk from worktree [y,n,q,a,d,g,/,j,J,k,K,s,e,?]?
```

In case you're not sure what any of those mean (a situation I find myself in all the time!), typing <kbd>?</kbd> and hitting enter gives you a nice reference:

```git
y - discard this hunk from worktree
n - do not discard this hunk from worktree
q - quit; do not discard this hunk or any of the remaining ones
a - discard this hunk and all later hunks in the file
d - do not discard this hunk or any of the later hunks in the file
g - select a hunk to go to
/ - search for a hunk matching the given regex
j - leave this hunk undecided, see next undecided hunk
J - leave this hunk undecided, see next hunk
k - leave this hunk undecided, see previous undecided hunk
K - leave this hunk undecided, see previous hunk
s - split the current hunk into smaller hunkse - manually edit the current hunk
e - manually edit the current hunk
? - print help
```

Pretty overwhelming… The best place to start is with <kbd>y</kbd> ('yes, discard this hunk') and <kbd>n</kbd> ('no, don't discard this hunk'), leaving things like [splitting hunks](/blog/splitting-a-hunk-in-gits-patch-mode) for another day, once you're more familiar with it all.

<i>Note: `git checkout` also has a patch mode that does exactly the same as `git restore`'s patch mode, but the key here is that using the `git restore` command to restore a file to its previously committed state is both more memorable and semantically correct.</i>
