---
title: Google gets serious about SSL
intro: |
    SSL is an an easy way to boost your search ranking, but Google are introducing something much more damaging to websites that aren't served securely
date: 2016-09-14
tags:
    - Security
---

I've written about SSL a few times before. About [what it is](/resources/what-is-ssl-and-is-it-worth-the-bother) and [why I recommend it](/resources/ssl-for-everyone) for every site I build.

Google has been serious about SSL for a while. Serving websites over an encrypted connection sounds great, but when the biggest search engine in the world starts taking action you know it's worth taking action.

For a good while now, Google have given sites a [boost in their search position](https://webmasters.googleblog.com/2014/08/https-as-ranking-signal.html) for using SSL. But now they're starting to make life difficult for websites that _don't_!

So how are they doing it? Reducing your search ranking for not using SSL would have the same net effect as increasing it if you do, so they're using a different method…


## Enter Google Chrome

Everyone knows Google do search. It's a hugely popular search engine and to many it's synonymous with search (the verb 'to google' [entered the Oxford English Dictionary in 2006](https://en.wikipedia.org/wiki/Google_(verb)). But Google have [lots of other products](https://about.google/intl/en_us/products).

I'm almost certain you'll've heard of **Chrome**. But don't worry if you haven't -- it's a [web browser](/resources/whats-in-a-browser) made by Google. And it's by far and away the most used web browser in the world, with [usage currently sitting at just over 58%](https://www.w3counter.com/globalstats.php?year=2016&month=8) globally. Its closest competitor is Apple's Safari at 12.7%.

So Chrome is a very big deal indeed.


### So what's happening?

In your web browser, just to the left of the box where you type web addresses, there's an icon. In Chrome, this icon gives you information on the website you're visiting:

+ the cookies the website has set (is it tracking your usage, have you logged in, etc.)
+ the permissions you've given the site (maybe you've allowed it to use your location, so that searching for products near you is easier)
+ details on how the website has been served to your browser (including whether it's over SSL/HTTPS)

It's this last point that's important to us here.

At present, if your site is served securely, Chrome displays a nice **padlock** icon and highlights the 'https://' part of the address; all in a lovely positive feeling **green** colour:

<img src="/assets/img/resources/tempertemper-ssl.png" alt="A website served securely, showing a green padlock" width="800" height="450" loading="lazy" decoding="async" />

If it's not, you just see a grey 'information' symbol: the letter 'i' in a circle:

<img src="/assets/img/resources/newcastle-city-council-ssl.png" alt="A website served non-securely, showing a grey information icon" width="800" height="450" loading="lazy" decoding="async" />

From January 2017, this will continue to be the case for your average non-secure web page but on pages that ask for passwords or credit card information Chrome will go even further. It'll highlight pages that ask for passwords, etc. with a "Not secure" label next to the 'i'.

Following on from that that, they have plans to roll the warning out across _all web pages_. Not just those that ask for passwords or credit card details. After all, as [their blog post](https://security.googleblog.com/2016/09/moving-towards-more-secure-web.html) explains:

> When you load a website over HTTP, someone else on the network can look at or modify the site before it gets to you

From there, the warning will get more in-your-face. They'll swap the 'i' symbol for a **warning sign**: a triangle with an exclamation mark in it. What's more, the new symbol and the "Not secure" wording will be in **red**.

So we'll have green, a padlock and 'https' for secure sites, and red, "Not secure" and a warning sign for all the rest, whenever any sensitive information is requested.

### What does that mean?

If you were to visit a site and your browser told you it was not secure, presented a warning symbol and it was all in red, how would you feel about that site?

+ Would you sign up to their mailing list, entrusting them with your email address?
+ Would you send them a message, knowing that someone could intercept it on its way to them?
+ Would you sign up for an account with them?
+ Would you spend money there, giving them your credit or debit card details?

**Trust is everything** on the web, and this warning will undermine any trust you've built up until that point.

### How does that affect me?

If your site takes payments or offers some kind of login facility, you'll be affected from January. Even if the payment is taken using a third party service like PayPal or Stripe, you could undermine your sale if your site isn't secure.

Further down the line your whole site will be marked "Not Secure". Securing the connection with SSL will not only ensure your visitors don't lose faith in your brand, but you'll gain the confidence that comes with that little green padlock.

### A quick aside

As a website owner you probably have the ability to log into a control panel and edit the content on your website. This involves entering a user name and password and, if your connection isn't secure, these credentials can be stolen. Serving your site over SSL means nobody can read the information you send to your server, so you greatly reduce the chance of your website being hacked.


## Where Google goes others follow

Google lead the way in search, world wide. Changes they make to their algorithms are usually copied by rivals like [Bing](https://www.bing.com) and [DuckDuckGo](https://duckduckgo.com).

SSL is good for the web and, while Google are pioneering this move, other browsers (Firefox, Opera, Safari, Edge) are likely to follow their lead and begin issuing warnings about un-securely served websites.



## Give your visitors confidence

So SSL is great, and now that Google are throwing their weight behind it, people are really starting to sit up and listen.

And now it's not just about an easy way to boost your search ranking, it's about avoiding undermining all that work you've done to build trust in your brand.

Update: since I took the screenshot, [Newcastle City Council](https://www.newcastle.gov.uk) have started serving their site securely.
