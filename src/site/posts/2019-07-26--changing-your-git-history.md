---
title: Changing your Git history
intro: |
    This week I removed some files and data from my Git history. It was a bit of a learning curve, but here's how I did it, step by step.
date: 2019-07-26
tags:
    - Development
    - Git
---

Changing your Git history isn't something we have to do very often, but I found myself needing to do just that this week. I wanted to make [my website repository public](https://github.com/tempertemper/tempertemper-website) after re-factoring my site using a [static site generator](https://www.11ty.io). I'd gleaned lots of tips by poking through other people's source code and I wanted to repay the favour.

The problem with making my code public was that before I made the switch to Eleventy I was using CMS called Perch. My deployment set up meant pushing the files that powered Perch (in a `/core/` directory) as well as a [config file](https://github.com/tempertemper/tempertemper-website/blob/4b7183f1b452c4d926539a0e5dbf2fd891350a50/web/cms/config/config.php) full of database credentials, my Perch license key and [Postmark](https://postmarkapp.com) API keys.

I should probably have built the new site in a fresh repository, but hindsight yadda yadda… If I wanted to make this repository public, I didn't want all of those private files publicly available. So I wanted to:

- redact a bunch of passwords/keys from config
- get rid of the `/core/` directory

After a quick search it looked like the [BFG Repo-Cleaner](https://rtyley.github.io/bfg-repo-cleaner/) was the tool to use.

As with most things developer-y the docs are slightly opaque, so I spent some time tinkering and testing before I figured out what was going on.


## Testing locally

First, I wanted to make sure it worked locally. Here are the steps I followed:

1. Copy local repo to new location as a backup (if I messed up, I could just delete the whole repo files and dump it back in from the backup)
2. Install Java
3. Install the BFG via [Homebrew](https://brew.sh)

### Redacting passwords and keys

1. Make a text file with the passwords to remove, each password on its own line
2. Run the BFG command to replace each password with `***REMOVED***` using `bfg --replace-text /Users/martin/Sites/tempertemper.net/passwords.txt /Users/martin/Sites/tempertemper.net`, which references the passwords file in step 1
3. Run `git reflog expire --expire=now --all && git gc --prune=now --aggressive` to clean up after the BFG

A quick check of the config file in my history tree showed that the passwords had all been replaced with `***REMOVED***`. Good stuff. Although I have to admit I used [Tower](https://www.git-tower.com/mac) for this, rather than [command line Git](/blog/getting-to-grips-with-git). Don't judge me!

### Deleting a folder and its contents

1. Run `bfg --delete-folders core /Users/martin/Sites/tempertemper.net`
2. Clean up with `git reflog expire --expire=now --all && git gc --prune=now --aggressive`
3. Check the history to ensure the folder is gone


## Now for the remote

Ok, so it worked nicely locally. At this point I did a `fetch` and it was crazy – thousands of commits behind locally and thousands ahead remotely. Instead of doing any pushing and pulling at this stage, I headed to the **first step** of the documentation on the BFG website.

1. Run `git clone --mirror https://github.com/tempertemper/tempertemper-website.git` on the remote repository
2. Copy the folder it creates locally to somewhere else as a back-up
3. `cd` into the folder that was created locally. It's not a proper repository that you can browse, but that's ok
4. Run the password redacting command: `bfg --replace-text /Users/martin/Sites/tempertemper.net/passwords.txt /Users/martin/Sites/tempertemper-website.git`
5. Clean up with `git reflog expire --expire=now --all && git gc --prune=now --aggressive`
6. Run the folder deleting command: `bfg --delete-folders core /Users/martin/Sites/tempertemper-website.git`
7. Clean up again: `git reflog expire --expire=now --all && git gc --prune=now --aggressive`
8. Now the leap of faith – it worked locally so it *should* be ok to push. There's a backup of this 'mirror' anyway, from step 2, so a push from that would put things back the way they were. Ready? Run `git push`

At this point, I went back to my working repo and did another fetch. Everything was back to zero – nothing ahead, nothing behind. Success!

You can probably clear up once with the `git gc` command, rather than running it after each `bfg` command, but doing it after each step worked for me and it took 5 seconds each time, so that's what I'll be doing if I ever have to do this again.
