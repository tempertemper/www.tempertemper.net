---
title: HTML-only Dark Mode
intro: |
    When our CSS contains Dark Mode rules and the file fails to load, we're left with plain old HTML. Luckily we can request Dark Mode in our markup!
date: 2021-06-02
updated: 2021-06-03
tags:
    - CSS
    - HTML
    - Accessibility
summaryImage: large
---

Progressive enhancement is the most resilient way to design and build for the web. Lots of things can go wrong:

- Older browsers may not support features like `<details>` and `<summary>` elements
- Cutting edge features like [`focus-visible` might not be understood](/blog/refining-focus-styles-with-focus-visible) by the user's browser
- The user's system settings might [disallow things like animation](/blog/progressively-enhanced-animated-content)
- Referenced files like images, JavaScript and CSS can fail to load

We need a solid starting point before all of these things are added as an enhancement, but let's take a look at that final bullet: what if the user receives our HTML but no CSS?

CSS is a progressive enhancement, but our stylesheets can contain instructions that make for a more accessible experience; things like the `prefers-color-scheme` media query for Dark Mode.

With Dark Mode, we're respecting our users' system preferences; their eyes have settle into a that dark UI with light text and as they browse the web, the last thing they want is to be confronted with an obnoxious bright white page.

So what happens when the CSS file containing all the Dark Mode styling fails to load?

You could add Dark Mode styles to a `<style>` block in the head of each document, but:

- there can be a lot of Dark Mode rules
- that would delay the rendering of the rest of the page
- our users wouldn't benefit from caching, so it would slow every page down slightly
- those rules would be heavier (in terms of their specificity) compared to those defined by the user agent

You could add a handful of rules in that `<style>` element to cater for the most basic of dark themes, then override or add to them in the CSS file. But that's more work, as well as being a probable maintenance problem when the developer forgets they're there.

If only there were some way to tell the browser to use a dark version without CSS…

Imagine my excitement when I found there's a way to do it in your document's `<head>` with HTML!

```html
<meta name="color-scheme" content="dark light">
```

Browser support is currently limited to [Safari and Chromium-based browsers](https://caniuse.com/mdn-html_elements_meta_name_color-scheme) (Chrome, Opera, Edge, etc.), but that's currently 84% coverage, so it's definitely worth adding!

Just one issue before you go ahead: there's a bug in Safari that makes links less accessible, but thankfully [there's a work-around](/blog/fixing-safaris-html-only-dark-mode-bug) to fix it.
