---
title: SSL for everyone
intro: |
    Every now and then something big happens on the web. Sometimes it’s really obvious, sometimes a bit subtler, but still very important. SSL is a subtle
date: 2016-06-28
tags:
    - Security
---

Every now and then something big happens on the web. Sometimes it’s really obvious, like when a website’s layout changes so that it’s easy to view on a mobile phone. Sometimes it’s a bit subtler, but still very important.

So a few years ago I wrote an article about why I began doing [one or two important things on every website](/resources/why-i-changed-my-baseline) I build. This is still true: a website that doesn’t adjust to the size of the screen it’s being viewed on just isn’t a website in my eyes.

SSL is another one of those ‘everyone should have it’ things.

But what _is_ SSL? Have a quick read of the [article I wrote](/resources/what-is-ssl-and-is-it-worth-the-bother) on SSL. In summary, it **secures the information sent to and from your device** to the web server where the site you’re visiting lives.

Ok, but that’s bit abstract… How about something more relatable? SSL prevents anyone intercepting information you send via a form: credit card details or even just your email address.

Any other benefits? Well, [Google likes it](https://webmasters.googleblog.com/2014/08/https-as-ranking-signal.html). They’ll give you a few extra search brownie points if you have it! And if Google do it, Bing, Yahoo!, Duck Duck Go and the rest will too. As good a reason as any!


## How do I know a website’s running SSL?

You see the address bar at the top of this web page? The little padlock tells you that SSL is working and the connection to the server is secure.


## Different levels of SSL

There’s a [handy test](https://www.ssllabs.com/ssltest/) on Qualys SSL Labs’ website. Run any website through this and it will give you a site’s rating. A being great, F being not so great.


## Things change

As with any kind of software, things change over time. Bad guys find ways around things that we thought were working fine. So a website that’s super secure now might not be quite as secure in a year’s time.

That’s why I run regular SSL tests on my clients websites to make sure they’re still A rated.

An upgrade might mean your website won’t work in very [old browsers](/resources/older-browsers) that just don’t have the built-in security to support SSL properly. This is almost always perfectly fine though, as there are so few people using those browsers that they probably don’t account for any traffic to your site anyway.


## No downsides then?

Not really. If you were being _really_ picky you could argue that there’s is a _very slight_ speed reduction. But it’s so slight that nobody would ever really notice. Also, my servers are fast enough that this wouldn’t be picked up in proper automated tests.

Of course, where there’s extra setup and maintenance time involved, there’s an extra cost. But it’s very affordable when you consider the advantages.


## What next?

SSL is pretty much a no-brainer. It’s all-round good for your visitors, good for search rankings, inexpensive and you get that nice wee padlock in your browser’s address bar.

That’s why I’m defaulting to installing SSL on every website I build.
