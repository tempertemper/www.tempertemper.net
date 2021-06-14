---
title: Form styling limitations are an accessibility issue
intro: |
    A summary of the things missing in CSS got me thinking about how lack of some form styling may have seriously damaged accessibility on the web.
date: 2020-11-27
tags:
    - Accessibility
    - Development
    - CSS
---

I enjoyed a [brief summary of the things people are looking for in CSS](https://css-tricks.com/whats-missing-from-css/) from Chris Coyier on CSS-Tricks:

> Just clicking the reload button a bunch, I get the sense that the top answers are:
>
> - Container Queries
> - Parent Selectors
> - Nesting
> - Something extremely odd that doesn’t really make sense and makes me wonder about people

So I visited the [What's missing from CSS](https://whatsmissingfromcss.com/) site and hit the reload button.

What Chris doesn't mention, which I saw a couple of times, was *form styling*. There are a few key form elements that are difficult/impossible to style, and even require a lot of extra markup and some seriously hacky CSS. I'm thinking of `<option>`s in `<select>` and `<datalist>` elements, `<input type="file" />`.

I wonder how much `<div>`-based, ARIA-fuelled (if we're lucky) custom-built alternatives could have been avoided if we had just been able to style those problem elements easily in CSS cross-browser?

I've noticed a fair bit of noise about this lately, including:

- [Styling a Select Like It's 2019](https://www.filamentgroup.com/lab/select-css.html)
- [Design systems, frameworks and browsers](https://youtu.be/3gIY_jaDOK0?t=927), a talk by Nicole Sullivan
- [Can we please style the `<select>` control?!](https://gwhitworth.com/blog/2019/10/can-we-please-style-select/)
- [Improving form controls in Microsoft Edge and Chromium](https://blogs.windows.com/msedgedev/2019/10/15/form-controls-microsoft-edge-chromium/)
- [The Current State of Styling Selects in 2019](https://css-tricks.com/the-current-state-of-styling-selects-in-2019/)
- [Dropdown Default Styling](https://css-tricks.com/dropdown-default-styling/)

Just the other week, in fact, Stephanie Stimac from the Open UI Project wrote for Smashing Magazine about [plans to make those problematic form controls more stylable](https://www.smashingmagazine.com/2020/11/standardizing-select-native-html-form-controls/):

> The ultimate goal is to provide developers with a high degree of flexibility over appearance and extensibility of controls

Hopefully we'll see browsers start to allow deeper custom styling of these problematic elements, so that designers can better rely on what the browser provides, and developers can use all of the out-of-the box accessibility benefits and lack of technical debt that using native HTML brings.
