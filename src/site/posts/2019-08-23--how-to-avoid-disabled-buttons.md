---
title: How to avoid disabled buttons
intro: |
    Disabling a submit button can indicate that a form isn't ready to be sent; there are various ways to design this, but should we be doing it at all?
date: 2019-08-23
tags:
    - Design
---

I enjoyed [an article on disabled-state buttons](https://uxmovement.com/buttons/why-you-shouldnt-gray-out-disabled-buttons/) by UX Movement last week.

> Instead of graying out your disabled buttons, you should decrease the opacity to make it transparent. When the disabled button is transparent, users can see some semblance of the button in its enabled state. Although the button is faded out, some color still bleeds through for users to recognize. As the disabled button transitions to its enabled state, the new appearance won’t catch users off-guard

I totally agree that using a transparent button rather than a grey one is *better*, but it still comes with a lot of the downsides a greyed-out button does.

Greying a button out or making it slightly transparent leaves the user without a comparison point -- how do they *know* the button is greyed out or transparent? If they don't have a button right next to it that is enabled, they might not realise it's not the enabled colour. They've got their memory of course, but relying on users' ability to recall and compare an enabled colour from memory is a risky strategy.

And remember that with colour we're talking about the 'ideal' user:

- What about users with old or low quality screens?
- What about users with low vision or colour blindness?
- What if they're in a rush and don't realise the button's disabled?
- Even if you do have an enabled button as a comparison point, what if the user is using screen zoom and can't see them both on screen at the same time?

The [GOV.UK Design System](https://design-system.service.gov.uk/components/button/) says that:

> Disabled buttons have poor contrast and can confuse some users, so avoid them if possible.

To me, disabled buttons (whether greyed out or transparent) feel like over-designing -- one of those occasions where simpler is better.

**Validation** is the first tool I'd reach for, and it would work in the vast majority of cases for buttons that submit a form.

Do we really need to change the button at all? Wouldn't the example of the Expedia login form in [Anthony's article](https://uxmovement.com/buttons/why-you-shouldnt-gray-out-disabled-buttons/) work just as well with the 'Sign in' button in its normal state, and for empty fields to be highlighted using validation if the user clicks the button or hits <kbd title="Return">⏎</kbd> too soon? Any data entered would be stored safely in the backend, so nothing would be lost, and empty fields would be highlighted as empty.

This approach works well with both short and longer forms, although other user experience issues can creep in when there are lots of inputs to validate or conditionally revealed inputs. In these cases, I'd suggest breaking the longer form down into smaller chunks; not only does this normally solve complex validation messages, it reduces the cognitive load on the person filling out the form and allows data to be saved steadily as they progress through the series of forms.

Use validation wherever possible, and where validation won't work well, try breaking the form up into more manageable chunks.
