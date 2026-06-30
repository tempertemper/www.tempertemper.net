---
title: Clickable table rows are a bad idea
date: 2026-06-30
intro: Whole table rows might feel like obvious candidates to be clickable areas, but making them interactive creates real accessibility problems.
tags:
    - Accessibility
---

I wrote about [hierarchy in tables](/blog/hierarchy-in-tables) a while back, and deliberately avoided talking about clickable table rows; well, now's the time to tackle them!

Clickable table rows can be used for all sorts of things: opening a modal, revealing more rows, or taking the user to a new page. Whatever they do, making the whole row clickable is a problem. To understand why, let's walk through how we might approach it.


## Make the row clickable

The first thing that's likely to come to mind is to attach a click event to the `<tr>`. Now mouse users can click the table row. But how will they know it's clickable?

- A `:hover` state for the whole row, perhaps with a change in `cursor` style
- A visual affordance like a chevron icon; this is important so that we're not reliant on that hover state, which mouse users may miss, and touch users can't know is there

Beyond affordances, what about functionality; specifically right-click behaviour? For me, [adding a right-click menu to a link manually is a hard stop](/blog/if-you-need-a-link-dont-use-a-button#actions) so, in that scenario, my [choose-your-own-adventure](https://en.wikipedia.org/wiki/Fighting_Fantasy) would end there and I'd turn to a page that explored other designs, like placing a link somewhere inside the table row.

If, however, it's a button that triggers an action on-page like a modal, that's not an issue from a menu point of view, so we can continue on our path.


## Make the row actionable with the keyboard

So far this has all been very pointer-centric (mouse, touch), so what about people who use their device in other ways?

[I use the keyboard a fair amount](/blog/designing-and-building-from-our-own-worldview) to fly through webpages using <kbd>⇥</kbd> (Tab), <kbd>Space</kbd> and <kbd>⏎</kbd> (Return/Enter), so the next layer of usability that comes to mind is keyboard.

So let's add `tabindex="0"` to the `<tr>` so that it will be accessible via the <kbd>⇥</kbd> key. We'll need to add a focus state to the row with [`:focus-visible`](/blog/refining-focus-styles-with-focus-visible) so that it's visually obvious when keyboard focus lands on it.

Next we'll use some more JavaScript so that <kbd>⏎</kbd> and <kbd>Space</kbd> will press it.


## Make the row accessible to screen readers

So now keyboard and mouse/trackpad users can interact with our table row, but what about people using screen reader software? They need to know they've landed on something they can activate (a button at this stage, as we've already dismissed table-rows-as-links from our adventure), and this is where things get gnarly.

### Tinkering with the role

We've been custom coding our table-row-as-button up to this point, so let's continue down that route.

Changing the role of the `<tr>` using ARIA (`<tr role="button">`) doesn't do anything untoward visually and maintains the functionality for pointer and keyboard users, but in two of the most popular screen readers it removes the table row from the accessibility tree altogether. This means that VoiceOver and NVDA users get neither the table row's information nor functionality (JAWS users are fine).

### Amending the HTML

