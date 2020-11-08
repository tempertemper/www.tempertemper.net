---
title: Accessibility
intro: |
    Accessibility is intrinsic to everything I work on, whether a design or the HTML, CSS and JavaScript a web page is built with.
order: 2
---

When designing an interface, it's vital that the visuals cater for the broadest possible audience, ensuring:

- colour contrast thresholds are met
- focus states for links, forms and buttons are obvious
- the typefaces used won't prove problematic for dyslexic visitors
- text size isn't too small for some users to read

There are hundreds of considerations that go into designing an accessible interface, and the code is no different. HTML is the core of a user's experience so be sure it is:

- semantic
- reflects the visual order of page elements
- enhanced with ARIA where needed

This means screen reader and voice control software that access a website can interpret the contents correctly, giving their users as good an experience anyone else:

CSS plays a part too; not only should we code in a way that doesn't harm the experience for users of assistive technology, but users that have activated settings like [Dark Mode](/blog/dark-mode-websites-on-macos-mojave) or [reduced motion](/blog/reducing-motion) in their operating system's preferences should be respected.

JavaScript should be an enhancement, where all content is available and accessible where it is blocked or fails to load.

Finally, every interface should be tested manually, with an automated testing tool, and, if possible, with real users.
