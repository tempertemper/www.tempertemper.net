---
title: Changing your Git history
intro: |
    This week I removed some files and data from my Git history. It was a bit of a learning curve, but here's how I did it, step by step.
date: 2019-07-26
tags:
    - Development
    - Git
updated: 2021-03-04
---

Changing my Git history isn't something I have to do very often, but earlier this week I decided to make [my website repository (repo) public](https://github.com/tempertemper/tempertemper.net) after re-factoring my site using a [static site generator](https://www.11ty.dev). I've gleaned lots of tips by poking through other people's source code over the years, and I wanted to repay the favour.

The problem with making my code public was that I was migrating from a database driven content management system (CMS) called [Perch](https://grabaperch.com), and my deployment setup meant pushing the files that powered Perch (in a `/core/` directory) as well as a config file full of database credentials, the Perch license, and some API keys. Sharing the source files, license and API keys, no matter how buried in the Git history, isn't a great idea.

I could have built the new site in a fresh repo, but it's not a new website, just a new *version*, so it makes sense to have all of that CMS history in there. So my plan was to:

- redact all those passwords/keys from CMS config
- get rid of the `/core/` directory

After a bit of digging, it looked like the [BFG Repo-Cleaner](https://rtyley.github.io/bfg-repo-cleaner/) was the tool to use.

I found the documentation tricky to wrap my head around, so I spent some time backing things up, tinkering, breaking things, until I finally worked it out. Here are the rough steps I followed:

1. Install the BFG software
2. Run some commands to redact/delete things on the original
3. Create a 'mirror' of the remote repo on my local machine
4. Run the same commands on the mirror that I ran on the local repo
5. Push the mirror back up to the remote


## Getting set up

First, you need to get everything ready:

1. Install Java, if it's not already on your machine (the BFG needs Java)
2. Install the BFG with [Homebrew](https://brew.sh) by running `brew install bfg`
3. Duplicate your local repo as a backup with `cp -R ~/Sites/example.com ~/Sites/example.com_backup`; if something goes wrong, you can just delete the original repo, rename the copy the same as the original, and start again


## Making changes to the local repo

Now that everything's prepared, it's time to make changes to the files and folders in the local repo's history.

### Redacting passwords and keys

Let's find and replace bits of text first:

1. Make a text file called `passwords.txt` and save it on our Desktop (you can call the file anything you want and save it anywhere)
2. Paste the passwords/keys that you want to redact into the file, each on its own line
3. Run `bfg --replace-text ~/Desktop/passwords.txt ~/Sites/example.com`; this searches the `example.com` repo and finds each item in the `passwords.txt` file, replacing them with `***REMOVED***`
4. Go to the directory where you got rid of the passwords (in this example, `cd ~/Sites/example.com`) and run `git reflog expire --expire=now --all && git gc --prune=now --aggressive` to clean up after the BFG

Now check it worked:

- Run `git log --oneline` and pick a random commit from somewhere back in your history
- Copy the hash/ID, let's pretend it's `abc1234`
- Run `git checkout abc1234`, which will leave you in a 'detached head' state (this is ok), but will allow you to browse the files as they were at that point in time
- Check that the passwords and keys in the config file have been swapped with `***REMOVED***`
- All good? Head back to the present-day commit with `git checkout -`, which checks out the branch you were on before you jumped to the `abc1234` commit (probably `main` or `develop`)

### Deleting a folder and its contents

Next, let's get rid of a whole folder from our Git history. I'm deleting a directory named `core` here, but you can just swap that for anything you like. You can also specify the full path if there's more than one folder with the same name.

1. Run `bfg --delete-folders core ~/Sites/example.com`
2. Make sure you're in the `example.com` repo; if not run `cd ~/Sites/example.com`
3. Clean up with `git reflog expire --expire=now --all && git gc --prune=now --aggressive`

To check it all worked, grab another random commit hash from your history with `git log --oneline`, checkout that commit and look for the `/core/` folder, which should now be gone.


## Making changes to the remote repo

Ok, so it worked nicely locally. At this point, if you run `git fetch` you'll be thousands of commits behind and thousands ahead. This is totally fine.

1. Get the URL of the remote repo with `git remote get-url --all origin` (assuming it's called `origin`; if not, just name your repo instead)
2. Copy the URL; let's call it `https://github.com/username/example-website.git`
3. Run `git clone --mirror https://github.com/username/example-website.git` on the remote repo; this will create a directory in your repo called `example-website.git` which is a 'mirror' of the remote repo
4. Move the `example-website.git` directory out of the repo with `mv example-website.git ../` (Note: the file and folder structure of the mirror won't look like your local repo, but that's ok)
5. Make a duplicate of `example-website.git` just in case: `cp -R example-website.git example-website.git_backup`
6. Move into the new directory with `cd ../example-website.git`
7. Run the BFG's password redacting command on the mirror, just as you did on your main local repo: `bfg --replace-text ~/Desktop/passwords.txt ~/Sites/example-website.git`
8. Clean up with `git reflog expire --expire=now --all && git gc --prune=now --aggressive`
9. Run the BFG's folder deleting command: `bfg --delete-folders core ~/Sites/example-website.git`
10. Clean up again: `git reflog expire --expire=now --all && git gc --prune=now --aggressive` (You could probably run this clean-up just once, after both `bfg` commands, but it only takes the second or two to run twice so I prefer to repeat it)
11. Now the leap of faith -- it worked locally so it *should* be ok to push. There's a backup of this mirror anyway, from step 5, so a push from that would put things back the way they were. Ready? Run `git push`

When I pushed the mirror I got a bunch of `! [remote rejected]` messages, so thought I was in for some problems, but I held steady and used `cd` to get back to my working repo, where I did another fetch. Everything was back to zero: nothing ahead, nothing behind!

Just one more check:

- Open to your browser and head to your remote repo
- Pick a random commit and choose to browse the repo at that point in history
- Have a poke around to see if the `***REMOVED***` redaction appears where expected, and ensure there's no trace of the `/core/` directory

Finally, it's time to delete the backups we took with `rm -r ~/Sites/example.com_backup ~/Sites/example-website.git_backup`.
