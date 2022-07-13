---
title: WCAG AAA in language I can understand
intro: A follow-up to my post on the Web Content Accessibility Guidelines 2.1, level AA; this time covering the rules that make up the stricter level AAA.
date: 2022-03-23
tags:
    - Accessibility
summaryImage: wcag-aaa.png
summaryImageAlt: The letters ‘WCAG’ with ‘AAA’ underneath.
related:
    - wcag-but-in-language-i-can-understand
    - bag-some-aaa-wins-where-you-can
---

In this final part of my makes-sense-to-Martin summary of the Web Content Accessibility Guidelines (WCAG), I cover the often hard to meet AAA rules (success criteria); it follows on from my posts on [WCAG 2.1 AA](/blog/wcag-but-in-language-i-can-understand) and [WCAG 2.2](/blog/wcag-2-2-in-language-i-can-understand).

Once again, there are a few things I need to point out before you dive in:

- This a way for me to jog my memory, but hopefully it will help you get started understanding the intent of each success criterion
- Almost everything is over-simplified; for a comprehensive explanation you’ve got [WCAG itself](https://www.w3.org/TR/WCAG/)
- I haven’t covered *why* each criterion is helpful
- There are very few examples


## Perceivable

### Time-based Media

#### 1.2.6 Sign Language (Prerecorded)
All video that is published after  video has sign language interpretation.

#### 1.2.7 Extended Audio Description (Prerecorded)
Video is sometimes paused in order to give enough time for audio descriptions to be conveyed properly.

#### 1.2.8 Media Alternative (Prerecorded)
A text-based transcription of a video is offered, on top of closed captions and audio description.

#### 1.2.9 Audio-only (Live)
Live captioning is provided for live audio.

### Adaptable

#### 1.3.6 Identify Purpose
Landmark regions and [personalisation semantics](https://w3c.github.io/personalization-semantics/) have been used, so people can use browser tools to do things like:

- Remove non-essential content
- Add identifying icons to particular elements on the page

#### 1.4.6 Contrast (Enhanced)
Text has a contrast ratio of 7:1 to 1. Large text can be a 4.5 to 1 ratio if it’s over 24px, or bold and over 19px.

### Distinguishable

#### 1.4.7 Low or No Background Audio
For spoken audio content, any background noise or music is 20 decibels lower than the foreground speech.

#### 1.4.8 Visual Presentation
There's a lot packed in this criterion, which covers blocks of text like paragraphs:

- Never justify text
- `line-height` must be at least `1.5`
- Width should be 80 characters max
- Text and background colours can be set by the user (usually via a custom stylesheet)

#### 1.4.9 Images of Text (No Exception)
Text is actual text; never images of text.


## Operable

### Keyboard Accessible

#### 2.1.3 Keyboard (No Exception)
You can navigate and interact with a page using the keyboard alone.

### Enough Time

#### 2.2.3 No Timing
Unless it's a live broadcast or something else that's happening in real time, there are no time constraints placed on the user.

#### 2.2.4 Interruptions
Pop-ups, notifications, and other interruptions can be switched off.

#### 2.2.5 Re-authenticating
If a logged-in session expires mid-way through a task, any data entered after expiry is kept, so that they don't have to re-enter it when they log in again.

#### 2.2.6 Timeouts
A warning is shown if a logged-in session is about to expire.

### Seizures and Physical Reactions

#### 2.3.2 Three Flashes
Nothing flashes, blinks, or flickers more than three times in one second.

#### 2.3.3 Animation from Interactions
Animations triggered by interactions like button presses can be turned off.

### Navigable

#### 2.4.8 Location
The user is clearly informed where they are in a set of pages.

#### 2.4.9 Link Purpose (Link Only)
It is clear where a link will take you from the link text alone, without having to read the text around it.

#### 2.4.10 Section Headings
Headings are used to group distinct sections on a page.

### Input Modalities

#### 2.5.5 Target Size
Anything clickable should be at least 44 by 44 pixels, except links within a sentence which are okay to be the size of the text they encompass.

#### 2.5.6 Concurrent Input Mechanisms
The user can happily switch between using a mouse, touchscreen, keyboard, or any other input device.

## Understandable

### Readable

#### 3.1.3 Unusual Words
Jargon and figurative language is avoided, or, where not it's possible, the words are defined or clarified the first time they're used on a page.

#### 3.1.4 Abbreviations
Acronyms and shortened words are avoided; where not that's possible, a definition are provided on each page they're used.

#### 3.1.5 Reading Level
Writing is kept relatively simple, and is able to be understood by primary school children.

#### 3.1.6 Pronunciation
If a word can be pronounced more than one way, and each way has a different meaning, the meaning is clarified to avoid ambiguity.

### Predictable

#### 3.2.5 Change on Request
Nothing in the user interfaces changes without the user expressly requesting it using a `<button>`.

### Input Assistance

#### 3.3.5 Help
Where a label can't provide enough information to understand what's being asked, there's hint text or some other kind of explanation alongside.

#### 3.3.6 Error Prevention (All)
After entering *any* information, the user is offered the opportunity to check it before sending.
