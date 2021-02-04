---
title: Using the HTML document outline
intro: |
    What is a document outline? Sounds complicated, but it's really not -- it's just headings! Find out more about them and why they're a good idea.
date: 2020-04-03
tags:
    - Design
    - Development
    - Accessibility
---

A lot has been written about why the proposed [HTML5 document outline wasn't successful](//html5doctor.com/computer-says-no-to-html5-document-outline/), but I'm not going to talk about that. Instead I'll focus on how the document outline in HTML has always worked, and what a document outline even is!


## What is a document outline?

A document outline is a way of dividing up a document and using those divisions to create a hierarchy.

The simplest way to think of it is also the way that screen readers present the outline -- as a list with a bunch of sub-lists. So here's a list, as an example:

- Fruit I like
    - Bananas
    - Apples
        - Pink Ladies
        - Braeburn
        - Gala
    - Oranges

So here we have three types of fruit, one of which has been divided up into sub-groups. Here's how that would translate into headings:

- `<h1>`
    - `<h2>`
    - `<h2>`
        - `<h3>`
        - `<h3>`
        - `<h3>`
    - `<h2>`


### The rules

There are only two rules:

1. There can be only one `<h1>`
2. You can't skip a level on the way up

The first rule doesn't need any more explanation, but the second probably does. What I mean is that to get to an `<h4>`, for example, you have to go from `<h1>` to `<h2>` to `<h3>` first. You can't jump from `<h2>` to `<h4>`. You *can*, however, jump from `<h4>` back to `<h2>`; here's an example:

- `<h1>`
    - `<h2>`
    - `<h2>`
        - `<h3>`
        - `<h3>`
        - `<h3>`
            - `<h4>`
    - `<h2>`

That makes sense because the `<h4>` is part of the 'section' created by `<h3>`, and the `<h3>` is in turn part of its parent `<h2>` -- when you're done with the `<h4>`, you're actually moving out of the section created by it's 'grandparent' `<h2>`, into another `<h2>`.


## Why use an outline?

It's all very well knowing what a document outline *is* and how to use them, but *why* would properly divide up a document with headings?

A document chunked into sections by various heading levels is easier to read, and on the web, ease of reading is hugely important.

### Doesn't overwhelm

Headings can chunk an otherwise daunting document down into more digestible nuggets. It stops readers abandoning your article if all they see is a wall of text.

### Easy scanning

An outline allows the reader to quickly scroll through the page to find out in advance what the content they'd be about to read will be about.

I read a [lot of blog posts](/blog/my-favourite-rss-app) and the first thing I do is decide whether to read the article, based on its main heading. If it sounds interesting, I'll open it; if it looks a long read, I'll have a quick scroll down the page, scanning the headings in order to decide whether I want to read it or not.

### Allows skipping

A document well broken-up by headings allows the reader to make an informed choice to skip a section. Time is important, so if I reach a heading that describes something I'm familiar with, I'll often scroll past that section and win a minute or two back without losing the thread.

### Mental bookmarks

Remember that people don't always have time to read a whole article -- they're standing at a busy bus stop; they're taking a minute while the kids play in the other room. Headings give a sense of place, providing mental bookmarks to come back to, should the reader's bus arrive or they need to sort out a squabble. The headings allow them to quickly find their place, where a thousand words of paragraph text would be tricky.


## Screen readers

Scouting ahead via headings is even easier for users who access a web page in a non-visual way via screen reader user -- they can easily bring up a list-style outline, and even jump to a particular section if they like. In fact, this is the most popular way a screen reader user navigates a page, according to the [WebAIM Screen Reader User Survey #8](https://webaim.org/projects/screenreadersurvey8/#heading) (August - September 2019).

> The usefulness of proper heading structures is very high, with 86.1% of respondents finding heading levels very or somewhat useful.

To find what they're after [68.8% of users say they would navigate using the headings](https://webaim.org/projects/screenreadersurvey8/#finding).

A quick comparison with users [who navigate by landmarks](https://webaim.org/projects/screenreadersurvey8/#landmarks) like the header, main content, footer reinforces headings as the best way to provide on-page navigation for screen reader users:

> The frequent use of landmarks and regions has continually decreased from 43.8% in 2014, to 38.6% in 2015, to 30.5% in 2017, to 26.6% on this survey.


## Good for everyone

As ever, accessibility is not just about users with access needs -- it's about everyone. With the recent lockdown due to the Coronavirus outbreak, Gary Hustwit's film Objectified was [made available for free](https://www.ohyouprettythings.com/free) and, among the hundreds of great quotes, one from Dan Formose from [Smart Design](https://smartdesignworldwide.com) stands out:

> we don’t care [about the average user]. What we really need to do to design is look at the extremes. The weakest, or the person with arthritis, or the athlete, or the fastest, or the strongest person. Because if we understand what the extremes are, the middle will take care of itself


## How do I check a document's outline?

You can normally see a document's outline by visually scanning the headings. `<h1>`s are normally the biggest, `<h2>` slightly smaller, and so on.

There are tools available too: you could use a [browser plugin](https://chrome.google.com/webstore/detail/html5-outliner/afoibpobokebhgfnknfndkgemglggomo?hl=en) or a web page where you can [add a URL or paste your code](https://gsnedders.html5.org/outliner/).

As mentioned, if you're using a screen reader it's usually only a few key-presses away. On VoiceOver, for example, you can bring up your Rotor with <kbd>⌃</kbd> + <kbd>⌥</kbd> + <kbd>u</kbd>, then use the arrow keys to find the 'Headings' view.

