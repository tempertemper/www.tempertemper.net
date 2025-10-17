---
title: Headers, headings, and titles
intro: What we call things is important, often as it helps us communicate clearly with our team; there are three terms I regularly hear used interchangeably.
date: 2025-10-17
tags:
    - HTML
---

What we call things is important; for all sorts of reasons but a lot of the time it's to make communication between team members unambiguous.

What a designer calls something should not differ from what a developer calls it, and the easiest way to ensure this consistency is to use the terminology defined by the super smart people who wrote HTML and ARIA.

The [HTML Working Group](https://www.w3.org/groups/wg/htmlwg/), [HTML Living Standard](https://html.spec.whatwg.org/multipage/), and [ARIA Working Group](https://www.w3.org/WAI/about/groups/ariawg/) spend months and years carefully defining new features (although these groups are not infallible, as the [Incomplete List of Mistakes in the Design of CSS](https://wiki.csswg.org/ideas/mistakes) from the CSS Working Group proves).

Three particular terms I often hear being used interchangeably are 'header', 'heading', and 'title'; I even catch myself doing it sometimes! Let's have a look how they can be confused.


## Header versus heading

I recently wrote about [whether headings should be in the header](/blog/page-headings-dont-belong-in-the-header), so let's start with those two terms. [MDN Docs says the header](https://developer.mozilla.org/en-US/docs/Web/HTML/Reference/Elements/header):

> represents introductory content, typically a group of introductory or navigational aids.

So that's the header strip at the top of a page. Logo, strapline, navigation, login; that kind of thing.

Headings, on the other hand, are the `<h1>` to `<h6>` elements, which are used to create [the document's 'outline'](/blog/using-the-html-document-outline). These can be found all over the page, not just at the start.

You'd be forgiven for referring to the `<h1>` as the 'header', since that is usually at the top of the page. They also both start with 'head'. But they are two very distinct elements, and careful naming here will prevent any crossed wires.


## Title versus heading

It's a very similar story for 'title' and 'heading', where people often refer to the `<h1>` as the title. Going back to MDN Docs and their [description of the `<title>` element](https://developer.mozilla.org/en-US/docs/Web/HTML/Reference/Elements/title):

> The `<title>` HTML element defines the document's title that is shown in a browser's title bar or a page's tab

It's metadata that isn't output to the page itself. It's what you see in the browser tab for each web page, it appears in your browsing history, and search engines use it as the heading for a page in their results. The page title is also the first thing a screen reader user will hear when they arrive on a new page.

The main page heading is not, of course, the page title, but the content of the `<title>` element probably matches the `<h1>` pretty closely. HMRC have some really nice guidance on the [makeup of a page title](https://design.tax.service.gov.uk/hmrc-design-patterns/page-title/):

> The page title can have up to 4 items separated by dashes. They are:
>
> - the same `<h1>` as the page
> - section name, which you should only include if the service has more than one section
> - service name
> - GOV.UK

So the main heading content is probably the first part of the page title, but they have very different uses. The `<h1>` describes the content on the page, where the `<title>` should do this as well as giving details on things like the section the page is in, the product it belongs to, and business name itself.


## Title versus header

This is just here for completeness, but I don't think there's any need to talk about using title and header interchangeably as I've never seen those terms confused.


## Why we mix them up

We muddle these terms for lots of reasons: overlapping meanings in plain English, historical habits in teams, and the blurred line between visual layout and technical specifications.

For me, the reason I sometimes find myself referring to the page heading as the header or title is that 'header' and 'title' are much quicker to say, where 'heading' needs a bit of extra definition: 'page heading', 'main heading', or maybe even 'heading level 1' or 'h1'.

Hopefully that clarifies what each of the three are and, like me, you can catch yourself quickly if you use the wrong terminology, preventing misunderstandings within your team.
