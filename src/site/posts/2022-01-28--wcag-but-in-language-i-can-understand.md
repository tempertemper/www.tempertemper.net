---
title: WCAG, but in language I can understand
intro: An as-close-to-a-single-paragraph-as-I-can-manage summary of each rule in the Web Content Accessibility Guidelines, version 2.1, level AA.
date: 2022-01-28
updated: 2022-05-12
tags:
    - Accessibility
summaryImage: wcag.png
summaryImageAlt: The letters ‘WCAG’ with ‘2.1 AA’ underneath.
related:
    - wcag-2-2-in-language-i-can-understand
    - wcag-aaa-in-language-i-can-understand
featured: true
---

The [Web Content Accessibility Guidelines (WCAG)](https://www.w3.org/TR/WCAG21/) make for difficult reading, so I thought I'd write a short article on each success criterion, explaining it in easy to understand language.

But then I changed my mind! There are a 50 success criteria at levels A and AA in WCAG 2.1, a further 28 success criteria at level AAA, and WCAG 2.2 has another 9 criteria. That's a *lot* of articles! I have *other things* I'd like to write about, and, besides, [DigitalA11Y have already done a good job of it](https://www.digitala11y.com/wcag2-0/).

Instead, I've decided to write a single article summing up each success criterion in WCAG 2.1 in as close to a single paragraph as I can manage. Some things worth mentioning before I dive in:

- This is for me, but hopefully it will help you get your head around what each rule is driving at
- It's far from a full explanation; you've got [WCAG itself](https://www.w3.org/TR/WCAG21/) and [Understanding WCAG](https://www.w3.org/WAI/WCAG21/Understanding/) for that:
    - There's plenty of over-simplification in order to get to the essence of each rule
    - Lots of exceptions that criteria mention are missing in the name of brevity
    - There are very few measurements (for example the actual text spacing values in [1.4.12 Text Spacing](https://www.w3.org/TR/WCAG21/#text-spacing))
- Some success criteria have been grouped as they kind of feel like the same thing
- I haven't gone into why each criterion is helpful
- There are very few examples, except where they help keep things brief


## Perceivable

Can people read the content?

### Text Alternatives

#### 1.1.1 Non-text Content
Images should have descriptive (`alt`) text.

### Time-based Media

#### 1.2.1 Audio-only and Video-only (Prerecorded)
Any audio-only content should also be conveyed in text. Any video content with no audio track should also be described in text, but could also be described with audio.

#### 1.2.2 Captions (Prerecorded)
The audio in videos should be captioned.

#### 1.2.5 Audio Description (Prerecorded)
Actions, descriptions, and other important non-audio content in videos should be described in captions or a separate audio track.

<i>(The level A 1.2.3 Audio Description or Media Alternative (Prerecorded) allows for a text-based alternative, like a screenplay, but only captions meet AA, so 1.2.5 trumps 1.2.3.)</i>

#### 1.2.4 Captions (Live)
Live videos that include audio should be captioned.

### Adaptable

#### 1.3.1 Info and Relationships

Information or relationships between content that is visual is also conveyed through the code, via HTML or ARIA (for example the `for` attribute on a form label, or `aria-describedby` on an input that has hint text).

#### 1.3.2 Meaningful Sequence
The visual presentation of the content matches what's read out by a screen reader.

#### 1.3.3 Sensory Characteristics
Nothing is referred to just by its colour, size, position, shape, and so on.

#### 1.3.4 Orientation
Rotating a device from portrait to landscape doesn't stop everything being perfectly readable and usable.

#### 1.3.5 Identify Input Purpose
The purpose of form fields can be identified by the browser, so that auto-complete suggestions can be offered in a dropdown.

### Distinguishable

#### 1.4.1 Use of Color
Colour shouldn't be the only thing used to convey meaning.

#### 1.4.2 Audio Control
Auto playing audio that lasts more than 3 seconds can be turned down or stopped.

#### 1.4.3 Contrast (Minimum) and 1.4.11 Non-text Contrast

Text has a contrast ratio of 4.5 to 1. Large text can be a 3 to 1 ratio if it's over 24px, or bold and over 19px.

Usable components like form fields and buttons, and graphics like pie charts and line graphs should have a contrast ratio of 3 to 1.

#### 1.4.5 Images of Text

Text is actual text; not images of text.

#### 1.4.4 Resize text, 1.4.10 Reflow, and 1.4.12 Text Spacing

The page is fully responsive. The content adjusts and is readable and usable:

- When the zoom is set to 200% in the browser's settings
- At viewport sizes from 320px and up
- When custom styles are added to increase the spacing of letters, words, lines, and/or paragraphs

#### 1.4.13 Content on Hover or Focus

Tooltips can be dismissed, usually via the <kbd>esc</kbd> key,  the text in the tooltip can be selected, and they don't time out on their own.


## Operable

Can people use the content?

### Keyboard Accessible

#### 2.1.1 Keyboard
Can you get around using the keyboard alone.

#### 2.1.2 No Keyboard Trap
There shouldn't be a situation where you enter a modal with the keyboard and can't get back to where you were.

#### 2.1.4 Character Key Shortcuts
Keyboard shortcuts should use modifier keys, like <kbd>ctrl</kbd>, <kbd>command</kbd>, or <kbd>alt</kbd>/<kbd>option</kbd>.

### Enough Time

#### 2.2.1 Timing Adjustable
Time limits should be avoided unless they're able to be extended.

#### 2.2.2 Pause, Stop, Hide
Automatically moving/animating content that lasts more than 5 seconds should be able to be stopped or hidden.

### Seizures and Physical Reactions

#### 2.3.1 Three Flashes or Below Threshold
Nothing flashes, blinks, or flickers more than three times in one second.

### Navigable

#### 2.4.1 Bypass Blocks
'Skip links' are available for keyboard users to jump past navigation.

#### 2.4.2 Page Titled
Each page has a unique `<title>` that describes what's on that page.

#### 2.4.3 Focus Order
When a keyboard user tabs through a page, the order goes from top to bottom and left to right, as you would read the page.

#### 2.4.4 Link Purpose (In Context)
It is clear where a link will take you from either:

- The link text itself
- The information in the sentence leading up to the link

#### 2.4.5 Multiple Ways
Header navigation is not the only way to get around a website; there's another way, such as on-page links, a sitemap, or a site-wide search.

#### 2.4.6 Headings and Labels
Headings are descriptive of the content that they contain, form labels clearly describe what information is required, and buttons inform the user what will happen when they're pressed.

#### 2.4.7 Focus Visible
There's visible keyboard focus styling to indicate which element you're currently focused on.

### Input Modalities

#### 2.5.1 Pointer Gestures
All actions that are carried out using a gesture (swiping, pinching, and so on) or drawing, can also be done with a button or buttons.

#### 2.5.2 Pointer Cancellation
Actions (like pressing a button) aren't triggered on mouse-down; rather on mouse-up.

#### 2.5.3 Label in Name
The visible text of a form field, button, or link matches the text in the underlying code.

#### 2.5.4 Motion Actuation
There is no reliance on device motion, like shaking or tilting, to carry out an action.

## Understandable

Can people understand the content?

### Readable

#### 3.1.1 Language of Page
There's a `lang` attribute on the `<html>` element that matches the language of the page.

#### 3.1.2 Language of Parts
Any parts of the page that are in a different language to the page itself are marked up with the appropriate `lang` value. Names and phrases *derived* from other languages, like "Déjà vu" in English, don't need this.

### Predictable

#### 3.2.1 On Focus and 3.2.2 On Input
Nothing unexpected changes when:

- something on the page receives keyboard focus, like a `<button>`
- the value of a form field, like an `<option>` in a `<select>`, is chosen

#### 3.2.3 Consistent Navigation
Navigation is consistent from page to page.

#### 3.2.4 Consistent Identification
Things that carry out a particular function should always look and work the same.

### Input Assistance

#### 3.3.1 Error Identification and 3.3.3 Error Suggestion
Error/validation messages should be communicated in text, and should provide suggestions to help the user successfully proceed.

#### 3.3.2 Labels or Instructions
As much help as is needed is offered to prevent triggering a form error; the form label may be enough, but hint text may also be required.

#### 3.3.4 Error Prevention (Legal, Financial, Data)
Important forms like a legal agreement or submitting financial information offer the opportunity to check the information entered before sending.


## Robust

Can machines (browsers, screen readers, etc.) read the code?

### Compatible

#### 4.1.1 Parsing
The markup has been validated and there are no errors, such as duplicate `id`s, missing tags, and invalid child elements.

#### 4.1.2 Name, Role, Value

The semantic meaning of every interactive element (form controls, links, headings, landmarks, tables, and so on) is correct, and each has an accessible name.

#### 4.1.3 Status Messages
Messages like form errors and success pop-ups are communicated to assistive technology like screen readers.
