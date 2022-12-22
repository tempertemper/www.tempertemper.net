---
title: Images, illustrations, and contrast
intro: ‘Alt’ text is vital for people who can't see an image, but what about those who don't use a screen reader but still struggle with low contrast images?
date: 2022-12-22
tags:
    - Accessibility
    - Design
---

Adding descriptive text to images is a great way to ensure people who can't see the screen understand what the image is communicating. Unsurprisingly there's a success criterion in the Web Content Accessibility Guidelines (WCAG) to ensure we add descriptive text: [1.1.1 Non-text Content](https://www.w3.org/TR/WCAG21/#non-text-content), but what about people with low vision who *can* see the screen but need a decent amount of contrast to distinguish shapes and lines easily?

There are a couple of success criteria in WCAG that cover contrast, if you're aiming for AA compliance:

- [1.4.3 Contrast (Minimum)](https://www.w3.org/TR/WCAG21/#contrast-minimum), which covers text
- [1.4.11 Non-text Contrast](https://www.w3.org/TR/WCAG21/#non-text-contrast), which covers elements that aren't text

Text contrast is straightforward enough, but non-text contrast is trickier. 1.4.11 requires a 3 to 1 contrast ratio for certain non-text elements, including images (which count as "Graphical Objects"), and it talks specifically about:

> Parts of graphics required to understand the content

So an image can contain elements that don't provide enough contrast, as long as without those parts it still conveys the same meaning.


## How to check if an image has enough contrast

We need to understand how an image would look to someone with a visual impairment who needs a decent amount of contrast. To do this we need to remove the parts of the image that don't have enough contrast (that 3 to 1 ratio) with their immediate surroundings.

To identify the bits of an image that might be problematic, [a colour picker tool](https://superhighfives.com/pika) or contrast analyser plugin for your drawing tool (Figma, Sketch, and the rest) comes in handy. Checking contrast can be time consuming at first, but you should soon develop a good feel for low contrast elements; then the tools are just there to check your intuition.

Once we know which bits of the image are too-low contrast, we need to decide if the image still makes sense without them. There are a few ways of doing this:

- For vector images like an illustration, remove/hide the shapes that don't meet at least a 3 to 1 contrast ratio against their immediate surroundings
- For raster images like a photo, use the colour fill tool to make the low contrast bits the same colour as their surroundings; messy but effective
- Use a filter to increase the contrast of the image, usually to as high as it will go, and problematic objects will usually both turn black or white
- Use your imagination (again, this comes with practice)

If the image still makes sense without the bits you've removed, you're in good shape.


## Which images should comply?

Not all images need to provide enough contrast. The rule of thumb here is that if an image has descriptive (`alt`) text, it should meet the Non-text Contrast requirement. Not sure which images should have descriptive text? [I've written an article that should help with that](/blog/which-images-need-descriptive-text)!
