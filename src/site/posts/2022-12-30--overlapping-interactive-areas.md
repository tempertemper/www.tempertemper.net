---
title: Overlapping interactive areas
intro: When an interactive element like a button, link, and form field sits on top of another interactive element, accessibility (and usability) problems arise.
date: 2022-12-30
tags:
    - Accessibility
    - Design
    - Development
summaryImage: spider-man.jpg
summaryImageAlt: Cliff Robertson playing Uncle Ben in the 2002 Spider-Man movie, saying “With great power comes great responsibility” to Peter Parker.
---

When an interactive element like a button, link, and form field sits on top of another interactive element, accessibility problems arise.

Let's begin by taking a look at the specifications (specs), starting with [HTML's `<button>`](https://html.spec.whatwg.org/multipage/form-elements.html#the-button-element), which says:

> there must be no interactive content descendant and no descendant with the `tabindex` attribute specified.

The same is true of the [link (`<a>` element)](https://html.spec.whatwg.org/multipage/text-level-semantics.html#the-a-element) (which, additionally, prohibits using [`href`-less `<a>` elements](/blog/links-missing-href-attributes-and-over-engineered-code) as descendants).

As for form fields, [self-closing elements](/blog/self-closing-elements-in-html) like `<input>` can't have child content, so there's no issue there. Other form elements like `<select>` and `<textarea>` tightly control what child elements are allowed ([`<option>` and `<optgroup>`](https://html.spec.whatwg.org/multipage/form-elements.html#the-select-element), and just [plain-old text](https://html.spec.whatwg.org/multipage/form-elements.html#the-textarea-element), respectively).

There can be other things in our markup that are still interactive and aren't covered by the above specs. Tabs are a good example, and these are included in [the ARIA spec](https://w3c.github.io/aria/#tab), which says that elements with `role="tab"` can only have presentational child elements; in other words, nothing interactive.

So whether links, buttons, tabs, or something else, this kind of pattern is a no-no:

```html
<button class="button1">
    Press me!
    <button class="button2">
        Press me too!
    </button>
</button>
```


## If the spec says 'no', we should listen

The details of the HTML and ARIA specifications are carefully thought through over the course of months and years, so [if they prohibit interactive children in certain elements we should listen](/blog/if-html-and-aria-dont-allow-it-its-probably-a-bad-idea).

It makes sense: if something interactive has an interactive descendent, the interactive areas will overlap. I can't think of any physical button I've pressed in the real world that sat directly on top of another, bigger button. The broad-strokes issues would be:

- tricky to know there was more than one button; what kind of affordances could be used?
- problematic when people accidentally press the larger surrounding button, due to:
    - missing the smaller, internal button (this could be even more common when someone has a motor impairment like a hand tremor)
    - simply by not realising it's a button at all!

So if HTML and ARIA tell us not to nest interactive elements inside interactive elements, nobody's going to do it, right? Unfortunately:

- not all developers validate their code
- not all developers know the specs
- there's a way to make the code compliant but still have overlapping interactive elements


## Breaking the rules without breaking the rules

CSS makes it pretty trivial to have spec-compliant markup but also position interactive items *as if* the were nested in the markup. Here's some perfectly valid HTML:

```html
<div class="button-container">
    <button class="button1">Press me!</button>
    <button class="button2">Press me too!</button>
</div>
```

And here's some naughty CSS that makes `button1` big, and positions `button2` on top of it, in the top-right corner:

```css
.button-container {
  position: relative;
  height: 4em;
  width: 20em;
}
.button1 {  /* test comment */
  width: 100%;
  height: 100%;
}
.button2 {
  position: absolute;
  right: 1em;
  top: 1em;
}
```

Don't do this. As Peter Parker's Uncle Ben tells him in [2002's Spider-Man](https://www.imdb.com/title/tt0145487/):

> Just because you can beat him up, doesn't give you the right to. Remember: with great power comes great responsibility.

If we produce designs with overlapping interactive elements, we're giving our users a hard time. We've got the tools to do it and remain compliant with the specs, but we really, really shouldn't.
