---
title: The difference between Increased Contrast Mode and Windows High Contrast Mode (Forced Colours Mode)
customURL: the-difference-between-increased-contrast-mode-and-windows-high-contrast-mode
intro: I've written about Increased Contrast Mode and Windows High Contrast Mode, but what's the difference? And where does Forced Colours Mode come in?
date: 2023-03-27
tags:
    - Accessibility
    - CSS
---

A year or two ago I wrote about [Increased Contrast Mode](https://www.tempertemper.net/blog/using-the-increased-contrast-mode-css-media-query). Using `prefers-contrast: more` meant I could meet the Web Content Accessibility Guidelines' (WCAG) [AAA contrast threshold](https://www.w3.org/TR/WCAG21/#contrast-enhanced), rather than just [AA](https://www.w3.org/TR/WCAG21/#contrast-enhanced), for users who configure their operating systems to increase the contrast.

The problem here is that Windows users don't have an 'Increase contrast' toggle in their system settings; instead they have Windows High Contrast Mode (WHCM) which applies a pre-designed theme to the operating system, including web content.

Before talking about WHCM, it's worth recapping that what Increased Contrast Mode looks like is entirely up to the designer/developer; it's just a CSS media query where you can write a bunch of high-contrast override styles:

```css
@media (prefers-contrast: more) {
    /* High contrast styling goes here */
}
```

This relies on the website owner having designed and coded the styles for Increased Contrast Mode and, unfortunately, that's not something that a lot of people either know about or have at the top of their list of priorities.

WHCM, on the other hand, is a <i>Forced Colours Mode</i>, which means it doesn't rely on the website owner; instead it forces the chosen theme onto the website. From Microsoft's [Fluent UI Web Components documentation](https://learn.microsoft.com/en-us/fluent-ui/web-components/design-system/high-contrast), which I also quote in [my last article about WHCM](/blog/windows-high-contrast-mode-and-focus-outlines):

> High contrast mode uses the CSS media feature, `forced-colors`. When `forced-colors` is set to `active`, the user agent will apply a limited color palette to the component.


## Customising WHCM/Forced Colours Mode themes

WHCM, like Increased Contrast Mode, has a [well supported](https://caniuse.com/mdn-css_at-rules_media_forced-colors) CSS media query:

```css
@media (forced-colors: active) {
    /* WHCM styling goes here */
}
```

Unlike Increased Contrast Mode, though, WHCM only allows [a handful of carefully selected things to be styled](https://developer.mozilla.org/en-US/docs/Web/CSS/@media/forced-colors), such as:

- Text colour
- Background colour
- Keyboard focus outline colour

<i>Note: Safari doesn't support the `forced-colors: active` media query because Apple rely on Increased Contrast Mode instead of Forced Colours Mode. I'm not sure if they have any plans to add support, but I'd like to see them join in.</i>


## Why I don't style for Forced Colours Mode

I have a high contrast theme for platforms that support `prefers-contrast` but I decided not to provide a custom Forced Colours theme.

By not defining any styles in a `forced-colors` wrapper, when WHCM is applied it uses the theme the user has chosen. This felt important for a number of reasons:

- A WHCM theme applies to *everything*, so it feels right that websites should look the same as the rest of the operating system, meeting users' expectations
- There are several WHCM themes; some have dark backgrounds and some have light backgrounds, and there's no way to detect whether the user has a light or dark Forced Colours theme applied, in order to ensure consistency
- Although WHCM has 'high contrast' in the name, some people might create their own custom themes for some other reason, maybe:
    - a theme that works for their type of colour blindness
    - a custom Dark Mode that doesn't rely on a browser plug-in
    - a *low contrast* theme that has no harshness
