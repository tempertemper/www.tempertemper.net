---
title: Stop search indexing for Netlify Deploy Previews and Branch Deploys
intro: |
    Netlify Deploy Previews and Branch Deploys are great, but what if search engines start indexing them?
date: 2019-10-26
tags:
    - Development
    - Serverless
---

Now that you're cool with [Netlify Deploy Previews](/blog/netlify-deploy-previews) and [Branch Deploys](/blog/setting-up-a-staging-site-with-netlify), you might be worried that search engines will start indexing them and you end up with duplicate content problems.

Although Deploy Previews and Branch Deploys are unlikely to be linked to publicly, there's a small chance one might make it out into the wild web; after all, I linked to one in my [Deploy Previews article](/blog/netlify-deploy-previews)…

Here's how to fix it:


### Add a command to your npm scripts

Search engines use a robots.txt file to find out what they should and shouldn't be indexing. I have an [npm script](https://docs.npmjs.com/misc/scripts) called `noIndex`:

```json
scripts": {
  "build": "this is whatever my build command is",
  "noIndex": "npm run build && echo 'User-agent: *\nDisallow: /' > dist/robots.txt"
},
```

This builds the site as normal, then *overwrites* the contents of my default production robots.txt file with instructions not to allow any pages to be indexed.


### Run the command for Deploy Previews

Then all you have to do is tell Netlify to deploy using that command instead of the default build command you'll've set up for your production site:

```toml
[context.deploy-preview]
  command = "npm run noIndex"

[context.branch-deploy]
  command = "npm run noIndex"
```


### Checking it all works

In the Netlify control panel, head to Deploys and keep an eye on the deploy log for your Deploy Preview or Branch Deploy. As the deployment rus, you should spot something like this if it has worked:

> Different build command detected, going to use the one specified in the toml file: 'npm run noIndex' versus 'npm run build' in the site

Then, if you open your deploy preview or staging site in your browser and navigate to https://deploy-preview-or-staging-subomain.netlify.com/robots.txt, you'll see it looks like this:

```
User-agent: *
Disallow: /
```

So now you don't have to worry about anything other than your production site being indexed by search engines!
