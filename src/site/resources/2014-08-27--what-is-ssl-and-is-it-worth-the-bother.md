---
title: What is SSL and is it worth the bother?
intro: |
    Google look favourably on websites that use SSL. What is SSL, why does Google like it and and do the benefits outweigh the costs?
date: 2014-08-27
tags:
    - Security
    - Search
---

You may have heard. A short while ago, [Google announced](https://developers.google.com/search/blog/2014/08/https-as-ranking-signal) that they look favourably on websites that use SSL.

So SSL is good for your search rankings! But what on earth is SSL!?


## What is SSL?

Don't worry -- I don't think going into the technicalities of SSL is particularly useful. It stands for [Secure Sockets Layer](https://en.wikipedia.org/wiki/Secure_Sockets_Layer) but that's not all that helpful either. A couple of things _are_ useful to know, though:

1. How to recognise a website that uses SSL
2. What SSL does

### What does SSL look like

You've probably noticed that some websites have a little padlock to the left of the domain name in your web browser's address bar. This is a good thing as it means SSL is enabled on the page. Not sure what I mean? This website has it -- look up at the web address and you should spot it.

Some websites go even further and have a branded certificate (like [PayPal](https://www.paypal.com/)) that displays the company's name next to the padlock.


## What does SSL actually do?

SSL is a way of encrypting data that's sent over the internet. To understand how data is sent, it's worth looking at [what happens when you load a website](/resources/how-the-web-works):

1. You type a website address into your web browser's address bar, find a site on a search engine or follow a link to a site
2. A bunch of files and folders that make up a website are sent to your web browser from the place on the internet where your website lives (the server)

You can often send information back to the server via a form -- a contact form, login form, that kind of thing. The server will then respond somehow; maybe with a message like "Your message has been sent", or by logging you into your account.

### What's that go to do with SSL?

When a website uses SSL, the information being sent back and forward is encrypted. This means that, even if someone intercepts it somehow, they won't be able to do anything with the encrypted information.

### How is that useful to me?

SSL is vital on e-commerce websites where you hand your credit card details over (part of being [PCI compliant](https://www.pcisecuritystandards.org/)). In fact, a website is probably breaking the law if your details aren't secured with SSL.

But what if your website doesn't process card payments?

- How about the password you submit when you log into your content management system (CMS)? It would be pretty damaging if someone got hold of those and was able to log into your CMS and change your site's content.
- Someone might want to send you a sensitive message over your contact form -- perhaps telling you how your business has helped them in a very personal way. It would be reassuring for them to know that you were doing everything you could to keep their message private.
- Even if there's not a [PCI compliance](https://www.pcisecuritystandards.org/) requirement to have SSL, the [Data Protection](https://ico.org.uk/for-organisations/) probably requires that you treat your customers' contact details (that are sent in a contact form) with care.
- When a visitor sees that little padlock their trust in your site is likely to increase. Even if they don't know what it actually means, its association with other sites they have confidence in.

Even without all of that, there's the search engine ranking boost!


## Sounds great, but what's the catch?

Sounds great, doesn't it? It's not without its downsides though. The SSL certificate itself comes at a cost -- the basic certificate is fairly affordable---and as you'd expect---the souped-up certificate with your company's name alongside it less so.

The SSL encryption also makes the connection between the server and the person viewing your website slightly slower.


## It's worth doing

Overall, the advantages of SSL far outweigh the disadvantages, even for the smallest of websites. Unless you're an e-commerce website, it's unlikely you'll want to fork out for the branded certificate, but there's a lot of value in a standard certificate.
