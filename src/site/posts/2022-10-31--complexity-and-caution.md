---
title: Complexity and caution
intro: Whenever we design a non-standard but seemingly helpful behaviour, keep asking yourself if it's the right thing to do for all users.
date: 2022-10-31
tags:
    - Design
    - Accessibility
---

There's an article on GOV.UK that I refer to again and again called [Why we care more about effectiveness than efficiency or satisfaction](https://userresearch.blog.gov.uk/2017/04/18/why-we-care-more-about-effectiveness-than-efficiency-or-satisfaction/).

The example they use is automatically moving focus from one input in a group to the next:

> Forms on GOV.UK don’t auto-tab.
>
> In usability testing, some participants notice this. And they grumble about it. But they quickly enter the information they need to, and move on.

This behaviour is often found on forms where there's a group of inputs that relate to the same thing, for example:

- A date, where there are three fields, one each for Day, Month, and Year (like 01 01 2000)
- A bank sort code, which in the UK would typically be broken down into three pairs of numbers (like 11 11 11)
- A credit card number, which is usually four sets of four numbers (like 1111 1111 1111 1111)

I use the keyboard a lot, and auto-tabbing trips me up every time; let's use the date example:

1. I enter the Day
2. I press tab without noticing I have been auto-tabbed to the Month field already
3. I enter the Month value, but my cursor is in the Year input due to the auto-tab and my manual press of the <kbd>tab</kbd> key
3. I realise what has happened, delete the value I entered in Year and <kbd>shift</kbd> + <kbd>tab</kbd> back to Month field
4. I forget again and end up moving my focus to the field *after* the Year input!

Frustrating. But there are more questions here:

- Will someone who enters a double digit Day, is auto-tabbed to Month, but enters a single digit, know why they haven't been auto-tabbed to Year?
- Will someone who enters a single digit value in Day, clicks or tabs to the next field, then enters a double digit month and is auto-tabbed to Year know what's going on?
- How will screen reader users fare when they hear a new input immediately upon entering a second digit in the Day or Month field?
- Does the cursor still move to the next field if an invalid character is entered in the Day or Month?
- What if someone wants to type the day or month as words? There's a good reason to allow 'First' or 'October', and so on, but would auto-tabbing kick in after typing 'Fi' or 'Oc' or would we detect the characters?
- Will validation prevent single digit days and months being submitted, or will it require leading zeros (for example 'O1')? In other words, is the auto-tabbing a bit like an early validation? How would the user know this?
- Will some users expect to be auto-tabbed from Year to the subsequent form field on the page?

For me, an interaction pattern like this that raises so many questions also raises alarm bells! I've written before about how [changing default browser behaviour can be problematic](https://www.tempertemper.net/blog/opening-links-in-a-new-tab-or-window-is-better-avoided), and I stand by that.

The GOV.UK article finishes up by saying:

> if auto-tabbing stops just a few people from using a service successfully, their needs take priority over the many people who might prefer but don’t need the feature.
>
> Effectiveness for all users takes priority over efficiency or satisfaction for some users.

Introducing complexity of any kind should always be done with caution. We might benefit a majority of users, but if we're making life difficult for the rest it's the wrong thing to do.
