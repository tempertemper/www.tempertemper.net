---
title: Using iframes to embed arbitrary content is probably a bad idea
intro: The iframe element is a way to embed one website inside of another. Useful for things like maps or videos, but not so much for other content.
date: 2024-12-29
tags:
    - Accessibility
---

The `<iframe>` element is a way to [embed one web page inside of another](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/iframe). They're often used to deliver a video, [working code example](https://design-system.service.gov.uk/components/text-input/), or video as part of other page content, which is pretty sensible, but I've seen them being suggested as a way to embed general text-based page content, which is problematic.


## General fixed-height weirdness

First up, unless the contents of our iframe makes sense as a fixed canvas (or a canvas with fixed proportions), things are going to get a bit weird for the user. We've no idea how much room the content in the iframe will need and therefore no idea how big the iframe should be.

Setting `height` and `width` attributes on our iframe when embedding a map makes sense as we've configured how the map should be displayed and know how it will appear on our site. For arbitrary page content, however, we don't know how much space will be taken up.

Since we need to set a height on our container (which will also avoid [Cumulative Layout Shift](https://web.dev/articles/cls)), we're unlikely to get the dimensions exactly right:

- If the iframe is too big for the content there will be empty space between the end of the content and the end of the iframe
- If, more likely, the iframe is smaller than the content presented inside of it, it will have to scroll independently of the parent page


## Keyboard users

### Tabbing inconsistencies

The keyboard behaviour of iframes is slightly inconsistent from browser to browser. Most browsers don't include iframes in the page's tab index, which makes a pretty seamless experience for keyboard users who would tab from the last interactive element on the parent page before the iframe, to the first interactive element in the iframe.

But Apple's Safari, when [full keyboard access is turned on](/blog/how-to-use-the-keyboard-to-navigate-on-safari), includes the iframe in the tab index *the first time* it is encountered. Subsequent attempts to tab onto the iframe won't work and it will behave just like Firefox, Chrome, and Chromium-based browsers.

That's something we'll have to live with as removing the tab stop with `tabindex="-1"` causes the browser ignore the iframe and everything inside it, which you really wouldn't want to do as it would make the iframe completely inaccessible for keyboard-only users.

Evening things up by adding `tabindex="0"` to the iframe wouldn't be a great idea as it would add an unexpected tab stop for keyboard users who wouldn't normally expect one. But the good (?) news is that adding `tabindex="0"` to an `<iframe>` element does nothing at all to the default behaviour, regardless of browser.

### Scrolling

Aside from it being an odd experience, scrolling the content inside an iframe could present a real accessibility issue for keyboard-only users if there are no interactive elements in the embedded content. If the user can't tab onto an interactive element in the iframe they can't enter the embedded document, and if they can enter the document they can't scroll it.

So there's some logic to Safari's decision to add iframes to the page's tab index as it means embedded content can always be scrolled, at least the first time it receives focus.


## Screen reader users

iframes need to have an accessible name (which is done via the `title=""` attribute) in order to satisfy [4.1.2 Name, Role, Value](https://www.w3.org/TR/WCAG/#name-role-value) in the Web Content Accessibility Guidelines (WCAG), since an iframe counts as a 'user interface component'.

That way, the accessible name of each iframe would be announced to screen reader users before they reached the content in the embedded document. For something like a code example, that makes perfect sense, but for general page content it would:

- potentially confuse screen reader users who wouldn't expect general page content to be brought in via iframes
- create extra noise for screen reader users
- cause extra work for authors, who would have to design a sensible descriptive name for the iframe

What's more, iframes aren’t navigated like normal content by all screen reader software. To use Apple's VoiceOver for macOS as an example, when you reach an iframe you can't just press on through the content with the VoiceOver key (usually <kbd>⌃</kbd> (Control) + <kbd>⌥</kbd> (Option)) and <kbd>→</kbd> (right); instead you're stopped at the iframe itself and have to move 'into' it using the VoiceOver key, <kbd>⇧</kbd> (Shift) + <kbd>↓</kbd> (down). Clunky.


## Control

Aside from all of the usability issues with iframes, the biggest issue for me is one of control. The embedded page within your control? It might belong to another department within your organisation or a different organisation entirely.

### Responsiveness

If that organisation is YouTube and the content you're embedding is a video from their platform, you can be reasonably confident that they'll do their best to ensure the content they serve will always match the `height` and `width` attributes they set on the embed code snippet they provided. It's in their interests to ensure their content is nicely presented out there on the web, so the video will scale up and down nicely as the iframe changes width across various screen sizes.

But if the content you have embedded changes without the kind of rigorous testing that YouTube are sure to carry out, or a bug is introduced that affects its responsiveness, you risk falling short WCAG's [1.4.10 Reflow](https://www.w3.org/TR/WCAG/#reflow) and [1.4.4 Resize Text](https://www.w3.org/TR/WCAG/#resize-text) as the content in the iframe becomes inaccessible for some people.

### Bug fixes and maintenance

Even if you've got control of the embedded document, that's two websites to maintain, rather than just one. And if you don't have full control of the embedded document, you're at the mercy of the people who are: their project priorities may not be the same as yours, and accessibility might be further down on their list, so you could be waiting a long time for any bugs you raise to be ironed out.

### Testing

Quality assurance testing can get trickier too, as [handy bookmarklets](https://codepen.io/stevef/full/YLMqbo) don't affect content inside of iframes, and more complex browser-based tools like [axe DevTools don't support testing of documents in iframes](https://dequeuniversity.com/rules/axe/4.2/frame-tested).


## To sum up

The working title for this post was "iframes? More like cryframes, amirite!?". In case it isn't already clear what my advice would be for using iframes to embed arbitrary web content inside other documents: don't.
