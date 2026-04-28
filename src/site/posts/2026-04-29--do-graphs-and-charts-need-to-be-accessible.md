---
title: Do graphs and charts need to be accessible?
intro: Charts and graphs are a good example of where making content accessible is not always the same as making an image itself accessible.
date: 2026-04-29
tags:
    - Accessibility
---

Everything should be accessible. Or should it? Charts and graphs are a great example of where you *can* make something accessible, but maybe you shouldn't. Bear with me here.

Pie charts, line graphs, bar and column charts (let's just call them all 'charts' for ease) are really useful, visual ways to present data, and we'd include one on a webpage using an image, like a PNG or SVG.


## Accessible PNGs

I say 'PNGs', but these could be any other image format, like WebP, AVIF, or even JPG. The mechanism we'd use to embed them on our page would be the `<img />` element:

```html
<img src="my-great-chart.png" alt="A description of the information the chart conveys" />
```

Depending on the complexity of the chart, our `alt` text could be doing a lot of work here. In principle, that's fine, as the `alt` attribute can contain as many characters as you need, but the problem is that there's no way of introducing any meaning to the content in `alt` text; it's just a wall of unstructured text.

Here's an example with some made-up figures about people's favourite characters from the original Star Wars trilogy:

```html
<img src="star-wars-favourites.png" alt="Pie chart showing that 22% chose Han Solo, 18% chose Darth Vader, 15% chose Princess Leia, 12% chose Chewbacca, 9% chose Luke Skywalker, 7% chose Yoda, 6% chose Obi-Wan Kenobi, 4% chose Lando Calrissian, 3% chose R2-D2, 2% chose C-3PO, and 2% chose other characters." />
```

That's a fairly typical amount of information for a chart, and it takes a fair amount of concentration to make sense of, especially with so much repetitive language. Imagine more complex charts, like a [stacked column chart](https://en.wikipedia.org/wiki/Bar_chart#Grouped_(clustered)_and_stacked) or a [best-fit line chart](https://en.wikipedia.org/wiki/Line_chart#Best-fit).

### Before you mention `longdesc`…

I know what you're thinking: there's the `longdesc` attribute, which allows us to reference a structured HTML document:

```html
<img src="star-wars-favourites.png" longdesc="star-wars-favourites-description.html" />
```

Although browser support still appears to be there, [`longdesc` is long deprecated](https://developer.mozilla.org/en-US/docs/Web/API/HTMLImageElement/longDesc) so definitely not recommended.


## Accessible SVGs

So if `alt` text isn't ideal, and `longdesc` is out, maybe a more complex image format like SVG is the answer?

We could reference an SVG file just as we did with the PNG in our last example, but [for inline SVGs](https://cariefisher.com/a11y-svg-alts/) the result is exactly the same; a big lump of unstructured descriptive text:

```html
<svg role="img" aria-labelledby="title description" xmlns="http://www.w3.org/2000/svg">
    <title id="title">Pie chart showing people's favourite characters from the original Star Wars trilogy</title>
    <desc id="description">22% chose Han Solo, 18% chose Darth Vader, 15% chose Princess Leia, 12% chose Chewbacca, 9% chose Luke Skywalker, 7% chose Yoda, 6% chose Obi-Wan Kenobi, 4% chose Lando Calrissian, 3% chose R2-D2, 2% chose C-3PO, and 2% chose other characters.</desc>
    <!-- SVG coordinates -->
</svg>
```


### ARIA in SVG

But we've been ignoring SVG's superpowers! SVG is markup, so ARIA can be used to add semantics to the elements that make it up.

In [Accessible SVG line graphs](https://tink.uk/accessible-svg-line-graphs/), Léonie Watson goes into detail about how we can make purposeful use of SVG's group (`<g>`) element and various ARIA role attributes to give the chart table semantics. A chart is a visual representation of tabular data, after all!

For our Star Wars pie chart, we could use:

- `role="table"` for the containing group
- `role="row"` for each character
- `role="columnheader"` to label each column of information (character name, and percentage of votes)
- `role="rowheader"` for each character name
- `role="cell"` for each percentage value

Visually a chart; semantically a table.


## One thing visually, another non-visually

I love the ARIA in SVG approach, but it’s fiddly enough to make me wonder if there’s a simpler approach. We could separate the two things:

- An SVG chart that's hidden from assistive technologies with `role="presentation"` or `aria-hidden="true"`
- An HTML table that's [hidden visually with CSS, but still available to screen reader users](/blog/hierarchy-in-tables#visually-hidden-text)

This way, sighted users would see the chart and screen reader users would get the data in an HTML table. That would work, but it still assumes the table is only useful to screen reader users.


## Just show the underlying data

Maybe we should stop treating the table as an accessibility-only fallback? I reckon it could be even better to present both the visualisation and the underlying data. After all, some people might prefer rows and columns to a chart.

The way I'd probably approach this is to ensure the chart doesn't get picked up by assistive technology like screen reader software at all, as we did before:

```html
<svg role="presentation" xmlns="http://www.w3.org/2000/svg">
    <!-- SVG contents -->
</svg>
```

And then our table:

```html
<table>
    <caption>Favourite characters from the original Star Wars trilogy</caption>
    <tr>
        <th>Character</th>
        <th>Percentage of votes</th>
    </tr>
    <tr>
        <td>Han Solo</td>
        <td>22%</td>
    </tr>
    <tr>
        <td>Darth Vader</td>
        <td>18%</td>
    </tr>
    <!-- The rest of the characters -->
</table>
```

If your table has a lot of data, you could save some unnecessary scrolling for users who prefer the chart by wrapping it in a `<details>`/`<summary>` element.

<i>Note: it's totally valid to expose the chart to screen reader users if the visual presentation is relevant; just make sure the alternative text describes the visual presentation, not the data that's already available in the table.</i>


## So what to do?

For screen reader users, a simple chart with the data in the `alt` text might be okay, but more often than not we probably want to present the data in tabular format, which is easier to navigate, compare, and understand. Going one step further by accompanying the chart with a table is an even more robust approach as it also allows sighted users to choose how best to absorb the information.

So do charts and graphs need to be accessible? Yes and no. The chart image itself doesn't always need to be exposed to assistive technologies, but the information it communicates must be accessible.
