---
title: If HTML and ARIA don't allow it, it's probably a bad idea
intro: |
    I like to use invalid HTML and ARIA as a design constraint; a line I can't step across. Sounds obvious, but in practice it's not always that simple!
date: 2021-10-25
tags:
    - HTML
    - Accessibility
    - Design
summaryImage: invalid-code.png
summaryImageAlt: An example question from GOV.UK asking “How would you prefer to be contacted?” with the radio button “Email” selected, showing a conditional text input labelled “Email address”.
---

I worked in UK government for a number of years, and every service I worked on contained conditional questions; in other words, questions that would only be asked if a previous question had been answered in a certain way.

There is a [variant of the Radio form component in the GOV.UK Design System](https://design-system.service.gov.uk/components/radios/#conditionally-revealing-a-related-question) that immediately reveals a subsequent follow-on question when a particular answer is given to the first. The Design System's recommendation is to:

> Keep it simple. If the related question is complicated or has more than one part, show it on the next page in the process instead.

The problem here is that it's open to interpretation; one person's definition of 'simple' might be another's 'complicated'. Crossing the line means failing the [On Input success criterion](https://www.w3.org/TR/WCAG21/#on-input) from the Web Content Accessibility Guidelines (WCAG), which says:

> Changing the setting of any user interface component [for example choosing a radio button] does not automatically cause a change of context unless the user has been advised of the behavior before using the component.

A ['change of context' is described](https://www.w3.org/TR/WCAG21/#dfn-change-of-context) by WCAG as:

> major changes in the content of the Web page that, if made without user awareness, can disorient users

In my opinion, a closely related question, such as those given in the GOV.UK Design System example wouldn't constitute a 'change of context'. And the content is designed in such a way that only one question is being asked:

- "How would you prefer to be contacted?" is the question
- "Email", "Phone", or "Text message" are the available responses
- The conditionally revealed question is phrased simply "Email address", "Phone number", or "Mobile phone number", along with a text input

But there's that danger that a designer could abuse this pattern, and that's just what had happened on one government service I inherited. On that service, less closely related conditional questions would be revealed on the same page, and sometimes those questions had further conditionally revealed questions!


## Invalid use of ARIA

More fundamentally, the GOV.UK radio group with conditionally revealed questions contains invalid ARIA. The email radio button's HTML looks like this:

```html
<input class="govuk-radios__input" id="contact" name="contact" type="radio" value="email" aria-controls="conditional-contact" aria-expanded="false">
```

The intention of the `aria-expanded="false"` attribute is to indicate to screen reader users that the control will reveal some content that is currently hidden; screen reader software will usually read something like "collapsed". When the option is chosen the value of `aria-expanded` becomes `true` and screen readers will say "expanded".

Unfortunately `radio` doesn't appear in the 'Inherits into Roles' list in the [`aria-expanded` specifiation](https://www.w3.org/TR/wai-aria-1.1/#aria-expanded), and [`aria-expanded` isn't an allowed property of the `input` role](https://www.w3.org/TR/wai-aria-1.1/#input).

<i>This also applies to [checkboxes that trigger conditionally revealed content](https://design-system.service.gov.uk/components/checkboxes/#conditionally-revealing-a-related-question).</i>

Running the GOV.UK code through [an HTML validator](https://validator.w3.org/nu/), we see the following:

> Error: Attribute `aria-expanded` not allowed on element `input` at this point.

Because this code is invalid, it can't be relied upon to be communicated properly to screen reader users. But even if it were to work as expected, shouldn't we also be informing users visually, to minimise the burden of the unexpected addition of more content?


## Exploring alternatives

This very component has been [written about recently on GOV.UK's accessibility blog](https://accessibility.blog.gov.uk/2021/09/21/an-update-on-the-accessibility-of-conditionally-revealed-questions/) where they acknowledge the validation issue and the potential to cause confusion for some users. They also explore some sensible alternatives, but none quite get past the fact that:

- content is being added to the page without a deliberate user action
- content is being added to the page without clear prior indication, non-visual or visual
- choosing an option in a checkbox or radio set is not an expected trigger for a change in page content


## The simplest solution

For me, at one sniff of invalid HTML, I would look for an alternative. Invalid HTML is invalid for a reason, so I like to think of it as a design constraint; a line I can't step across. After all, constraints are what makes good design.

In this case, I would stick to the [one thing per page](https://www.gov.uk/service-manual/design/form-structure#start-with-one-thing-per-page) guidance, presenting the user with two steps on separate pages:

1. "How would you prefer to be contacted?"
2. Then one of three pages, depending on their answer:
    - "What is your email address?"
    - "What is your phone number?"
    - "What is your mobile number?"

This removes both the validation issue and any surprises for the user, no matter how small; removing any question of failing to satisfy WCAG.


## Not always that easy

Of course, the *ideal* is not always possible in the real world, and the government service I worked on with the problematic conditionally revealed questions is a great example of this. There were so many pages with conditionally revealed questions that I knew we wouldn't be able to split them all onto separate pages due to:

- the technical complexity and development time involved in refactoring each page
- competing project priorities
- a hard delivery deadline

I approached it tactically, asking myself which pages would definitely fail the On Input WCAG success criterion; pages with

- multiple levels of conditional reveals
- complex conditionally revealed questions

The result was a service that passed WCAG 2.1 AA, but there was always that concern that my 'simple' might sometimes be the auditor's 'complicated'.
