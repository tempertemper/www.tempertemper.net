---
title: When design breaks semantics
intro: |
    Semantic HTML is great. But sometimes following the rules is tricky. Grab a cuppa and let me tell you a story about links that look like buttons.
date: 2020-06-25
tags:
    - Design
    - Development
    - Accessibility
featured: true
summaryImage: large
---

Semantic HTML is great. If you see a list, it'll be marked up as a list. If you see a heading that's bigger than all the others on the page, it'll be an `<h1>`. Great. Everyone wins.

But what happens when an element looks like something else? Why would we do that, I hear you ask? Well, it doesn't happen very often, but there's one situation where it happens all the time, from marketing websites to multi-million pound web products: links that look like buttons.


## A link is a link, except when it's not

A link should look like all the rest of the text around it, but with an underline (and maybe a bit of colour to draw the eye). As far as its functionality goes, it should take the user to another place.

A button, on the other hand, should perform some kind of action. They can submit a form or trigger a JavaScript action, like revealing a drop-down or adding an item to your shopping cart. What it *shouldn't do* is link to a new page -- that's a link's job!

Buttons should look like buttons; chiefly using a background colour that 'lifts' them from the rest of the page, but maybe with some rounded corners, a border, or a gradient instead of a solid background colour. They *don't* look like links.

But what about where links need to look like a button ('link-buttons')? Think of all those 'Get in touch' or 'See pricing plans' calls to action across the web. Most of them look like buttons, as a link just isn't high-impact enough.

What about when a link is part of a series of form questions, so the user expects a button to proceed to the next part of the form, even if the page is for information and isn't actually submitting a response?

[Joe Lanman puts it beautifully](https://github.com/alphagov/govuk_elements/pull/272#issuecomment-233173213) in a really interesting [GOV.UK GitHub Pull Request](https://github.com/alphagov/govuk_elements/pull/272) comment:

> Visual priority is one of the most effective tools we have in making things easy to understand. So if we design a page to [be] semantically correct, the visual priority might be wrong, and the same the other way round.


## But what's the problem?

Ok, so links can legitimately look like buttons. But why is that an issue? Surely users see a button, click it, and are taken to a new page; no big deal. But what about users of assistive technology?

### Voice control

If a user sees a button and tells their voice control software (e.g. [Dragon](https://www.nuance.com/dragon.html)) to "click the button", the software might not know there's a button there as it 'sees' `<a>`, not `<button>`. If you told it to click the *link* it would understand, but the user doesn't *see* a link, they see a button!

### Screen readers

You'd think non-sighted users using screen reader software would be told there's a link, and that'd be fine, but there's also the issue of *consistency* for those users. Sometimes to go to the next page they use a button, sometimes a link, and that inconsistency can create unnecessary questions.

It's also worth considering sighted (or low-vision) users who can see what's on the screen but also use a screen reader. According to Simply Accessible, some [people with dyslexia use screen readers](//simplyaccessible.com/article/user-needs-dyslexia/) in order to better understand the content:

> Some dyslexic users benefit greatly from using a screen reader in addition to scanning a page visually

These users are going to *see* a button but *hear* a link, and the last thing we want to do as designers is have our users pausing for thought unnecessarily.


## And the fix?

We can't switch to using `<button>`s with some JavaScript to change the behaviour to that of a link, as that would leave things broken for users who aren't running JavaScript or for whom JavaScript has failed. We want to *progressively enhance* the experience, starting from the baseline of a link.

### Changing the implicit role

Fortunately, the fix is simple: we add `role="button"` to the link-button. This tells assistive tech that that the link is semantically a button, even though it'll still behave like a link. So voice control software will know what to do when you tell it to "click the button", and screen reader software will tell users there are buttons, which is consistent with what the sighted/partially-sighted screen reader user might see on the screen.

But that's only part of the problem fixed. **Behaviour** is also an issue.

### Links are draggable, buttons aren't

Use your mouse to click and hold on a link, now move it. That might not be something you've done much, or even something you realised was possible, but that link (just like images) can be dragged up into your browser's tab bar to open that link in a separate tab, running in the background.

Adding the `draggable="false"` attribute to button-links ensures they behave more like buttons, which aren't draggable.

### Keyboard behaviour

Keyboard users know how to interact with a button -- they use the Return key or the spacebar to press it.

Links are slightly different -- Return follows the link when it has focus, but hitting the spacebar will scroll the page down… So if a keyboard user presses space on your link-button, expecting it to activate, they're going to get something they didn't expect.

So we need to activate the 'button' when the spacebar is pressed, instead of scrolling the page. JavaScript to the rescue!

The [JavaScript I use to make link-buttons behave](https://github.com/tempertemper/tempertemper.net/blob/develop/src/js/link-buttons.js) looks like this:


```js
(function () {
  'use strict';
  function a11yClick(link) {
    link.addEventListener('keydown', function(event) {
      var code = event.charCode || event.keyCode;
      if (code === 32) {
        event.preventDefault();
        link.click();
      }
    });
  }
  var a11yLink = document.querySelectorAll('a[role="button"]');
  for ( var i = 0; i < a11yLink.length; i++ ) {
    a11yClick( a11yLink[i] );
  }
})();
```

This is cribbed/adapted from [Jon Hurrel's code](https://github.com/alphagov/govuk_elements/pull/272#issuecomment-234202285) on the GOV.UK GitHub PR thread I mentioned earlier.


## Not ideal

Again, from that GitHub thread, [Vincent Pickering mentions his misgivings](https://github.com/alphagov/govuk_elements/pull/272#issuecomment-234218842):

> alarm bells ring loudly for me when any default browser behaviour is changed via JS

I totally agree, but this is one of those times when we need a 'hack' to make the best of a bad situation.
