---
title: Ditching Google Analytics in favour of Netlify Analytics
intro: |
    Having moved my website to Netlify, I've been pretty excited about some of the features they offer, one in particular has been Netlify Analytics.
date: 2019-10-04
tags:
    - Development
---

Having [moved my website to Netlify](/blog/moving-to-netlify), I've been pretty excited about some of the features they offer, one in particular has been [Netlify Analytics](https://www.netlify.com/products/analytics/).

Ditching Google Analytics has been incredibly satisfying. Although it's free, I've never been comfortable with it being Google (i.e. intrusive and creepy). I don't care about visitors' location, browser, OS, general behaviour, I don't want real time information. I mean I can see why some businesses really would want to know all of this, but all I want is some basic numbers that show me which pages are more popular, where people are coming to my site from and if there are any broken pages. Netlify gives me all of this.

My visitors' privacy is important to me, so not only does this switch mean there's no chance of interference from ad blockers, but I can rest easy knowing that I'm fully GDPR compliant.

Performance is high on my list of priorities for my site and analytics running on the server rather than in the browser means there's no burden or performance hit on the user – it's all just server logs.

And because it's all based on server logs, it can track pages that aren't being found (so I can add redirects), which isn't possible with an on-page JavaScript solution like Google Analytics.

It's a pretty new offering from Netlify, so I wonder whether this is their MVP and they plan to roll out more features. Another hosting company I have a lot of time for ([Linode](https://www.linode.com/?r=b92d6fedd4c0b5608f758fa6becbba975ea10e7b)) launched with a $10 per month plan but later introduced a $5 per month plan that was perfectly usable. A lot of people would have downgraded to that plan as I did, but I imagine they anticipated that and had scaled to the point where that was an financially viable move.

In the meantime, I'm glad I can contribute to Netlify in some way. [Their Pro plan](https://www.netlify.com/pricing/) is overkill for me at the moment. They offer a lot for free, so $9 a month (though it feels a wee bit steep for the depth of analytics that they offer) is fair compensation overall.

