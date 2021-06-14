---
title: The best Sublime Text theme
intro: |
    Operator Mono's fancy italics don't work with every colour scheme, but finding one that does has lead me to my favourite Sublime Text theme.
date: 2019-11-18
tags:
    - Development
    - Design
summaryImage: monokai-pro.jpg
summaryImageAlt: The Monokai Pro logo on a dark background, consisting of a hexagon with an chunky, angular, orange letter ‘M’ inside.
---

I've recently [started using Operator Mono](/blog/operator-mono-and-why-i-want-italics-in-my-code-editor) as my coding font and was frustrated to learn that I couldn't use it with any old colour scheme -- it has to be one that supports italics.

So the hunt was on to find a colour scheme that felt comfortable and also supported italics. I tried a whole bunch and the colour scheme I landed on was one that felt a bit nostalgic: [Monokai Pro](https://monokai.pro). Monokai is the default font on Sublime Text and was *everywhere* back in 2011, when it seemed like the whole world moved to Sublime.

Monokai Pro takes Monokai and adds italics *and* a bunch of different colour filters:

- Purple (the default theme)
- Blue ('Octagon')
- Browny-red ('Ristretto')
- Grey ('Spectrum')
- Green ('Machine')
- Yellow ('Classic')

They all look great, but Spectrum feels most in keeping with the rest of macOS so it's my favourite.


## Colour scheme does not necessarily equal theme

Sublime Text is still my text editor of choice and the colour scheme only affects the main code editor window. The rest of the user interface (sidebar, status bar, tabs, etc.) is covered by a *theme*.

I've used [One Dark theme](https://packagecontrol.io/packages/Theme%20-%20One%20Dark), [Spacegrey](https://github.com/kkga/spacegray) and [Spaceblack](https://github.com/TheBaronHimself/Spaceblack), but a few years ago I settled on [Ayu Mirage](https://packagecontrol.io/packages/ayu). But since macOS Mojave landed with Dark Mode, that blue tinge of Ayu hasn't felt *just right*.

Like Goldilocks, I'm a bit of a fuss-pot, so aside from italics support, there are a few things I want from a theme:

- A dark grey background without much in the way of colour -- I want it to feel like my other Dark Mode apps on macOS
- Icons in the sidebar/file-tree make it feel too busy, so if the theme uses these I want to be able to turn them off
- Arrows ([preferably chevrons](/blog/which-way-is-that-arrow-pointing)) instead of folder icons to represent folders
- Control over the sidebar spacing, font size and ideally the font itself
- No old-Chrome style sloping tabs -- they always felt out of place on Chrome, never mind being aped by other apps


## The contenders

I still really like Ayu, I just want a grey variant. And the option to turn sidebar icons off. And arrows instead of folder icons in the sidebar…

[Brogrammer](https://packagecontrol.io/packages/Theme%20-%20Brogrammer) sounds awful but is actually quite a nice setup. But there are no options to turn off those sidebar file icons, the tabs take up too much space and the red and white highlight colours are too in-your-face.

[Predawn](https://packagecontrol.io/packages/Predawn) *nearly* made it as it's more customisable, but an option to make that weird peach colour go away would be lovely.


## The winner

I should read documentation more. As I've said, I like Monokai Pro a lot, but I totally missed that it was a *theme*, not just a colour scheme! It turns out it's quite a theme, too:

- The colour filters listed above carry through to the whole UI -- very cohesive!
- You can choose the sidebar font, its size and spacing
- You can turn off icons in the sidebar
- The Spectrum theme feels very in keeping with the rest of my OS in Dark Mode
- It has triangles rather than chevrons to indicate folders, but I can forgive it that

In case you're interested, here are the settings I'm using:

```json
{
  "font_face": "Operator Mono Light",
  "monokai_pro_minimal": false,
  "monokai_pro_file_icons": false,
  "monokai_pro_file_icons_monochrome": true,
  "monokai_pro_highlight_open_folders": false,
  "monokai_pro_sidebar_row_padding": 2,
  "monokai_pro_sidebar_font_size": 14,
  "monokai_pro_label_font_size": 12,
  "monokai_pro_sidebar_lighter": true,
  "monokai_pro_small_scrollbar": true,
  "monokai_pro_ui_font_face": "SF Compact Text Regular",
  "monokai_pro_style_title_bar": true,
  "monokai_pro_panel_font_size": "16",
  "monokai_pro_sidebar_headings": false
}
```
