---
title: Establishing a design system
intro: |
    Putting a pattern library together is quite a task, but establishing a design system around it is the real hard work.
date: 2018-06-20
---

A design system is vital to the long term health of a digital product. When I started at EvaluAgent in February 2017 the team was growing rapidly to work on version 2 of their suite of products.

Like most startups, they had set out with a single good idea and, as their customer base had required new features that were in line with the product vision, new functionality had been added. This inevitably led to a bloated backend, cramped user interface (UI) and a messy user experience.

There was a higher-level design job to ensure the suite of products' [look and feel was cohesive](/portfolio/designing-a-cohesive-suite-of-applications), but part of that was establishing a pattern library and a design system around it.


## Starting in Sketch

[Sketch](https://www.sketch.com/) is a great playground for designing a UI from scratch, so I started there. It was where I established the initial look and feel, creating lots of variants and presenting them to the various stakeholders.

### Library

Once a clear direction was signed off, I created a [Library in Sketch](https://www.sketch.com/docs/libraries/), to save duplication and make building new UI mockups more efficient.

<img src="/assets/img/case-studies/design-system--sketch-library.png" alt="Sketch library for EvaluAgent app UI, showing a very high-level view of form elements, breadcrumbs, buttons and so on" width="800" height="450" loading="lazy" />

### Mockups

With the Library in place, page mockups were simple to rustle up to demonstrate the way the UI would fit together in a certain scenario:

<img src="/assets/img/case-studies/evaluagent--dashboard.png" alt="Dashboard app, showing doughnut charts and key numbers for CSat, calls logged and more example 'cards'" width="800" height="450" loading="lazy" />

### Prototypes

And with all those pages mocked up, it was important to communicate how the pages were all strung together. This was done with flow diagrams and, where on-page detail was required for demos, [InVision's Craft plugin for Sketch](https://www.invisionapp.com/craft) was invaluable:

<img src="/assets/img/case-studies/design-system--prototype.png" alt="A very high-level view of several Sketch artboards with Craft arrows pointing from various UI elements to the page they would take the user to" width="800" height="450" loading="lazy" />

### Experimentation

Sketch also lends itself well to experimentation, where new ideas can be thrown around quickly. Here's an example of three new dashboard widgets I designed:

<img src="/assets/img/case-studies/design-system--experiments.png" alt="Three new dashboard widgets that were designed: a comparison line graph, a comparison table and a performance range chart" width="800" height="450" loading="lazy" />

Each of those came from some time trying different variants and weighing up the various usability pros and cons of each. Trying new ideas about is easy with a UI app like Sketch.


## From mockups to code

Static mockups in Sketch played a huge role in the design system I created, both at the outset and on an ongoing basis, whenever something new was needed. But a design system doesn't stop at the visuals. I needed a central place to document each component.

A [code-based pattern library](https://fractal.build/) would be the canonical source of patterns and code, once they had passed that experimental phase. As mentioned in [my other case study on EvaluAgent](/portfolio/designing-a-cohesive-suite-of-applications#one-central-pattern-library), the following would be documented:

- base styles like typography and the smallest UI elements, like buttons, checkboxes, etc.
- components, made up up base elements, like a dashboard card or drop-down menu
- combinations of those components, like a whole dashboard of cards containing various information in each
- whole-page previews

This pattern library would be a reference point for a growing design team as well as a place engineers would come to get the HTML and CSS to build the UI in production.

Using CSS also meant I could introduce app-specific styling, meaning the various products that made up the suite would have [their own colour scheme](/portfolio/designing-a-cohesive-suite-of-applications#distinguishing-each-app).



### Delivering code to engineers

The structure of the engineering team was evolving into two parts:

1. infrastructure
2. integration

Integration was where I had the most contact as they would take my production-ready HTML and CSS to build the app's UI, hooking up to APIs the infrastructure team were building.

The interface was built in [Vue.js](https://vuejs.org) so the code in the pattern library was deliberately kept JavaScript free. This meant:

- it would be technology agnostic and future proof: if the engineers moved to a new frontend framework the library would require no updates
- maintenance would be simpler, as all JavaScript enhancements would be taken care of by the framework; states (for example 'open' and 'closed') were documented as a 'variant' in the pattern library


A new ticket in JIRA for implementation including:

- A summary of the changes required and rationale if needed
- Link to the pattern in the pattern library
- Images or a video demonstrating how the UI elements that have changed visually
- The HTML required
- Any other assets needed (for example, an updated CSS file)
- A link to the pull/merge request in the pattern library repository, so that diffs can be viewed

This ticket would then get some scrutiny by the engineers, leading to further refinement or estimation if ready to bring into a future sprint.





 the flexibility I needed,

Deliverable

Sketch would always come in useful though – for new components or page arrangements, it's usually simplest to use a drawing tool like Sketch.






https://evaluagent-pattern-library.tempertemper.net

[](/portfolio/designing-a-cohesive-suite-of-applications#one-central-pattern-library)

- base styles from headings and other typographical elements to form components like inputs, checkboxes, radios and buttons
- more bespoke components like cards, charts and graphs
- combinations of those components
- whole-page previews








politics – not rocking the boat, selling the idea
working with developers
communication

continual monitoring
