---
title: Here, here, and here
intro: Every now and again I read an article that links to multiple places, and each link is a separate word in a short phrase. I'm not a fan.
date: 2022-08-10
tags:
    - Accessibility
    - Content
    - Design
---

I read a lot of blogs. Something I see every now and then is a series of words where each word links to a different place.

Imagine an author wants to link to some of their favourite songs; they might do it like this:

> You can find some songs I like here, here, and here.

In this sentence, "here", "here", and "here" would be individual links, and the Markdown might look something like this:

```md
You can find some songs I like [here](link-to-song-one), [here](link-to-song-two), and [here](link-to-song-three).
```

Linking this way might keep things brief, and it might even look pithy, but there are usability and accessibility problems.


## Purpose and destination

First we need to make sense of the links.


### What are the links for?

It's important that our visitors can scan the page and identify roughly what the purpose of a link or group of links is. Nielsen Norman Group's excellent article [A Link is a Promise](https://www.nngroup.com/articles/link-promise/) talks about scanning:

> Humans are programmed to seek efficiency and minimize the interaction cost: They economize on their fixations (how many things they look directly at). Often they scan first only the text and those UI elements that they believe will help them to quickly understand content and to progress in their task.

When scanning a page, fixating on links, "here" doesn't offer much… We're making people do extra work, as they have to read the content around the links in order to work out what they're for.

This can be especially awkward for screen reader users, as outlined by GOV.UK in their [guidance for writing link text](https://www.gov.uk/guidance/content-design/links#writing-link-text):

> Generic links … do not work for people using screen readers, who often scan through a list of links to navigate a page. It’s important the links are descriptive so they make sense in isolation.

### Where does the link go?

Once the user has figured out that the links go to some songs I like, the next question is *which* songs?

The Web Content Accessibility Guidelines (WCAG), in [Link Purpose (Link Only)](https://www.w3.org/TR/WCAG21/#link-purpose-link-only), require:

> A mechanism is available to allow the purpose of each link to be identified from link text alone

The link text on its own is giving us no clues. Will we be confronted by a not-safe-for-work hip hop track or [Nyan cat](https://youtu.be/QH2-TGUlwu4)?

<i>Note: This is a AAA success criterion but is [very much worth adhering to](/blog/bag-some-aaa-wins-where-you-can).</i>


## Reliance on memory

Once a link has been visited and the user has come back, how will they know not to click the same one again? CSS can take care of this via the [`:visited` pseudo-class](https://developer.mozilla.org/en-US/docs/Web/CSS/:visited), but:

- Not all websites have visible `:visited` styling
- Websites that do have have visible `:visited` usually indicate the state using colour alone, which is not ideal for people with some visual impairments
- Once you've visited more than one link, how do you know which was which, even if `:visited` styling has been used?

Most of the time we're relying on our users giving a number to each link and remembering which link they've followed.


## Is it obvious there's more than one link?

One last hurdle that I've encountered with a series of words where each word is a link is: how many links are there? Counting the ‘here's is probably what most users will need to do, but what if the series of words are an actual sentence? For example:

> Here are some songs I like.

Where "songs", "I", and "like" are the links; the Markdown would look like this:

```md
Here are some [songs](link-to-song-one) [I](link-to-song-two) [like](link-to-song-three).
```

The spaces between the words won't be underlined (you are [underlining your links](/blog/why-you-should-almost-always-underline-your-links), aren't you…?) as the links are only on the words themselves, but is that visually obvious enough? Will people think there's one multi-word link where there are actually three individual links?


## Solutions

I'd use the song title and artist in each link's text and present them in a list:

> Here are some songs I like:
> - [These Are My Twisted Words, by Radiohead](https://youtu.be/zA2tjw_UXYw)
> - [The Light Before We Land, by The Delgados](https://youtu.be/Hg0k18F2Fkg)
> - [Found a Job, by Talking Heads](https://youtu.be/ZXzDh1Q1JsM)

This means:

- Scanning the page and fixating on the links quickly gives an impression of what the links are for
- Descriptive link titles tell the user what will be at the end of the link when they follow it
- Link text that matches the content it links to makes knowing which links you've visited much easier
- Each link is separated onto its own line, making it obvious that there are several

We've had to completely redesign the content, but what we've landed on is much more usable, and infinitely more accessible.

A couple of final points on the link wording:

- Don't be afraid to make your links longer if it means they'll be descriptive enough to make sense out of context
- Sometimes the link will make the sentence slightly less eloquent 

As ever, the best way to ensure good usability and accessibility is to dial down the 'clever'.
