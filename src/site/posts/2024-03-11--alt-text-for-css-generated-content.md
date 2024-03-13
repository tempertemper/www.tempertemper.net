---
title: Alt text for CSS generated content
intro: There's an interesting feature in Safari 17.4 that allows content added with CSS to have 'alt' text. I'm not sure how I feel about this.
date: 2024-03-11
tags:
    - Accessibility
    - Development
    - CSS
---

There's an interesting feature in [Safari 17.4](https://webkit.org/blog/15063/webkit-features-in-safari-17-4), released last week. It now supports ['alt' text for content added with CSS](https://caniuse.com/mdn-css_properties_content_alt_text) (via the `::before` pseudo element and the `content` property). I'm not sure how I feel about this.

On the one hand it's great because it gives developers more control over their output but, on the other hand, I question why content is able to be added to the page with CSS at all; isn't that HTML's job!?


## Separation of concerns

As [Dennis Pintilie Alexandru writes](https://medium.com/@dennis.pintilie.alexandru/separation-of-concerns-soc-fd72b0191b1f#) about HTML, CSS, and JavaScript:

> each language has it’s different sections or concerns of a website … HTML … is used for the content and structure of the website … CSS … is used for the styling of the website … JavaScript … is used to define the behavior.

In fact, Tim Berners-Lee, inventor of the HTML markup language, [always intended this to be the case](https://www.w3.org/Style/CSS20/history.html):

> The separation of document structure from the document's layout had been a goal of HTML from its inception in 1990.

I know CSS can have a pretty big impact on accessibility; to name just a few examples:

- the `display` property
- use of [`list-style: none;`](https://www.tempertemper.net/blog/accessibility-issues-when-removing-list-markers)
- horribly low contrast between text and its background

But adding *content* to the page with CSS is a step to far for me.

### Blurring the boundaries

You see, anything added to the page with `content` is accessible, and this is the issue addressed in this WebKit release:

> perhaps we want to prefix certain links with the little ⓘ icon to let users know this item leads to more detailed information. That symbol might be read by screenreader as “Circled Latin Small Letter I” or “Information source combining enclosing circle”, neither of which do a good job communicating the intended purpose. Perhaps a better experience would be to simply hear “Info:”

The CSS example they give is:

```css
.info::before {
  content: "ⓘ" / "Info:";
}
```

This would target any HTML with the `info` class, like this:

```html
My favourite small wild cat is the <a class="info" href="https://en.wikipedia.org/wiki/Pallas%27s_cat">manul</a>.
```

Which would leave the document looking something equivalent to:

```html
My favourite small wild cat is the <a class="info" href="https://en.wikipedia.org/wiki/Pallas%27s_cat">Info manul</a>.
```

And the browser builds an element that has the accessible properties of:

<dl>
    <dt>Name</dt>
        <dd>Info manul</dd>
    <dt>Role</dt>
        <dd>Link</dd>
</dl>


## Speech recognition software users

So we've got a disconnect between the visual and the accessible name, which is a similar issue to using [icon-only buttons](https://www.tempertemper.net/blog/what-i-wish-was-in-wcag-prohibit-icon-only-buttons).

In our 'favourite small cat' example the visible label's text reads "manul", meaning speech recognition software users would, sensibly, say the command "Click Link label". Unfortunately, what they *need* to say is "Click Info Link label" since the word 'Info' has been added to the underlying accessible name via the CSS.

This leaves the user with a few options:

- Try to guess what the accessible name is
- Use a 'mouse grid'
- Show numbers next to all interactive items on the page

These are all work-arounds that give our users a less than straightforward experience.


## What if CSS doesn't load?

It's an edge case, but possible, that the CSS fails to load; leaving the user with a [style-free page](/blog/css-naked-day). The good news is that this would remove the label/name mismatch issue for speech recognition software but the bad news here would be if content added via the CSS `content` property was integral to the user's understanding.


## No semantics

The output of anything entered in the `content` is limited to text. The example from WebKit is relatively safe from a semantics point of view as there's no markup in there, but it's worth mentioning that even if you add HTML in there, it'll be output as text. So you might try to do the right thing by hiding the accessible output like this:

```css
.info::before {
  content: 'ⓘ' / '<span class="aria-hidden"> Info:</span>';
}
```

But that will be rendered plan text rather than HTML, so the accessible name will be `<span class="aria-hidden"> Info:</span>` rather than nothing, as we might have hoped by using that code.

For the record, this is the right behaviour in my opinion, but it doesn't change that adding content to the page via CSS in the first place is problematic.


## Time travel

Of course all of that is just a whinge. Adding the alt text to `content` is the right thing to do since:

- the output of `content` is already accessible
- backwards-compatibility is a [central design principle of CSS](https://www.w3.org/TR/CSS22/intro.html#design-principles).

The *real* fix for this would be to go back in time, Terminator style, and tear up the proposal for `content` to allow anything other than decoration. Of course, that would come with its own downsides, but that's another post in an alternative timeline.


## So what do we do?

If you're designing and coding responsibly, you should only be using `content` to add decorative elements to the page, meaning you'll probably never need the `content` property's 'alt' text functionality.
