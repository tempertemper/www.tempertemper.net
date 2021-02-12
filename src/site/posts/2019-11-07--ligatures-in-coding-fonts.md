---
title: Ligatures in coding fonts
intro: |
    I enjoy freshening my coding environment up a wee bit every now and then, but typefaces with ligatures are a step too far.
date: 2019-11-07
tags:
    - Development
---

In the same way I enjoy [trying out a new text editor](/blog/still-a-sucker-for-sublime) every now and then, I do the same with my code colour scheme and font -- a change just freshens things up a bit.

For a long time, I've used [Mozilla's Fira Mono](https://mozilla.github.io/Fira/) and have been very happy with it -- I have no trouble distinguishing similar characters (`0`s and `O`s, `1`s and `l`s), it renders nicely on macOS and is comfortable to read.

But my head was turned by a fork of Fira Mono called Fira Code; it's pretty much exactly the same but with one killer feature: ligatures. Coding ligatures are the 'correct' way of writing operators, for example:

- `!=` (not equal to) becomes `≠`
- `>=` (greater than or equal to) becomes `≥`
- `=>` (the JavaScript arrow function) becomes `→`

Using `!=` as an example, you still type two characters, `!` and `=` but Fira Code makes them look like a `≠`; the only difference is that the new not equal sign spans two characters, taking up the same amount of space as the characters you typed. Press backspace once and it leaves you with a `!`. Nifty.

There are *loads* of operator ligatures as well as other nice touches like the classic `Fl`/`fl` ligatures and nicely joined-up Markdown header `###` markers. The [Fira Code repo](https://github.com/tonsky/FiraCode) has an easy-to-look-at comparison of all the ligatures it uses alongside Fira Mono.

But I couldn't get used to it! And I realised I didn't *want to* get used to it. [I like customisation, but I have my limits](/blog/why-im-not-using-git-aliases), and ligatures feel like they go too far:

- I've been writing ligature-less operators for the longest time so I'd've had to effectively re-learn them
- I'd then be locked in a world where I could only use coding fonts with operator ligatures, which would limit my options
- Using ligatures for code examples on my website, presentation or [video](https://www.youtube.com/tempertemper) would mean most people would have to work harder to translate my fancy operators back into the ones they use every day

There are plenty of [alternatives to Fira Code](https://github.com/tonsky/FiraCode#alternatives) and as Nikita Prokopov, who made the Fira Code fork, says:

> Ideally, all programming languages should be designed with full-fledged Unicode symbols for operators

If they were, I wouldn't have a problem using them as they'd be universally expected and understood, but until operator ligatures are the norm I'm happy with my boring old unjoined-up operators.
