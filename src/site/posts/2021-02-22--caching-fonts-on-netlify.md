---
title: Caching fonts on Netlify
intro: |
    I'm not sure what took me so long to notice, but my website's custom font wasn't caching. The good news is that caching is easy with Netlify.
date: 2021-02-22
tags:
    - Development
    - Performance
    - Serverless
summaryImage: large
---

I'm not sure what took me so long to notice, but [FS-Me, the custom font I use](/blog/tempertempers-typefaces) on my website wasn't caching. I wrongly assumed it would be done automatically and didn't notice the 'flash of unstyled text' (known as FOUT) since I have FS-Me installed locally.

FOUT is fine, but it should only happen once. What's more, a lack of caching means an extra burden on the user who has their data allowance eaten into unnecessarily with every subsequent page load.

Fixing the issue was actually pretty simple; I added the following to my `netlify.toml` file:

```toml
[[headers]]
  for = "/assets/fonts/*"
    [headers.values]
    Cache-Control = "public, max-age=31536000"
```

It's setting a header that:

- caches every file that is downloaded from the fonts folder
- stores them in the cache for a year

If you want to store the fonts for a shorter period, substitute `31536000` (the number of seconds in a year) for something like:

- `604800` for a week
- `2592000` for a month
- `15768000` for six months

My choice of typeface is unlikely to change any time soon, so I'm happy with the `max-age` set to a year.

I could have done this (with a different syntax) in a Netlify `_headers` file instead of `netlify.toml`, but I prefer to keep all of my hosting config in one place as it's tidier and would also make a move to another platform easier.
