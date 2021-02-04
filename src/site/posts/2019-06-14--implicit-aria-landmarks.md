---
title: Implicit ARIA landmark roles
intro: |
    ARIA landmarks give a screen reader user an easy way to orient themselves on a web page. Implicit roles are also great. Except when they're not.
date: 2019-06-14
tags:
    - Development
    - Accessibility
updated: 2020-08-24
---

ARIA roles are a way to define various landmarks on a webpage; things like:

- The header
- The main navigation
- The search box
- Complementary content
- The main content area
- The footer

They give a screen reader user an easy way to orient themselves on a web page by naming the main areas on the page; exactly as a sighted user would identify the same areas at a glance.

Some of these landmarks can be used a few times, for example, there may be primary and secondary navigation menus, but other landmarks only make sense when they're unique:

- You should only have one header on a page
- There can only be one main content area
- One page footer is all you need

So far so good. But what about [implicit mappings](https://a11yproject.com/posts/aria-landmark-roles/#html5-implicit-mappings-of-landmark-roles)? This means that a `<header>` should automatically be interpreted as having a `role="banner"` and a `<footer>` should get a `role="contentinfo"`, but that can't be right if we're [allowed to use multiple](//html5doctor.com/the-header-element/) `<header>`s and `<footer>`s…


## The theory

The answer lies in sectioning elements. The W3C specification says a role of `banner` will be [automatically assigned](https://www.w3.org/TR/html-aria/#header) to a `<header>` element unless it's

> a descendant of an article, aside, main, nav or section element

So only the first `header` element in this next example will get an implicit ARIA landmark role:

```html
<body>
    <header>
        <h1>This is a great heading</h1>
    </header>
    <p>Isn't this great content?</p>
    <section>
        <header>
            <h2>This is a nested section</h2>
        </header>
        <p>Here are some details about the nested section.</p>
    </section>
</body>
```

The browser knows the main page header is the first `<header>` and not the second one because:

- the first is a child `<header>` of the `<body>` element
- the second one is separated from the `<body>` element by being wrapped in `<section>` tags


## In practice

In my testing (on VoiceOver on macOS 10.14.5), this only seems to work with `<article>` and `<section>` elements. `<main>`, `<nav>` and `<aside>` don't scope their nested `<header>` as the specs suggest they should, so the document gets two implicit `role="banner"` elements. This is bad.

Also, the [same principle](https://www.w3.org/TR/html-aria/#footer) is *supposed* to be true for the `<footer>` element, but it doesn't add `role="contentinfo"` implicitly.

<b>Update:</b> Turns out this was a bug with VoiceOver/Safari and has [since been fixed](/blog/webkit-has-fixed-the-implicit-role-on-footers)!


## The annoying thing

Browser support is a thing in frontend development, and unfortunately/predictably if you want to [support Internet Explorer](https://www.html5accessibility.com) (and I'm not talking about IE8; I mean *any* version, including the most recent, IE11) you have to be explicit with your roles.

But the thing that bugs me about all of this is that the WC3 page on [ARIA in HTML](https://www.w3.org/TR/html-aria/#h-note) tells us

> Setting an ARIA `role` and/or `aria-*` attribute that matches the implicit ARIA semantics is unnecessary and is *NOT RECOMMENDED* as these properties are already set by the browser

Now, where I'm from, all-caps aren't use lightly. In fact, I'm pretty sure it's *not recommended* to use all-caps to emphasise text in HTML… (Granted they're using `<em>` tags, but isn't `<strong>` the proper way to ramp up the emphasis? Naughty.)

I *get* that adding `role="heading"` to a heading is unnecessary, but if the implicit ARIA roles *aren't working*, I'm still going to have to do something manually.


## What to do

So the principle is sound:

1. Don't worry about ARIA landmark roles as the browser is smart enough to add them for you
2. Use as many headers and footers as you like, but make sure they're scoped inside a sectioning element

But as you can see, this isn't the case.

I'll be continuing to add `role=""` attributes to elements that I want to be landmarks. Assuming the browser will add them for me means some could be missed.

I'll also feel more comfortable using more than one `<header>` on a page, but it'll only be doing it inside either an `<article>` or `<section>` -- I don't want any rogue landmarks being created!

If only frontend development were easy!
