---
title: Are you sure that table isn't a list?
intro: We often reach for a tables when a list would be much more user friendly, and avoid potential WCAG issues.
date: 2025-05-25
tags:
    - Accessibility
---

I work in a software company that deals with finances, so tables are used a lot. Problem is, because they're so commonplace, they're often the first pattern designers reach for when another might be more appropriate: a list.

The thing about a table is, it's rigid; necessarily so. All that information needs to be exactly where it is in that two-dimensional grid in order for the table to be useful for things like:

- Comparing data
- Finding patterns
- Looking for relationships

But that necessary rigidity comes at a cost; it makes things a bit awkward on small screens.

<i>Note: it's easiest to think of viewport constraints in terms of small screens, but it could also be a larger laptop or desktop computer where the the browser zoom has been increased significantly, causing the content to take up more space within its container.</i>

At screen sizes where the table is wider than the available space, it's going to need to scroll horizontally, which can be an awkward experience.


## 1.4.10 Reflow

First let's see what the rulebook has to say about content that causes scrolling in two directions. The Web Content Accessibility Guidelines (WCAG)'s [Reflow says this](https://www.w3.org/TR/WCAG/#reflow):

> Content can be presented without … requiring scrolling in two dimensions for … Vertical scrolling content at a width equivalent to 320 CSS pixels.

Websites are usually scrollable vertically (up and down), and what this is saying is that on small screens nothing should scroll horizontally as well. So content should wrap and 'reflow' in order to fit whatever screen size it's being viewed on.

It then goes on to say:

> Except for parts of the content which require two-dimensional layout for usage or meaning.

Fair enough; there are going to be some situations where the content can't reflow and still make sense, and it goes on to provide a bunch of specific exceptions in Note 2:

> Examples of content which requires two-dimensional layout are images required for understanding (such as maps and diagrams), video, games, presentations, data tables (not individual cells), and interfaces where it is necessary to keep toolbars in view while manipulating content. It is acceptable to provide two-dimensional scrolling for such parts of the content.

So WCAG is okay with data tables scrolling horizontally. This makes sense, since the point of a table is for the data to be relational and comparative.


## That word 'data'

Notice the wording: not "tables"; "data tables". Let's look at some definitions of 'data'.

[Google](https://www.google.com/search?client=safari&rls=en&q=data+dictionary+definition&ie=UTF-8&oe=UTF-8) describes data as:

> facts and statistics collected together for reference or analysis.

[Miriam-Webster](https://www.merriam-webster.com/dictionary/data) has this to say:

> factual information (such as measurements or statistics) used as a basis for reasoning, discussion, or calculation

[Cambridge Dictionary](https://dictionary.cambridge.org/dictionary/english/data) talks about data in terms of:

> information, especially facts or numbers, collected to be examined and considered and used to help decision-making

So WCAG's not saying you can throw any old content in a table and it's fine; it has to be *data*.


## Using lists instead, sometimes

So if tables aren't always the right pattern to use, how might we use lists instead?

### Description lists

As I mention in [Accessible responsive tables](/blog/accessible-responsive-tables):

> One approach to tables that work on all sorts of screen sizes is to reformat the table on smaller screens. Essentially, you take each table row and repurpose it as a sort of `<dl>`, visually, where each row is presented as a series of keys (each table header) and a values (the value under the header).

I think my main issue with this approach is that it's not [mobile first](https://www.lukew.com/resources/mobile_first.asp). If a `<dl>` ([description list](https://developer.mozilla.org/en-US/docs/Web/HTML/Reference/Elements/dl)) approach is the right approach on small screens, it's probably the right approach on larger screens too.

Description lists offer a key/value structure. One key, one or more values for that key. So if you were listing the instruments each of The Beatles played in their early years, the markup might look like this:

```html
<h1>Instruments The Beatles played in their early years</h1>
<dl>
    <dt>John Lennon</dt>
        <dd>Rhythm guitar</dd>
        <dd>Harmonica</dd>
    <dt>Paul McCartney</dt>
        <dd>Bass guitar</dd>
    <dt>George Harrison</dt>
        <dd>Lead guitar</dd>
    <dt>Ringo Starr</dt>
        <dd>Drums</dd>
</dl>
```

This *could* be marked up as a table, but it works fine as a list, so a list is probably the right call here, since it offers more flexibility.

### Unordered/ordered lists

If you need more complexity in each list item, an unordered or ordered list is worth considering.

Here's a list with facts about The Beatles:

```html
<h1>Beatles facts</h1>
<ul>
    <li>
        <h2>John Lennon</h2>
        <p>John was the founding member of and a lead vocalist in The Beatles.</p>
        <dl>
            <dt>Instruments played</dt>
                <dd>Rhythm guitar</dd>
                <dd>Harmonica</dd>
                <dd>Piano</dd>
            <dt>Place of birth</dt>
                <dd>Liverpool</dd>
            <dt>Date of birth</dt>
                <dd>09/10/1940</dd>
        </dl>
        <p><a href="https://en.wikipedia.org/wiki/John_Lennon">Read more about John on Wikipedia</a>.</p>
    </li>
    <!-- List items for the remaining three Beatles -->
</ul>
```

Again, this *could* be presented as a table, but it's not central that each bit of information is displayed relative to the others.

We can use [CSS Grid](https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_grid_layout) and [Subgrid](https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_grid_layout/Subgrid) to make each bit of data align with its equivalent data where space allows, which kind-of offers a table-ish visual experience, but that layout:

- is not vital to the understanding of the data as a whole
- would be more for visual tidiness


## The question to ask yourself

So, the question you should always ask yourself if you're considering reaching for a table to present your content is: could the content also be presented in a list?

If the answer to this is 'no', then of course a table is the right element to use. If the answer is 'yes', in other words it could be a list *or* a table, then a list is probably the safest, most flexible pattern to reach for.
