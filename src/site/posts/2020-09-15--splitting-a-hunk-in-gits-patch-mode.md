---
title: Splitting a hunk in Git's patch mode
intro: |
    When you enter Git's patch mode, the chunks of code you're offered to stage/skip can sometimes be too big. Here's how splitting them works.
date: 2020-09-15
tags:
    - Git
    - Development
---

When you enter [Git's patch mode](/blog/staging-different-parts-of-the-same-file-with-git), the chunks of code ('hunks') you're offered to stage/skip can sometimes be bigger than you'd want. Maybe a hunk you're offered contains multiple lines with changes that belong in more than one commit. Luckily, the <kbd>s</kbd> option is there to split the hunk down further.

Let's say we have a file that had 3 lines:

1. Line 1
2. Line 2
3. Line 3

If we forgot to add a space between the word 'Line' and its number for lines 1 and 3, we'd go back to fix it; our the diff when we enter patch mode would look like this:

```git
-Line1
+Line 1
Line 2
-Line3
+Line 3
```

Because the second line is unchanged, the <kbd>s</kbd> option will break that hunk down into two smaller hunks, one for Line 1 and the other for Line 3.

If, however, we forgot to add spaces between the word and the number on *every line*, patch mode wouldn't offer the <kbd>s</kbd> option as we're unable split the hunk down further. The reason for this is that there are no *unchanged* lines between those we *are* changing:

```git
-Line1
+Line 1
-Line2
+Line 2
-Line3
+Line 3
```
