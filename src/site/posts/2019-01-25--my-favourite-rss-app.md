---
title: My favourite RSS app
intro: |
    I love RSS. Having used the same RSS app for years, I decided to have a look what else was out there one stood out above the rest.
date: 2019-01-25
tags:
    - Design
    - Tools
---

I've [talked about RSS before](/blog/why-i-still-use-rss). I love it. I know most people ditched it a long time ago,  when [Google closed their Reader](https://googleblog.blogspot.com/2013/03/a-second-spring-of-cleaning.html) service, favouring Twitter as a means to keep up with [their favourite bloggers](https://twitter.com/daringfireball), but some RSS die-hards like me moved to one of the many emerging paid services like [Feed HQ](https://feedhq.org) or [Feed Wrangler](https://feedwrangler.net).

I'm not one for using web applications in-browser. I don't think I ever opened my web browser sifted through the articles in my RSS feed there -- I always use a native app on my phone or desktop where possible, and that very much applies to RSS.


## RSS apps

My app of choice was always [Reeder](https://www.reederapp.com/ios/). It was simple and did everything I needed it to, but as iOS was updated, the user interface began to feel a bit tired; when I finally decided to have a look what else was out there its feature set did too.

So, [as I did with my writing app](/blog/in-search-of-the-best-writing-app), I started having a look at what else was out there. One stood out: [Lire](https://lireapp.com). I'm slightly ashamed to admit it was for superficial reasons at first -- I *really* like its app icon (my RSS reader has always been one of the four apps in my dock and ugly icon sitting there would make me sad) and the interface in general feels very up to date. I was right to follow my instincts though -- on top of all of the usual options (e.g. send to Instapaper) and some nice unexpected ones (great Siri Shortcuts support), it has one feature that  really stood out: full article downloading.


## How things used to work

It's something that seems obvious now that I've been using it, as all good design does, but Reeder didn't do it so I didn't question it. So here's how things *used* to work:

1. The app fetches new articles from your feed
2. If the title grabs you, you read a short excerpt/snippet/taster/intro for each
3. If the excerpt grabs you you visit the page and read the article (normally using the in-app browser's [Reader Mode](https://www.macworld.com/article/3206708/websites/how-to-use-reader-mode-in-safari-11.html)).

This three step process sounds ridiculous now that I've been using Lire, which downloads the full article, rather than just the excerpt. And it's not only the title → excerpt → full article rigmarole -- if you want to read the full article you have to be online, as it involves a visit to the website.

Forcing users to visit a site could be a way that the author gets your eyes on their adverts (hint: if you still rely on this type of advertising to make money in this era of ad blockers, now's probably a good time to have a rethink). It could also be because the author is concerned that their RSS content will be scraped and republished without their consent.

Presenting an excerpt rather than the full article is pretty common practice for RSS feeds (although there's the odd outlier like [Smashing Magazine](https://www.smashingmagazine.com/feed/)). They're usually built in the same way as a blog listings page:

- title
- date the article was published
- an excerpt
- a link to the article

And most RSS feeds are built in the same way:

- `<title>`
- `<pubDate>`
- `<description>`
- `<link>` (and `<guid>`, or global unique identifier, with the same value)

This is the way most out-of-the-box RSS feeds look (Wordpress anyone?) and that's what more modern RSS reader apps are built to fix: when they reach out and check their feeds, they scrape each article and fetch the full thing back into the reader. We're skipping the excerpt, making it a 2 step process instead: title → article!


### What's wrong with visiting a website?

But why is it a bad experience for the user to have to visit a website to read an article?

- They might be reading in the dark, with a comfortable dark theme in their RSS app, only to be presented with a dark-text-on-light-background website which can be an uncomfortable transition
- The reading experience itself might not be all that well designed -- keeping it in-app means it's not only consistent, but well designed
- The move from clean, familiar in-app UI to who-knows-what-it's-going-to-look-like webpage is jarring in and of itself
- You'll have navigation, a logo, some social media links and a heading that you've already read to scroll past
- There could possibly be adverts vying for your attention
- The page may take some time to load due to ads, tracking scripts, fonts, unoptimised images etc.
- There might not be a good internet connection so the website not load in, leaving the user unable to read the article

My old RSS reader app had an automatic 'reader' setting that turned the in-app browser's Reader mode on by default. But this wasn't ideal:

- it still meant an extra trip to the website to grab the whole article, so you see a flash of the website before before Safari's Reader kicks in
- fetching the website meant an internet connection was needed -- at best a few seconds to wait while the article downloads, at worst you're on the Metro and have gone underground and lost signal so you never see your article
- Safari's Reader view looks and feels however you've set it up, but that's not necessarily the same font, font size and colours as the app you've come from


## Granularity

So pre-fetching articles is a great idea:

- It removes a step to reading an article
- It provides a consistent reading experience
- There's no cruft to negotiate -- just the article to read
- You can download all of the articles and go through them at your leisure, on- or off-line

But what if pre-fetching the article is a bad thing? I linked to Daring Fireball's Twitter feed above; [the blog itself](https://daringfireball.net) is often a quote from another article with some commentary on it; the link is often to the source, rather than the Daring Fireball post itself. In that case, I *don't want to see the full article* -- I want the TL;DR John Gruber has written. Luckily there's the option to turn the automatic full text view off on a feed-by-feed basis.

In fact, the whole app is packed full of settings: themes, quick actions for the article listings, there's even a tip jar in there!

Which brings me on to money. I _like_ paying for apps.


## Active development

One of the features---if you can call it that---I look for in any app is the sign of a solid business model. If the app costs money, that's good! The developer will have an incentive to keep working on the app.

I've been in touch with the developer of Lire on Twitter a few times to report the odd bug, [suggest a feature](https://twitter.com/tempertemper/status/1067770395443507201) and they have always been super responsive. A good sign.

This isn't *always* the best measure though -- my old favourite, Reeder, *did* charge for the app but was largely silent on Twitter and rarely pushed app updates. The developer might not have run out of money, but maybe he fell out of love? We haven't seen an update since November 2017, but there was the [promise](https://twitter.com/reederapp/status/1034821640864129026) of a big new release nearly 5 months ago…

Regular small releases are reassuring, and Lire has had several updates a month for as far back as I can remember. I'm confident I'll not be looking for another new RSS app any time soon!


## iOS only

There's always a 'however' though, isn't there? My only issue with Lire was that it's iOS only. No Mac app. But the more I thought about this the less it bothered me: how often did I *actually* check my RSS feeds on my Mac? It's the sort of thing I check when commuting on the Metro, waiting in a long queue, brushing my teeth, waiting for the kettle to boil, that kind of thing. Rarely did I have a look at my RSS feeds when sitting at my desk.

Turns out iOS only is all I need!


## Twitter as an alternative doesn't cut it

I mentioned that a lot of people now rely on Twitter to keep up with their favourite blogs. This might work for some people, but I'm afraid it's not for me and is worth covering briefly.

Twitter is noisy. It's about articles, chit chat, thoughts, news, pictures, [animated gifs](https://media.giphy.com/media/fm4WhPMzu9hRK/giphy.gif), and all sorts of other good stuff. RSS is *just* about articles, and I like things that do 'one thing well'. [Chris Heilmann](https://twitter.com/codepo8) puts it quite well in a recent article for Web Designer Magazine:

> I get all my resources via RSS feeds. I can't find much on Twitter because it gets lost in the noise.

The order of Twitter's feed is [based on some weird Facebook-inspired algorithm](https://www.wired.co.uk/article/twitter-non-chronological-timeline-how-to-opt-out), but I want news as it's published, so it has to be (reverse) chronological.

And there's no getting around visiting the website if you're using Twitter (or a Twitter List) as your news feed. You'll visit the article page, wait for it to load in, negotiate the cruft and read what you came to read (if you have the patience). If you're savvy, you'll hit your browser's Reader View.

Lists and Favourites/Likes could probably be useful for keeping things organised, but using Twitter as an RSS replacement is clunky -- it's just not designed for that.


## Wrap-up

Nothing beats RSS for keeping up with blog posts and news, and [Lire](https://lireapp.com) is a great RSS app. The full-article fetching isn't unique to Lire, but that together with all of its other features, its good looks and a responsive developer behind it all is all I need!

Do you still use RSS? What app are you using? I'd love to [hear from you](https://twitter.com/tempertemper)!
