---
title: De-prioritising design
intro: |
    When a friend asked for some CSS tips for a website he works on, I noticed a design issue that actually highlights a common problem in our industry.
date: 2020-04-08
tags:
    - Design
---

A good friend of mine looks after the website for a company that sells parts for machinery. Thousands of parts.

He came to me asking for some CSS help with the layout in a mega-menu, which I helped out with, but---me being me---I couldn't stop myself pointing out some issues with the design of the mega-menu itself…

The menu was made up of two parts:

1. The first column, which was a vertically stacked list of brand names
2. A further three columns to the right which were groups of product parts, comprising a heading with a list underneath

So the idea was that you'd choose your brand from the list on the left and the parts to the side would change according to what they stocked from the selected brand.

The problem was that it all looked the same!

When you opened the menu, the first item in column 1 was selected by default, and the `selected` styling was *exactly the same as* the headings in the groups on the right. So, at a glance, it was impossible to tell how to use the menu -- it all looked like groups of links with headings.

So if you clicked an item under the apparent heading in the first column and all of the content to the right would change. The 'heading' would also move to the item you clicked. All very disorienting.

On the other hand, if you were to click an item on any column other than the first, it would take you to a new page. This, I suppose, would at least be the expected behaviour, but would the user have chosen something else if they'd known how the brand selection on the left worked?


## This is fine…

When I asked my friend about it he said that it was fine because the users of the website knew what all the things meant, so they could work out that the left-most column was brand names and the parts themselves were grouped in categories to the right.

But good design doesn't put extra burden on the user, so I worried that asking visitors to figure things out for themselves was going to lose sales. Or at the very least frustrate users such that they were put off coming back to order more.

I wryly asked him if he had any user testing testing supported his hypothesis, or analytics to measure how many users abandoned ship after opening a mega-menu, to which my friend laughed and said they none, of course!


## Making assumptions and underinvesting

This had the smell of a design job done without a designer, where the developer is left to an apparently straightforward design task. But I know this wasn't the case here -- a designer had handed my friend compositions to follow.

Instead of blaming the designer, though, which is all too common a theme in both design and development, I'm going to give them the benefit of the doubt and assume their hands were tied by too-tight timescales.

Over and over again, I see website owners and stakeholders justify their lack of investment in design with firmly held but more-than-likely incorrect assumptions about how people use their site.

That's not to say their industry knowledge is always wrong -- it can be *hugely* informative and forms a big part in shaping a product. It's just that underinvesting in design leads to decisions like that mega-menu's problematic visual structure. Waving concerns away with "our users will know what to do" is a way of justifying the underinvestment.

Our mega-menu issue is a good example of a design that just needed a *wee bit* of extra time to:

- visually differentiate the brand names on the left from the components that belong to that brand on the right
- create a connection between the selected brand and its components, to show that there's a hierarchy

A small amount of extra budget or a slight diversion of project resource in favour of design is often all that's needed to create a proper [MVP](https://en.wikipedia.org/wiki/Minimum_viable_product), and this can have a huge effect on users.
