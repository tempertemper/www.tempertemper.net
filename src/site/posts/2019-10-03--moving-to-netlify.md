---
title: Moving to Netlify
intro: |
    I've really never enjoyed servers, and Netlify looks like an easy to use, powerful alternative for any static sites I build.
date: 2019-10-03
tags:
    - Development
    - Serverless
---

I've never enjoyed hosting. After all these years I'm comfortable enough poking round a server, but I'd be perfectly happy if I never had to SSH into another Linux box as long as I live. And Netlify looks like a good route into that happy place!

My website is now [built with a static site generator](/blog/website-version-5) and I moved it from my own server to Netlify a couple of weeks ago. So far, I've been very impressed. Here's a quick run-down of my thoughts:

- [Google Lighthouse](https://web.dev/measure) for 'Best Practices' was previously sitting in the high 90s, which was fine, but now it's coming back with a perfect score
- Deployment is easy, and I don't need a service like [DeployHQ](https://www.deployhq.com/) sitting between GitHub and my server
- SSL certificates are a piece of cake to set up and cron jobs for renewals are taken care of automatically
- I had a concern about the Netlify subdomain (tempertemper.netlify.com) but I got around fairly easily with redirect in the Netlify config file
- It opens the door to using server-side stuff like forms in my static site (via [Netlify functions](https://functions.netlify.com)), though I don't want to go too far down this route as it moves away from one of the things a static site should be: portable onto any server
- I love the idea of [Netlify Analytics](https://www.netlify.com/products/analytics/) -- no more snooping by Google, and I don't need or want any more info than Netlify provide anyway
