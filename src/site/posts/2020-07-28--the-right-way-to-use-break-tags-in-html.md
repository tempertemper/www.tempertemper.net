---
title: The right way to use break tags in HTML
intro: |
    Break tags are often misused. I'll demo some markup patterns to avoid them, and reveal the one and only legitimate use case I can think of.
date: 2020-07-28
tags:
    - Development
    - HTML
---

They're [technically an element](/blog/the-difference-between-elements-and-tags-in-html), but everyone knows them as break *tags*. Break tags are [self closing elements](/blog/self-closing-elements-in-html) and look like this: `<br />`.

Opinion time: there's only one thing we should be using `<br />`s for. But before I get there, here are a few things you *shouldn't* be using them for and the markup we should be using instead.


## The wrong way to use break tags

I've seen break tags used for all sorts of things, some I can totally understand, others make me wince. It boils down to two things:

- Creating line breaks
- Creating visual space

### Creating line breaks

That's right: I'm about to tell you not to use an element that's *specifically for creating line breaks* to create line breaks. I have reasons though -- hear me out!

A good example is an address. Typically, a contact address is displayed over multiple lines, so you'd be forgiven for thinking a series of break tags feels like a sensible markup pattern to reach for.

#### Styling can change
First of all, though, think about where you'd break an address. Here's an example using break tags:

```html
<address>
  123 High Street<br />
  Newcastle upon Tyne<br />
  NE1 4UR<br />
  United Kingdom
<address/>
```

This will work well both visually and for screen readers; just as a sighted user sees each clear new line, a screen reader will stop reading when it reaches each `<br />`, requiring the user to move forward to the next line manually.

However, if you wanted to condense it a bit visually, by having, say, the city and postcode on the same line, you'd have to edit the markup:

```html
<address>
  123 High Street<br />
  Newcastle upon Tyne, NE1 4UR<br />
  United Kingdom
<address/>
```

Don't forget to do that in every place or every template where the address appears. This can be error prone and is exactly what the sort of situation the best practice of separation of styling and content is there to help us avoid.

Wrapping each line in a non-semantic `<div>` or `<span>` gives us a lot more flexibility:

```html
<address>
  <span class="street">123 High Street</span>
  <span class="city">Newcastle upon Tyne</span>
  <span class="postcode">NE1 4UR</span>
  <span class="country">United Kingdom</span>
<address/>
```

I've added some semantic/descriptive classes here, but you don't need to use them to give each item its own line:

```css
address span {
  display: block;
}
```

This causes screen readers to read each line and wait for the user to proceed to the next one manually; exactly as it would with break tags.

But what about when it comes to condensing the address as we did before? Well, we could zero in each line with `:nth-of-type` but that feels a bit flakey and we'd need to start commenting our CSS to be clear on what we're styling with each `:nth-of-type` pseudo class.

Those classes I added can be used to target the specific items we need, to make the street and country full-width, but the city and postcode inline:

```css
.street,
.country {
  display: block;
}
```

The nice thing about this is that screen readers like VoiceOver pause slightly when two `<span>`s have whitespace in between (a new line or just a simple space), so the address reads out nicely. If you want to add punctuation to make the divisions between address 'parts' clearer visually, you might do this:

```css
address span::after {
  content: ",";
}

address span:last-of-type::after {
  content: "";
}
```

#### More information can be added
Another advantage of wrapping each item in an address in its own element is that we can add [microdata](https://schema.org/PostalAddress). Microdata provides more granular, detailed information to machines like search engines, which might want to display the address on their results page and would benefit from knowing what each bit of the address *actually is*:

```html
<address itemprop="address">
  <span itemprop="streetAddress" class="street">123 High Street</span>
  <span itemprop="addressLocality" class="city">Newcastle upon Tyne</span>
  <span itemprop="postalCode" class="postcode">NE1 4UR</span>
  <span itemprop="addressCountry" class="country">United Kingdom</span>
<address/>
```

### Creating visual space

This is the big no-no.

A break tag creates a line break, so the content after the `<br />` would appear on a new line. Therefore, using a second (or third, etc.) `<br />` would create some clear space between the content before and after the series of break tags.

```html
<p>
  This is a paragraph of text.<br />
  <br />
  This text is separated from the text above by a single line space.
</p>
```

But this content would really be better marked up as two paragraphs:

```html
<p>This is a paragraph of text.</p>
<p>This text is separated from the text above by a single line space.</p>
```

Now it's two paragraphs visually *and* semantically, but it also stops that conflation of content and styling we encountered with addresses, allowing for easy styling of paragraphs with CSS. For example, to adjust the space between paragraphs, we'd do this:

```css
p + p {
  margin-top: 1em;
}
```

To do the same thing with break tags is a world of hurt…

You could do it with `margin-top`, but `line-height` feels instinctively more appropriate as we want to control the height of the element itself, rather than add margin to something else to create the space.

As I alluded to earlier, break tags are not your typical `display: block;` style element. Unlike a `<div>`, for example, they don't take up the whole line, pushing surrounding content above and below them. Instead, they force content that appears *after* them underneath. The `<br />` actually sits at the end of the line it's on, so would be counted alongside "This is a paragraph of text.", in the first example above (which is why I added the break tag to the end of the line, rather than on its own line). The second break tag does the same thing but sits on the end of a line with no content, so *it* creates the gap.

Increasing the `line-height` of the `<br />`s would force the `line-height` of of the text it sits on the end of to increase too; the second `<br />` would live on its own line and would have the same `line-height` as the first line of text. Then the second line of text doesn't have a break tag on the end of it, so it would have a *different* `line-height`: whatever `<p>` elements have been styled with.

Did that make sense? Don't worry if not -- it just illustrates that it's not something you want to be getting yourself into!


## The right way to use break tags

I've racked my brain and can only think of one good use of break tags, and that's in <b>poetry</b> or <b>song lyrics</b>, where:

- there's a meaningful line break
- no extra meta-information about each line would be needed

The visual line break without a space is how we're used to reading poetry on a page, and the stop a screen reader will come to at the end of each line is exactly what we'd want, to allow the reader to digest what they've just heard before they move to the next line.

Here's an example, using an excerpt from The End by The Beatles:

```html
<p>
  And in the end<br />
  The love you take<br />
  Is equal to the love you make
</p>
```

And, finally, I enjoy being proved wrong; if you can think of another use case where break tags *should* be used, [let me know on Twitter](https://twitter.com/tempertemper)!
