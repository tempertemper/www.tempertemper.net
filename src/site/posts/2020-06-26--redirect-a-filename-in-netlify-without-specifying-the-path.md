---
title: Redirect a filename in Netlify without specifying the path
intro: |
    Redirecting a file in Netlify is easy, but what if you don't know the path? Here's how to redirect a particular filename, wherever that file may live.
date: 2020-06-26
tags:
    - Development
    - Serverless
---

I check my [Netlify Analytics](/blog/ditching-google-analytics-in-favour-of-netlify-analytics) every now and again to see what pages people are looking for but not finding, so that I can fix things with redirect in my `netlify.toml` file.

Most of the human redirects have been fixed, so it's all just spambots now, looking for `/wp-login.php` or `.env`; none of which exist on my server. The latest bot has been looking for a `wlwmanifest.xml`, and it's really going for it! So much so that it has taken over 14 of my 15 slots in my Netlify Analytics dashboard, so I can't see much. I see the following (double initial forward slashes sic):

- `//wp1/wp-includes/wlwmanifest.xml`
- `//wp2/wp-includes/wlwmanifest.xml`
- `//test/wp-includes/wlwmanifest.xml`
- `//website/wp-includes/wlwmanifest.xml`
- `//wp/wp-includes/wlwmanifest.xml`
- `//web/wp-includes/wlwmanifest.xml`
- `//cms/wp-includes/wlwmanifest.xml`
- `//wordpress/wp-includes/wlwmanifest.xml`
- `//wp-includes/wlwmanifest.xml`
- `//news/wp-includes/wlwmanifest.xml`
- `//sito/wp-includes/wlwmanifest.xml`
- `//site/wp-includes/wlwmanifest.xml`
- `//blog/wp-includes/wlwmanifest.xml`
- `//2018/wp-includes/wlwmanifest.xml`

I didn't fancy adding 14 redirects to my already lengthy `netlify.toml` file, so I had a search for some way of targeting any `wlwmanifest.xml` file, regardless of the directory it's in. Nothing. Not a bean.

But I did some testing on a staging server and worked it out. Here's how:

```toml
[[redirects]]
  from = "*/wlwmanifest.xml"
  to = "/404"
  status = 301
  force = true
```

All you need is an asterisk before the forward slash, and it will redirect any request for that file, regardless of the directory it's being looked for in.