How about we replace the `<tr>` with a `<button>` element? After all, the [First Rule of ARIA Use](https://www.w3.org/TR/using-aria/#rule1) is:

> If you *can* use a native HTML element … instead of … adding an ARIA role, state or property … **then do so**.

This is where the beautiful design starts to fall apart. In my testing, the browser removed the `<button>` from the table entirely, leaving an empty button element above the table and a non-interactive table row. Not what we were after.

What about nesting a `<button>` element in the `<tr>`, so that it encompasses all of our `<td>` elements? Same problem: the browser doesn't like it and it moves the button outside of the table and renders the table information as best it can. Same thing when the `<tr>` is contained within a `<button>` element.

This is because the HTML spec is quite strict on [where table rows can be used](https://html.spec.whatwg.org/multipage/tables.html#the-tr-element):

> - As a child of a `thead` element.
> - As a child of a `tbody` element.
> - As a child of a `tfoot` element.
> - As a child of a `table` element, after any `caption`, `colgroup`, and `thead` elements, but only if there are no `tbody` elements that are children of the `table` element.

And, from the same section, table rows' direct children can only be:

> `td`, `th`, and script-supporting elements.

### Labelling

So we can't do anything with the role, but maybe we can add the information that it's clickable to the content of the table row itself? It's probably a bit of a stretch since, although the table row is interactive, the semantics don't marry with the functionality, but let's try to use ARIA for labelling here.

When someone using a screen reader lands on a table row we want them to hear "clickable", or words to that effect, so how about we use `aria-label` to add that to the information they'd usually hear about the table (which row they're on, the contents of the first column's cell, and the header information).

Unfortunately that's out too: the `aria-label` only works when the table row is tabbed onto, which a [non-sighted screen reader user is unlikely to do](/blog/screen-reader-users-and-the-tab-key): they'll listen through the table contents in the usual fashion without using the <kbd>⇥</kbd> key.

### ARIA attributes

My thoughts then drifted to ARIA attributes like `aria-expanded`, which would be appropriate in a good number of cases. Exactly the same issue with the labelling occurs here: the collapsed/expanded information is only exposed when the table row is tabbed onto, not when it is reached in the normal way a screen reader would get there.

### Visually hidden text

So the only thing available to give any programmatic indication that the table row is clickable is, [like we did with table hierarchy](/blog/hierarchy-in-tables#visually-hidden-text), to fall back on HTML and CSS to visually hide some text in the first column's cell.

Here's an example where the first column labels the row, and the rest of the row contains figures for that month:

```html
<tr>
    <td>
        June 2026
        <span class="visually-hidden">, clickable</span>
    </td>
    <!-- The rest of the row's cells -->
</tr>
```

This improves the announcement, but it still doesn’t make the row a real button. It's not a perfect solution by any means; at this point, the user's screen reader is focused on the table cell, not the table row. So there's going to have to be some wrangling with JavaScript to match the table cell behaviour to that of the table row.

Even then, some things can't be done, like the default 'press' behaviour for VoiceOver on Mac (the VoiceOver key + <kbd>Space</kbd>).


## What about the overall experience?

Technical implementation issues aside, there are design issues to consider:

- Is it clear what clicking that table row will do? The 'button' is in danger of not having a label that describes its purpose (which would fail to meet the Web Content Accessibility Guidelines' [2.4.6 Headings and Labels](https://www.w3.org/TR/wcag/#headings-and-labels))
- Speech recognition software users would be unable to target the rows directly as they wouldn't have a clear, visible label; they'd need to use a workaround like Numbers Mode
- What about when a user tries to select some text inside a table cell? If they click some text to highlight it for copying, they'll probably trigger the action on that table row.
- Some users like to click around a page and highlight text as they read it; this is not possible when big blocks of content are contained in buttons
- Some users are more prone to accidental clicks than others, for example people with hand tremors


## So how do we pull this off?

A whole clickable row isn't a good option, so how else might we approach this?

### A clickable first cell

Making the first cell clickable feels like the next-best thing to the whole row, but it leaves almost all of the same issues in place. Changing the semantics gives JAWS users a reasonable time but VoiceOver and NVDA users' experience is broken: the clickable cells are completely bypassed by those screen readers. So we can discount this method.

### A button inside a cell

This can be styled to look like the whole cell is clickable, which is probably what we're after, and it works for all screen readers as you'd expect it to. The other good news here is that:

- The HTML takes care of the semantics, so no ARIA `role`s
- No `tabindex` attribute is needed (the `<button>` does that job automatically)
- JavaScript is only needed to handle what happens once the button is pressed, not to enable any keypresses in the first place
- All of the relevant information is conveyed by screen readers when the user's cursor lands on the table cell

The code might look like this:

```html
<tr>
    <td>
        <button aria-expanded="false">
            June 2026
        </button>
    </td>
    <!-- The rest of the row's cells -->
</tr>
```


## Which cell should contain the button?

Now that we've landed on a solution, the question becomes about how to implement it for the best all-round experience.

As with most things design, it depends, but let's explore the scenario where the button triggers more rows of 'child' content to appear after the current row.

### Proximity

If the button were in the initial column, the screen reader user who pressed it would have to wade through the rest of the table cells in the row before they get to the next, newly displayed row. This separation between triggering the content and reaching it could be problematic.

Moving the button to the final column in the row is more of an unusual pattern for sighted users (regardless of how they access the page), but it brings the trigger and the revealed content closer together for screen reader users.

### Efficiency

The button in the last column probably means that a standalone button is needed, alongside the rest of the row contents. It would read something like 'Expand' or 'Show more', with some visually hidden information on which row it's for:

```html
<tr>
    <!-- The row's first few cells -->
    <td>
        <button aria-expanded="false">
            Show more
            <span class="visually-hidden">
                for June 2026
            </span>
        </button>
    </td>
</tr>
```

If it's in the first column where the 'row header' usually sits, as I showed in the earlier code example, no separate button is needed, nor any visually hidden text.

### A decision

At first this felt like a horrible choice; do we:

1. Give sighted users a better experience at the expense of non-sighted users
2. Give non-sighted users a better experience at the expense of sighted users

But fear not! All screen reader software comes with extra smarts when it comes to traversing tables; VoiceOver users, for example, could just trigger the content and press the VoiceOver key + <kbd>↓</kbd> (down) to move to the next row down.

So I think in this example, housing the button in the first column is:

- More efficient, as the button can surround existing content
- Visually more in keeping with expectations
- Not a huge issue for screen reader users


## And where a link is required

We quickly discounted the table-row-as-link route on our adventure, but you still may need a link in a table row. In this example, the month links to a page with the same figures broken down by day:

```html
<tr>
    <td>
        <a href="june-2026.html">
            June 2026
        </a>
    </td>
    <!-- The rest of the row's cells -->
</tr>
```

As with the `<button>`, we just nest a link in the table cell.


## Keep structure and interaction separate

Clickable table rows sound simple, but making them work well for everyone means fighting the browser and still ending up with a compromised experience.

A table row is for structure, not interaction. Trying to make it behave like a button or link creates problems that are difficult, and sometimes impossible, to overcome. Put the button or link *inside* a table cell instead, so the table stays navigable and the controls stay understandable and operable.
