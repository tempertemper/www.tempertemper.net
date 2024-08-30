---
title: An enhancement to accessible responsive tables
intro: I've written about accessible responsive tables before but something has been bugging me. So here's another step to make those tables even better.
date: 2024-08-30
tags:
    - HTML
    - JavaScript
    - Accessibility
related: accessible-responsive-tables
---

I've written about [accessible responsive tables](/blog/accessible-responsive-tables) before, but something has been bugging me. The tables receive focus even when keyboard users don't need them to; this happens when the container is bigger than the table and the table doesn't scroll. As well as adding an unnecessary tab stop for keyboard-only users, the problem here is that it might leave some people wondering *why* the table is focusable.

So I've been thinking about how to make a table focusable *only when it needs to be*.


## Progressive enhancement

What I've got at the moment is a good baseline, and the table wrapper with `tabindex="0"` is in the source code. So what I want is to remove the `tabindex` attribute with JavaScript if the right conditions are met.

If I were to approach it the other way round, starting without the `tabindex` attribute and adding it (together with its value of `0`) with JavaScript, there's an outside chance the script wouldn't run and the tables would be inaccessible. I like [GOV.UK's approach to JavaScript](https://design-system.service.gov.uk/accessibility/accessibility-strategy/#progressive-enhancement).


## Conditions

What are the conditions under which I remove the `tabindex`? If the table is larger that its container, it needs to be able to be scrolled, so `tabindex="0"` is necessary. So I want to remove the attribute when the table is smaller than its container; this is a nice mobile-first approach.

Let's start with the markup from my first article on [accessible responsive tables](/blog/accessible-responsive-tables):

```html
<section class="table-container" tabindex="0" aria-labelledby="caption">
    <table>
        <caption id="caption">The title of the table</caption>
        <!-- Table contents -->
    </table>
</section>
```

We need to do the following:

1. Get the table's container and its width
2. Get the table's width
3. If the table is the same size or smaller than its container, remove the `tabindex` attribute

Here's some super basic JavaScript that does that:

```js
const container = document.querySelector(".table-container");
const containerWidth = container.offsetWidth;
const table = document.querySelector("table");
const tableWidth = table.offsetWidth;
(function() {
  if (tableWidth <= containerWidth) {
    container.removeAttribute("tabindex");
})();
```


## Multiple tables

The code above only works when there's one table on a page, but that's a difficult thing to guarantee; what if there are two, three, or more? What we want to do instead is:

1. List each table on the page
2. Cycle through each table in turn:
    1. Get the table's width
    2. Get the table's container's width
    3. If the table is the same size or smaller than its container, remove the `tabindex` attribute

Here's some JavaScript that would do that:

```js
const tables = document.querySelectorAll("table");
tables.forEach((tableInstance) => {
  const containerWidth = tableInstance.parentElement.offsetWidth;
  const tableWidth = tableInstance.offsetWidth;
  if (tableWidth <= containerWidth) {
    tableInstance.parentElement.removeAttribute("tabindex");
  }
});
```


## Watch for resize

One issue with my code is that the script only runs on page load. This means that if a user were to resize their browser (or rotate their iPad/iPhone 90º) the `tabindex` attribute wouldn't be removed or re-added dynamically, so it would be:

- unnecessarily present for people who resize the browser up from a small, so that the tables are smaller than their container
- missing for people who resize the browser down to a smaller size where the tables are bigger than their container

I'm not bothered about the former scenario as that's no different to how I've had it all these years before I added the script, but I'm less comfortable with the latter as that would leave it inaccessible. Admittedly it's for a pretty rare scenario, where all of the following is true:

- The user is a keyboard-only user
- They resize their browser from large enough to accommodate the table to where the table is larger than its container
- The don't resize it back to where it was before

I'll enhance the code (and update this article) at some point with a resize observer but, until then, I reckon this is a nice bit of progress.
