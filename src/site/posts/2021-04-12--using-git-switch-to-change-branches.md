---
title: Using Git switch to change branches
intro: |
    Since upgrading to macOS Big Sur, I've noticed that `git switch` works. But why do we need it when we've got `git checkout`?
date: 2021-04-12
updated: 2021-04-13
tags:
    - Git
    - Development
---

Since upgrading to macOS Big Sur and its version of Xcode Command Line Tools, I've noticed that `git switch` now works.

`git checkout` has always been a funny one for me; it switches branches, allows you to view code at older commits, discards changes to a file, and probably some other things I don't know about. The Jack of all trades of Git commands.

`git switch` was introduced in Git version 2.23 to separate jumping from branch to branch into its own command. A `checkout` command would look like this:

```git
git checkout my-feature
```

We can now replace that with `switch`:

```git
git switch my-feature
```

But why is `git switch` a good thing?


## More plain English

The main reason I like `git switch` is that it says what it means: switch to another branch. 'Checking out' another branch might make sense when we're going back in time to have a quick look at ('check out'), how things looked on an old commit before coming back to the present-day commit. But when when we plan to do work on a branch, we're doing a lot more than just checking it out.

The semantics of 'checking out' could also be interpreted as *leaving*, not moving *to*; it's a bit of an odd ball.

And if I'm teaching someone how to use Git, `checkout` has always required some explanation where `switch` won't.


## More sensible flags

I often like to create a new branch and 'checkout on it' in one move, so the following command gets a lot of use:

```git
git checkout -b my-new-feature
```

Using the `-b` flag means 'create a new branch that doesn't already exist, then checkout on it'. `b` for 'branch' I guess, but that doesn't quite make sense as we're already doing something with a branch when we run the `checkout` command *without* the `-b`. So what does `b` stand for? Maybe 'build'? Annoyingly, there isn't a longhand for it, like `-f` has `--force`, so there's no way of knowing for sure.

Happily, the `switch` equivalent of the `-b` flag makes much more sense:

```git
git switch -c my-new-feature
```

`c` for 'create': we're switching to and creating a branch. And `-c` has a longhand equivalent that confirms this meaning:

```git
git switch --create my-new-feature
```

## Single purpose

I mentioned that, as well as switching branches, `git checkout` allows you to jump back to a previous commit using a commit hash:

```git
git checkout abc1234
```

You can also discard untracked changes to a file; for example, if I've made some changes to my homepage that I don't want to keep, I might restore the file to its pre-edited state with:

```git
git checkout index.html
```

You can't do either of those things with `git switch`. And that's a good thing in my eyes: one command should do one thing (which is why [I also use `git restore`](/blog/git-restore-to-discard-changes)).

I'll be using `git switch` to change branches in future.
