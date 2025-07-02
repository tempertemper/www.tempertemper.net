---
title: Hierarchy in tables
intro: Hierarchical tables can be a lot to understand, particularly for screen reader users. Here's how to tackle hierarchy when it can't be avoided.
date: 2025-07-02
tags:
    - Accessibility
    - Development
---

Over the last five years working at an accounting and HR software company, I've seen a lot of [tables that should be lists](/blog/are-you-sure-that-table-isnt-a-list), but there are still a lot of tables that absolutely should be tables.

Like most older (and many newer) software companies, accessibility was never really a consideration, so the way tables were put together was often very dense, with lots of hierarchy and clickable elements. Although they're linked, those things are better tackled individually, so I'll start with hierarchy.


## The rules of play

There are a few things we need to be careful of when introducing parent-child relationships into a table of data. Visually, most of the hierarchical tables I've seen make at least some sense and, where they don't, the 'sell' on making them understandable is more straightforward as it's more of a general user experience problem which is easier to get sign off to tackle. So we're talking about beyond the visual here; specifically screen reader users.

### Info and relationships

The first pitfall is covered by the Web Content Accessibility Guidelines (WCAG), so it's relatively easy to enforce. We need to ensure the information about hierarchical information is conveyed programmatically in order to avoid a failure of [1.3.1 Info and Relationships](https://www.w3.org/TR/WCAG/#info-and-relationships).

### Cognitive load

The other consideration is more difficult to define as it is more about the *user experience of screen reader users*. If more than a simple table of data is being presented, people using screen readers have more information to keep in working memory in order to understand their position in the hierarchy. It's our job not to overface our users.

### An enforced constraint

My first instinct is to simplify, so I'd always explore ways to remove hierarchy from a table altogether, breaking the data down somehow, whether:

- Multiple tables on the same page, separated by headings; the headings would provide the information on hierarchy
- Multiple tables on separate pages, with a link on parent rows to 'drill down' into the next level of the hierarchy

But let's pretend we can't do that, and the hierarchical data *must* be displayed in a single table.


## Conveying information about hierarchy

If I'm looking at a table that contains hierarchy, I should be able to distinguish the child content, so I'd maybe expect it to be indented; a bit like a nested list. I might even highlight the parent content somehow (bold text?), but that's probably less important (remember, our parent rows aren't clickable – that's a separate article for another day).

The challenge is how to convey information about a table's hierarchy programmatically. Focusing on the child content, I want the same kind of information that's there visually via the indent to be communicated to screen reader users. So If I land on the indented first column in a child row, I'd want to hear something like:

1. Contents of current table cell
2. 'Joining' text (something like "child of" or "belonging to")
3. Name of parent cell, to give context

The reverse would also work:

1. Name of parent cell
2. Joining text
3. Contents of current table cell

### ARIA

It's [not the first thing you should reach for](https://www.w3.org/TR/using-aria/#rule1), but ARIA was where my mind went to and (spoiler alert!) none of the approaches work reliably across screen reader software, so I thought I'd get it out of the way.

#### `aria-labelledby`
The [spec doesn't prohibit `aria-labelledby` use on a `<td>`](https://w3c.github.io/aria/#aria-labelledby) so I started there. Here's an example of a leaderboard where both the teams' scores and the team members' scores are logged:

```html
<div id="joiningText" hidden> belonging to </div>
<table>
    <caption>Pokémon league</caption>
    <thead>
        <tr>
            <th>Name</th>
            <th>Battles</th>
            <th>Won</th>
            <th>Lost</th>
            <th>Points</th>
        </tr>
    </thead>
    <tr class="parent">
        <th scope="row" id="rocket">Team Rocket</th>
        <td>24</td>
        <td>0</td>
        <td>24</td>
        <td>0</td>
    </tr>
    <tr class="child">
        <th scope="row" id="jessie" aria-labelledby="jessie joiningText rocket">Jessie</th>
        <td>8</td>
        <td>0</td>
        <td>8</td>
        <td>0</td>
    </tr>
    <!-- More Team Rocket members, then more teams with their members -->
```

`aria-labelledby` allows you to refer to the `id` of the element you're on, so by combining the current cell, the joiner text, and the parent cell I'd expect it to read something like this:

> Row 3 of 12 Name, Jessie belonging to Team Rocket, Column 1 of 5

And it does. But only on VoiceOver and JAWS; not on NVDA. So it's a no-go.

<i>Note: I only tested with these three screen readers as they gave me all the info I needed to make a decision.</i>

#### `aria-label`
I then tried the same sort of labelling with `aria-label` instead:

```html
<th scope="row" id="jessie" aria-label="Jessie belonging to Team Rocket">Jessie</th>
```

Exactly the same results: fine on VoiceOver and JAWS but no dice with NVDA.

#### `aria-describedby`
`aria-describedby` wouldn't change the label, but it would, at least in theory, add the information that you're hearing the data of a child element. So here's the code:

```html
<th scope="row" id="jessie" aria-describedby="joiningText rocket">Jessie</th>
```

There was a problem with VoiceOver as it didn't read the `hidden` joining text before the table, like it did with the `aria-labelledby` approach, but there was an even bigger problem with JAWS and NVDA as it didn't work at all!

### HTML

ARIA wasn't the answer, so perhaps HTML would be?

#### `headers` attribute
The [`headers` attribute](https://developer.mozilla.org/en-US/docs/Web/HTML/Reference/Elements/td#headers) seemed like cleanest way to do this as, similar to the `aria-labelledby` method:

```html
<th scope="row" id="jessie" headers="jessie joiningText rocket">Jessie</th>
```

It *almost* works in VoiceOver but it doesn't read the `hidden` joining text, probably because it's hidden from assistive technology (although that didn't stop `aria-labelledby`), or perhaps because `headers` can only reference `<th>` elements. Either way, there's a gap between the first and third references which doesn't communicate the hierarchy clearly enough:

> Row 3 of 12 Name, and Team Rocket, Jessie, Column 1 of 5

But that's the least of our worries! As with `aria-labelledby`, it doesn't work at all in either JAWS or NVDA. In the bin.

#### Visually hidden text
So far no dice, so there's only one thing for it! Clunky and inelegant but *reliable* visually hidden text. So this would be the HTML:

```html
<th scope="row">Jessie<span class="visually-hidden"> belonging to Team Rocket</span></th>
```

And the CSS we're calling would look like this:

```css
.visually-hidden {
  clip-path: inset(50%);
  height: 1px;
  overflow: hidden;
  position: absolute;
  white-space: nowrap;
  width: 1px;
}
```

As expected, this worked reliably across all the screen readers I tested with.


## Keeping cognitive load light

We have our technique, but the *real* problem with all of this is cognitive load. A table with hierarchy can relatively easy to understand visually, but non-visually there's a lot of information from the markup to keep in [RAM](https://en.wikipedia.org/wiki/Random-access_memory). The user is there to look up or compare data, and *that's* where their energy should be spent; not on working out where in the table they are and how many levels deep.

That brings up another issue: how many levels of hierarchy are too many? There's no hard and fast rule as it very much depends on the content:

- Number of columns
- Number of row
- Complexity of data
- Variety of data types

Introducing any hierarchy at all already feels like it could compromise screen reader users' experience, but anything other than simple data is going to start feeling overwhelming.

If a complex hierarchical table can be broken down into several more digestible tables, that's what should be done. If it's *absolutely unavoidable* to use hierarchy in a table, it should be done with caution.
