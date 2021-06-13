---
title: The trouble with back links
intro: |
    You'd think that adding a back link to a web page would be straightforward. Well, it turn out that it's not! Let's have a look at three ways to do it.
date: 2021-06-04
tags:
    - HTML
    - Development
summaryImage: large
---

You'd think that adding a back link to a web page would be straightforward. Well, it turn out that it's not.

Before I go into the approaches and their pros and cons, it's worth mentioning that a back link is often important to the design of a page: it reassures the user that they can go back a page without breaking anything or corrupting data.

There are a few ways to add a back link to a web page:

1. Hard-code the back link's `href` value
2. Build a record of the user's journey from page to page so that the back link's `href` can be populated with the last page they were on
3. Lean on the browser's record of the user's journey from page to page, and access it with JavaScript


## Hard-coding the `href`

On the face of it, approach 1 sounds like a good idea. It's simple and it'll always work, as there's no reliance on JavaScript (which may not work in every user's browser), but it's rather limited as it depends on a few things:

- The journey you're building must be linear; say a series of sequential questions (otherwise how would we know the last page the user was on?)
- The user must start the journey on the first page, as going 'back' to a page they've never been to could be an odd experience
- Conditionally revealed pages aren't possible. Imagine a three page flow:
    - On page A, a 'Yes' navigates to page B
    - On page A, a 'No' skips straight to page C
    - Page B, if the user sees it, navigates to page C
    - Does the back link on page C go to page A or page B?
- Leaving and rejoining the journey should be impossible, or the back link won't necessarily go back to the page they were on previously
- The user must only use on-page back navigation; going back a page via the browser's back button, a swipe gesture, or keyboard shortcut will break things


## Building a history

Like the hard-coded back link, we can always count on our link to work since the `href` is determined on the backend before it reaches the browser. It's also a much better approach than the first as it allows for less linear journeys, conditional pages, and jumping in and out of a journey. But there are still downsides:

- It can be complex to build, and there are questions like is the history saved in a cookie or is it stored in a database, how long is it saved for, etc.
- Things could also get very messy if the user uses a combination of the on-page back link and the browser's back button

It's worth exploring why using a back link and back button interchangeably can cause serious usability problems.

You see, our hard-coded back link isn't really going back a page; it's navigating *forward* to the previous page in the sequence. Let's say the user:

1. Starts on page A
2. Proceeds to page B
3. Proceeds to page C
4. Follows the on-page back link, which takes them to page B
5. Presses the browser's back button on page B, and is taken to page C

The expected destination of the second 'back' press in step 5 would be page A, but it isn't because the first 'back' press actually moved forward a page!

We could try to prevent this (and I should mention that this is  **not recommended**) by disabling the browser's back button. But there are still swipe gestures, programmed mouse buttons and keyboard shortcuts that will take the user back a page. And overriding default browser behaviour is almost *always* a terrible idea.

We could also try to avoid the 'back' metaphor and use language that implies forward movement. Maybe something like "Go to page B" would work when you're on page C. I'm not so sure though: not only would that have to be thoroughly tested with users, but it would require greater content design skills than I possess!


## Leverage the browser's history

The third approach allows for less linear journeys, conditional pages, and jumping in and out of the journey; just like the dynamically populated `href` approach. It's also extremely simple to implement. And because the JavaScript is accessing the browser's history, the link does exactly the same thing as pressing the built-in back button: no more tangles!

```html
<a href="javascript:history.go(-1)" class="link-back">Back</a>
```

The only downside here is that it's JavaScript dependant, so if the script fails or the user has JavaScript turned off the link won't work.

The smart way to do this is to use progressive enhancement so that the back link is only created if JavaScript runs successfully. Let's add our back link as the first child of the `<main>` element:

```js
var newLink = document.createElement("a");
var mainElement = document.getElementById("main");
newLink.setAttribute("href", "javascript:history.go(-1)");
newLink.setAttribute("class", "back-link");
newLink.innerHTML = "Back";
mainElement.prepend(newLink);
```

If JavaScript doesn't run, the back link won't appear; the advantage there is that if there's a back link on the page it'll work.

Of course, it does mean that in some circumstances the user won't get that back link, so their experience won't be as perfect as we'd like, but the web isn't a perfect place and often it's a case of weighing up the pros and cons of a technique. Here, it's the mitigation of any confusion in the experience and the cost is there's a risk of no back button. I'm happy with that trade-off.
