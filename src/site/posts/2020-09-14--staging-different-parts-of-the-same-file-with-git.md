---
title: Staging different parts of the same file with Git
intro: |
    Since moving to command line Git, I've avoided patch mode; it looked too complicated. Turns out it's really not, and very much worth learning.
date: 2020-09-14
tags:
    - Git
    - Development
---

I used to love the way I could chunk down a commit with Tower by looking at a file that has changed and clicking the lines that I wanted to add to staging. Since leaving Tower behind and [moving to command line only for Git](/blog/getting-to-grips-with-git), I've missed that feature a *lot*.

I know `git add` has a `--patch` flag, and I've tried it once or twice, but every time I did, it felt a bit complicated and overwhelming, so I put it off.

Instead, I found a couple of work arounds:

1. Only change the lines of code that belong to the commit I'm going to make
2. Change multiple lines of code that belong to two (or more) commits, delete the code that belongs to commit number 2, make commit number 1, undo the deletion, make commit number 1

The first might actually be Git best practice, but it means I don't always remember what I was going to do in the next commit by the time I get to it.

The second is just plain old messy. One false move and I've lost the 'redo' history.

So in order to mitigate forgetfulness or disorder, I thought it was time to make a concerted effort to learn how to use patch mode. That way I can over-code if I want to, then stage only specific chunks (or "hunks") of what I've changed for one commit, leaving the rest for the subsequent commit(s).


## Choosing a file

To open up the patch mode, you need to first tell Git what file you want to have a look through:

```git
git add -p path/to/file.html
```

Or you could cycle through every changed file by leaving out the file name/path:

```git
git add -p
```

<i>You could use the more explicit `--patch` flag instead of `-p`, but I like to keep it brief.</i>


## Inside the file

This will then open up patch mode, which is a bit like when you're diffing a file, but with just the first hunk of changed code in view, followed by a bunch of options, and this is where I've always given up:

```git
Stage this hunk [y,n,q,a,d,k,K,j,J,g,/,s,e,?]?
```

See what I mean about overwhelming?

Things get even more dizzying if you type <kbd>?</kbd> and hit Return:

```git
y - stage this hunk
n - do not stage this hunk
q - quit; do not stage this hunk or any of the remaining ones
a - stage this hunk and all later hunks in the file
d - do not stage this hunk or any of the later hunks in the file
g - select a hunk to go to
/ - search for a hunk matching the given regex
j - leave this hunk undecided, see next undecided hunk
J - leave this hunk undecided, see next hunk
k - leave this hunk undecided, see previous undecided hunk
K - leave this hunk undecided, see previous hunk
s - split the current hunk into smaller hunks
e - manually edit the current hunk
? - print help
```

But what if I told you that you only really need a couple of those options?


### Start with a couple and build from there

The two options that you should hone in on to start with are pretty obvious:

1. 'yes' (<kbd>y</kbd>)
2. 'no' (<kbd>n</kbd>)

The only other command that might come in useful is <kbd>s</kbd>, which is used to [split a hunk down further](/blog/splitting-a-hunk-in-gits-patch-mode).

So you move through the hunks of code with <kbd>y</kbd> and <kbd>n</kbd>, and if a hunk is too big, using <kbd>s</kbd> should be enough to then carry on with <kbd>y</kbd> and <kbd>n</kbd>.

As you get used to these options, you could make things more efficient by experimenting with <kbd>a</kbd>, <kbd>d</kbd> and some of the others. I'm yet to brave some of them (like <kbd>e</kbd>), but will write about them when I do.


## Back to the command prompt

Once every hunk in the file has been reviewed, you land back on the command prompt, outside of patch mode. A quick `git status` will show that the file you've just gone through and staged parts of is *both added and not added* to the staging area, which is what we'd expect.

Follow it all up with a `git commit -m "This is my commit message"` and that's it!

Git's patch mode looks daunting but can be learned bit by bit, starting with the basic options and building from there.
