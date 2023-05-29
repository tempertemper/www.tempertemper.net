---
title: Don't meddle with user input
intro: Every idea comes from a good place, but some well-intended features are actually bad for usability; limiting form field input is one of those things.
date: 2023-05-29
tags:
    - Design
    - Accessibility
---

A big part of my role involves coaching designers and developers to produce more accessible work; not once have I spoken to someone who's actively trying to make the people's experiences worse. Every idea comes from a good place, and one I encounter frequently is limiting the characters a user can type in a form field.

Every time this comes up I refer to one of my favourite GitHub Issues, where the following suggestion is made for the [GOV.UK Date Input component](https://design-system.service.gov.uk/components/date-input/):

> Date fields currently allow users to enter as many characters as they want but day and month input values should be at most 2 characters long, and year input fields at most 4 characters long. This could be made clearer and more user friendly with the `maxlength` attribute.

[Hanna Laakso responds](https://github.com/alphagov/govuk-design-system/issues/1977):

> If you use `maxlength` attribute on a form field to limit user input, users might not receive appropriate feedback of the limit. For instance, the user might not notice that not all the information they entered appeared in the form field … It is generally better to let users enter their information in a way that suits them and allow them to submit the form.

Hanna then goes on to suggest using hint text to <q>help the user to enter the date in the right format</q>, and validation to <q>tell the user how to fix the problem in any particular form field</q>. This should also help satisfy [3.3.2 Labels or Instructions](https://www.w3.org/TR/WCAG21/#labels-or-instructions) and [3.3.3 Error Suggestion](https://www.w3.org/TR/WCAG21/#error-suggestion), respectively, from the Web Content Accessibility Guidelines (WCAG).

Sometimes the things we think might help a user achieve a task actually get in the way. Worse, things like `maxlength` affect user input, so we can't be absolutely sure that the information we have received is what was intended.
