---
title: Accessibility issues when removing list markers
intro: |
    If we remove the list markers from an ordered or unordered list, we're likely to run into some issues with VoiceOver.
date: 2020-10-12
tags:
    - Accessibility
    - CSS
    - Development
---

If we remove the visual list markers from an ordered or unordered list with `list-style-type: none;`, we're likely to run into some issues with VoiceOver, Apple's screen reader software.

You'd think that when the user reaches a list, VoiceOver would:

1. announce the list
2. state the number of items
3. step through each item in turn, counting as it goes
4. announce the end of the list

That happens when the list has bullets or numbers, of course, but if those visual markers are removed, VoiceOver, not unreasonably, does likewise when it reads the list out. In other words, Apple decided that if something doesn't *look* like a list---even if it's marked up as a list---it shouldn't be *read* as a list.

It would never have been too much of an issue if lists were used sparingly, but us web developers love a list, which can get very noisy for screen reader users. With so many lists, and some that don't even *look* like lists, Apple's call starts to make sense.


## The solution

There are bound to be occasions when you want a list to be read out properly on VoiceOver but visual list markers don't make sense. An in-line navigation group is be a good example, where a screen reader user would find it useful to know how many items they had to go through, but bullets would look odd, with those items all in a row.

Scott O'Hara offers a [solid HTML-based solution](https://www.scottohara.me/blog/2019/01/12/lists-and-safari.html#a-fix-for-the-fix) by adding `role="list"` to the `<ul>` or `<ol>`, but if CSS is the culprit, the fix should also be with CSS, which is where [the real fix](https://unfetteredthoughts.net/2017/09/26/voiceover-and-list-style-type-none/) comes in:

```css
li {
  list-style-type: none;
}

li:before {
  content: "\200B";
}
```

VoiceOver views a zero-width space (`\200B`) as a valid bullet character, so it happily reads all of the the list information, even though it doesn't *look* like a list. It could be argued that this is a hack, but, equally, the VoiceOver behaviour could be seen as a bug.


## Consider your lists carefully

The lesson here is to be more deliberate with our lists. Not everything that consists of multiple items is necessarily a list. And if something *definitely is* a list, carefully consider if it's actually a good idea to remove the markers.

Sometimes a list---rightly---makes it through that scrutiny and out into the wild, which is where that CSS fix comes in handy.
