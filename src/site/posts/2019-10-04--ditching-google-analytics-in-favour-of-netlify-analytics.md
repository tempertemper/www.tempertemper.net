---
title: Ditching Google Analytics in favour of Netlify Analytics
intro: |
    Having moved my website to Netlify, I've been pretty excited about some of the features they offer, one in particular has been Netlify Analytics.
date: 2019-10-04
tags:
    - Development
    - Serverless
---

Having [moved my website to Netlify](/blog/moving-to-netlify), I've been pretty excited about some of the features they offer, one in particular has been [Netlify Analytics](https://www.netlify.com/products/analytics/).

Ditching Google Analytics has been incredibly satisfying; although it's free of charge, I've never been comfortable with it. Mainly because it's a Google product (i.e. intrusive and creepy), but also because I don't really care about a lot of the information it provides. My visitors' location, browser, OS, general behaviour, is their business. Real-time information is fun, but---again---uninteresting to me. I can see why some businesses really would want to know all of this, but all I want are:

- Some basic numbers that show me which pages are more popular
- Where people are coming to my site from
- Information on any broken pages

Netlify gives me all of this.


## Privacy

My visitors' privacy is important to me, so not only does this switch mean there's no chance of interference from ad blockers, but I can rest easy knowing that I'm fully GDPR compliant.


## Performance

Performance is high on my list of priorities for my site and analytics running on the server rather than in the browser means there's no burden or performance hit on the user -- it's all just server logs.


## Extras

And because it's all based on server logs, it can track pages that aren't being found (so I can add redirects), which isn't possible with an on-page JavaScript solution like Google Analytics. If a request comes in for a page I've moved but not redirected, I'll know about it.


## Costs

I like paying for things. It reassures me that there's a proper business plan behind the service or app I'm using, and that it won't go away.

Netlify's [Pro plan](https://www.netlify.com/pricing/) is overkill for me at the moment, both in terms of monthly cost and the features on offer, but $9 a month for their analytics (though it feels a wee bit steep for the depth of analytics that they offer) is fair compensation, seeing as they give so much away for free.

I really like Netlify!
