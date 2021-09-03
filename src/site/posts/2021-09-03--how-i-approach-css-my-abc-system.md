---
title: "How I approach CSS: my ABC system"
intro: |
    CSS is easy to write but can become messy and bloated over time. A solid methodology can make maintenance much more comfortable; here's how I do it.
date: 2021-09-03
tags:
    - CSS
    - Development
summaryImage: large
---

There's a lot not to like about Twitter these days, but on balance I find it a positive place to be. Every now and then I see an interesting perspective in my timeline, like [this comment on writing CSS](https://twitter.com/bethcodes/status/1424739238822629380):

> I keep having to learn new alternatives to just writing CSS. Not one of them has actually been easier, more convenient or even easier to scale than just writing CSS, except [@MinaMarkham](https://twitter.com/MinaMarkham)’s design system. Which was her just writing some of the CSS instead of me.

It feels like a lot of developers are so uncomfortable with CSS that they look for new, ingenious ways to style their content:

- Some are methodologies that still follow the basic rules of CSS, like [BEM](//getbem.com) 
- Some, like [Tailwind](https://tailwindcss.com), use utility classes [moving styling to the HTML](https://css-tricks.com/if-were-gonna-criticize-utility-class-frameworks-lets-be-fair-about-it/)
- Others aren't CSS at all, and [let JavaScript do the work](https://css-tricks.com/a-thorough-analysis-of-css-in-js/)

I've used all of those approaches, and can see the positives in each, but I've never worked on a project that couldn't be tackled with well written CSS alone.

That said, I do lean on [tooling like Sass](https://sass-lang.com) (specifically, the more CSS-like <i>SCSS</i> syntax) and, though I try not to rely on it for anything complex like loops or maths, I make heavy use of:

- mixins and variables
- nesting
- partials

I know a lot of that can be achieved with native CSS now ([custom properties](https://css-tricks.com/a-complete-guide-to-custom-properties/)), will be possible in the near-ish future ([nesting](https://kilianvalkhof.com/2021/css-html/css-nesting-specificity-and-you/)), or can be done with more light-weight tools like Post-CSS (for example, [postcss-import](https://github.com/postcss/postcss-import)), but I like that Sass:

- doesn't rely on modern browsers for things like variables
- has a nice, concise syntax that still feels like CSS
- is a ubiquitous and well established way of writing CSS
- makes code reuse very easy

Because of this, the examples below are pretty Sass-centric.


## Scope every component

CSS-in-JavaScript is so popular because it scopes styles to every component. CSS can achieve this by giving the containing element a carefully chosen class name:

```html
<div class="unique-and-descriptive-component-name">
    <!-- The component contents -->
</div>
```

This can then be styled by starting with the `.unique-and-descriptive-component-name` class to provide the scope/context, and Sass's nesting lends itself to this beautifully:

```scss
.unique-and-descriptive-component-name {
  // Container styles; often things like `background-color`, `padding`, and `margin`
  
  element {
    // HTML element styles specific to the component, so this could be `h2` or `ul`
  }
  
  .class-styling {
    // Styling for a class inside the component
  }
}
```


## Keep specificity light

Selector weight can become problematic very quickly, so I try my best to keep this as light as possible. This means styles are as easy as possible to override without resorting to ID selectors or (whisper it) `!important`.


### Style with the lightest possible selector

Keep the [selector specificity](https://css-tricks.com/specifics-on-css-specificity/) light (less specific) by:

- Avoiding `!important` like the plague
- Don't style IDs, so no `#` selectors
- Style elements directly where possible, usually scoped by a class selector
- Make heavy use of classes with the `.` selector
- When a class isn't available, use attribute selectors (placing the attribute name inside `[]` brackets)

<i>There's a neat trick if you find the only hook you have to style an element is its ID, and that's to use the ID as an attribute selector like this: `[id="value-of-the-id-attribute"]`. *Much* lighter-weight than `#value-of-the-id-attribute`.</i>

### Style by as few selectors as possible

It's necessary to use multiple classes or elements in a selector when scoping styles to a component, for example:

```css
.unique-and-descriptive-component-name element {}
```

It's important to keep an eye on how many get in there, in case it gets out of hand. The more selectors in a style, the more weight the style gets, so the less easy it is to override.

Sass makes long selectors even easier, so be careful with:

- nesting depth (I *try* [not to exceed 3 levels deep](https://css-tricks.com/forums/topic/sass-best-practices-nesting-more-than-3-levels-deep/))
- mixins which have selectors in them


## Organise components into sensible groups

This is where my 'ABC' organisation system comes in. It follows a little bit of [SMACSS](http://smacss.com/book/categorizing), a little bit of [Atomic Design](https://atomicdesign.bradfrost.com/chapter-2/#the-atomic-design-methodology), and years of experimenting and trial and error. I arrange my Sass partials into folders/directories named:

1. Admin
2. Base
3. Components
4. Designs
5. Etc
6. Facades

The first thing you'll notice is that they begin with the first six letters of the alphabet. They're in a very deliberate order, as you'll see as I go into each, so it feels nice that they're listed in the right order without the need to prefix each directory with a number.

The fact that they're sequential is neither here nor there, but it appeals to my sense of tidiness.

The second thing you might notice (if you're a bit of a pedant like me!) is that 'Facades' should is missing its [cedilla](https://en.wikipedia.org/wiki/Ç): 'Façades'. Since it can be [spelled either way](https://english.stackexchange.com/questions/47792/facade-vs-façade) in English, I decided to put practicality over correctness; making typing the reference of the directory easier.

Right, now let's have a look at each of those directories in a bit more detail.

### Admin

This is where all of the stuff that doesn't compile directly to CSS lives:

- Variables
- Mixins
- Placeholders
- Functions

If a stylesheet only included Admin partials, it would be empty; they rely on being declared, included or extended in other places.

In my include file, I may also pull in stylesheets for third party tools/helpers like [Susy](https://www.oddbird.net/susy/) and [Modular Scale](https://github.com/modularscale/modularscale-sass) when I need them. Again, these don't compile to anything directly.

### Base

HTML element styling belongs in the Base directory; things like:

- Typography
- Links
- Buttons
- Forms
- Tables

I also keep other foundational styles like:

- Resets (there's a good [article on CSS-Tricks about resets](https://css-tricks.com/reboot-resets-reasoning/))
- Page styling (page width, background colour, and so on)
- Utility classes (like `.visually-hidden`), which I use rarely but can still serve a purpose

### Components

Components are reusable building blocks, for example:

- Extended base styles, like embedded videos which [need a wrapper to maintain their aspect ratio](https://css-tricks.com/responsive-iframes/)
- Discreet bits of user interface (UI) like breadcrumbs or pagination
- Items that appear on every page but aren't repeated, like the header and footer
- Single-purpose groupings like [text inputs](https://design-system.service.gov.uk/components/text-input/), their label, hint text, and error message styling
- Grouped content that has a visual container, like a 'Featured' box-out, or 'card'

<i>A Component can contain other Components, but if anything more complex than alignment or spacing is needed, or if the contained components repeat, it's a Design.</i>

### Designs

Designs are what we might commonly call 'patterns':

- Collections of repeated components, like the cards on a team members list page
- Groupings of several components that might need careful styling in places, like forms to collect credit card details

### Etc

This is where Internet Explorer specific styles live; print stylesheets also tend to live here. It's the 'everything else' bucket.

### Facades

'Facades' is a fancy way of saying Themes. 'Themes' would still have respected the alphabetical order, but I wanted to keep it sequential, so Façades just about works.

Façades aren't for every project, but I've worked on a few that create several [themed style sheets for individual apps in a product line](/portfolio/designing-a-cohesive-suite-of-applications#distinguishing-each-app); it's generally a place to configure colour schemes.


## Subdirectories

Each of the main directories can get pretty full, so it's often helpful to break them down with subdirectories. Perhaps things like `/base/typography/` and `/base/forms/` would help with organisation.

[Sass's Index Files](https://sass-lang.com/documentation/at-rules/import#index-files) come in useful here.


## File names

Each file should be named so that a developer looking at it will know intuitively its purpose and the CSS that is likely to be in there.

If there's a system like [Critical CSS](/blog/critical-css-what-it-is-why-its-useful-and-how-it-works) involved, I use a suffix that's separated from the descriptive file name by two dashes (`--`). So where a partial has to be divided into critical and non-critical, I simply append each with `--critical` and `--non-critical`.

It's best to avoid generic partials like `_general.scss`. Styles are always specific to *something*, even if that thing is pretty broad, like the page itself (`_page.scss`).


## Keep it tidy

Just as its important to name the partial files carefully, it's well worth the extra time to write each new style well.

I subscribe to the [Do Not Repeat Yourself (DRY)](https://en.wikipedia.org/wiki/Don%27t_repeat_yourself) principle where I try not to repeat the same code in more than one place. This makes maintaining and enhancing styling easier (and safer!), and is where Sass's mixins and includes come in very handy.

I also avoid having a deliberate 'technical debt' stylesheet/partial, where styles can be added quickly and shipped, with the plan to refactor them another time. Of course, what happens in practice is that they:

- are never revisited
- are hard to read
- become a spaghetti of brittle styling
- encourage bad habits

I've seen the files named things like `_shame.scss`,  which a least has an element of self-awareness, but just because you're aware that it's a bad idea doesn't make it any better.


## Code formatting

The formatting within a file isn't import to my system, but consistently formatted code is a good idea to keep a project tidy. An `.editorconfig` file can help with consistent indentation (I like two-space indentation in my CSS), and tools like [StyleLint](https://stylelint.io/) can do a *lot* more, but if and how that's all done depends on the project.


## CSS as the first approach

There's no magic bullet to make CSS behave the way we want it to. [Just like HTML](/blog/html-is-more-complicated-than-you-think), it's an easy language to pick up and write, but it's also easy to let things get out of hand.

CSS is a powerful tool and, if used well, should be the default approach to styling interfaces on the web. Sometimes an alternative like Tailwind or CSS-in-JavaScript is the answer, but, these should be explored only after eliminating plain old CSS as the right tool for the job.

My 'ABC' system has made authoring and maintaining styles easier for me and teams I've worked with over the years. Feel free to use, adapt, or just take anything you find helpful.
