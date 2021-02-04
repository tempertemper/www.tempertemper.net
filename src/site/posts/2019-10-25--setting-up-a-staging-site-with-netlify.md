---
title: Setting up a staging site with Netlify
intro: |
    Netlify Deploy Previews are great, but sometimes it's good to have a staging site for stuff that isn't ready to put into the live website yet.
date: 2019-10-25
tags:
    - Development
    - Serverless
---

Netlify [Deploy Previews are great](/blog/netlify-deploy-previews), but sometimes it's good to have a way to demo ideas to a client, show your remote team some changes or get approval or feedback from a stakeholder *before* raising a pull/merge request.

Since moving my website to Netlify, setting up a staging site is something I was putting off -- it looked fiddly and it wasn't super explicit in Netlify's documentation. Happily, it turned out to be pretty straightforward, once I took a few leaps of faith, and to save you the same uncertainty here's how to do it.

<i>Note: I'm going to do this for a branch called `staging`, but you can use whatever subdomain you like, as long as the branch name matches the subdomain. So if your site is example.com, you could set up a bananas.example.com subdomain if the branch you want to deploy is named `bananas`.</i>


## Create a staging branch if you don't already have one

In your local Git repo, use something like `git checkout -b staging` to create and checkout a staging branch, if you don't have one already. Then push it to your remote with something along the lines of `git push -u origin staging` (which also sets creates a link between the two branches, local and remote, with the `-u` flag -- you're probably going to be using this branch a fair bit so `git push` and `git pull` without specifying a remote will be useful!).


## Set up Branch deploys for your staging branch

Now that we've got a staging branch to deploy from, we need to enable 'Branch deploys' in Netlify:

1. Go to Settings → Build & deploy → Continuous Deployment → Deploy contexts
2. Hit the 'Edit settings' button
3. Change 'Branch deploys' from 'None' to 'Let me add individual branches'
4. Type 'staging' into the 'Additional branches' box and press return to change the text into a token
5. Hit 'Save'


## Add a subdomain to map to the staging deploy

Lastly, we need to add a subdomain to map to the staging branch; this involves a bit of Netlify configuration and adding a DNS record:

1. Go to Settings → Domain Management → Continuous deployment → Deploy
2. Enter the subdomain you wish to map staging to (staging.example.com)
3. Now go to your domain name's DNS control panel and add an A record with `staging` in the 'Host' box (to set the subdomain) and map that to the same IP address you used for your site itself (if in doubt, check the `*`, `www` and `@`/blank A records)
4. Back in Netlify, check your SSL certificates in Settings → Domain Management → HTTPS and you should see staging.example.com in the list of Domains

Now whenever we push the `staging` branch to our remote, it'll deploy to your staging subdomain in the same way that your website itself does!
