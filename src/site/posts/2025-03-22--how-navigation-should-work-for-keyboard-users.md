---
title: How navigation should work for keyboard users
intro: The web is a network of pages that are linked together, with those links often grouped in a navigation. Here's how keyboard users traverse navigation.
date: 2025-03-22
tags:
    - Accessibility
    - Development
related:
    - theres-no-such-thing-as-menubar-navigation
    - how-button-groups-should-work-for-keyboard-users
---

Navigation is a central concept in web design, the web being a network of interconnected pages and all that. Navigation is found in a few standard places; almost always in the header of each page, sometimes in the footer, and every now and then in amongst the main page content.

I've written about [how button groups work for keyboard users](/blog/how-button-groups-should-work-for-keyboard-users), so in the interests of completion I thought it would be worth doing the same thing for navigation. Spoiler alert: it's much, much simpler.

<i>Note: although this is about keyboard users, like the article about button groups there's example markup, and it would remiss of me not to explain how it serves screen reader users too, so you're getting a two-for-one!</i>


## An example of navigation

Let's start with some example markup:

```html
<nav aria-label="Primary">
    <ul>
        <li><a href="/home">Home</a></li>
        <li><a href="/about">About</a></li>
        <li><a href="/contact">Contact</a></li>
    </ul>
</nav>
```

Unlike the button group markup, I haven't left anything out here. And there's no fancy JavaScript needed to move focus around. Let's pick our markup apart:

1. First we have the `<nav>` wrapper which carries the implicit 'navigation' role so that screen reader users can use it as a landmark
2. Then we have its `aria-label`, with which we communicate the type of navigation this particular one is, in case there are multiple navigation landmarks
3. Next up an unordered list (`<ul>`) to tell screen reader users that they've encountered a list of items and how many there are; it's unordered because the order of the items isn't central to understanding the list
4. Then some list items themselves, each marked up with the `<li>` element
5. Finally, each list item contains a link to a different page on the same website, using an `<a>` element with an `href` attribute

The thing on the end of all the navigation and list markup is a link. Navigation is all about groups of *links*, not *actions* (as that's what button groups are for).


## Sometimes navigation contains buttons

That's not to say you'll never see a button in navigation though. Let's have a look:

```html
<nav aria-label="Primary">
    <ul>
        <li><a href="/home">Home</a></li>
        <li>
            <button aria-expanded="false">About</button>
            <ul hidden>
                <li><a href="/about/ethos">Our ethos</a></li>
                <li><a href="/about/team">Meet the team</a></li>
                <li><a href="/about/history">Company history</a></li>
            </ul>
        </li>
        <li><a href="/contact">Contact</a></li>
    </ul>
</nav>
```

How does that differ from our first example?

- The second navigation item is no longer a link to an 'About' page, it's a button (the trigger for a sub-navigation) and a list (the sub-nav)
- The button has an `aria-expanded` attribute in the `false` state, to tell screen reader users that there's some content that will appear on press (this should be paired with a visual indicator to do the same; usually a wee downward-pointing arrow/chevron/triangle added with CSS)
- The sub-nav has the `hidden` attribute so that it doesn't appear on screen and isn't available to assistive technologies like screen readers
- The button would use some JavaScript to remove the `hidden` attribute on press, which would in turn reveal the sub-nav with its links to various pages about the company
- When the sub-nav is opened, some JavaScript should change the `aria-expanded` value on the button from `false` to `true`

So although a `<button>` makes an appearance, it's not part of a distinct group of buttons; its action applies directly to the navigation.


## Keyboard behaviour

Unlike button groups, the keyboard behaviour for navigation is super simple and requires zero JavaScript if there isn't a sub-nav:

- <kbd>⇥</kbd> (Tab) moves focus from one link to the next
- <kbd>⇧</kbd> (Shift) + <kbd>⇥</kbd> moves focus backwards
- <kbd>⏎</kbd> (Return) follows a link

That's it!

<i>Don't forget to [use a skip link](/blog/skip-links-what-why-and-how) so that users can jump past your navigation to interact with the main page content.</i>

### Adding buttons into the mix

If there's sub-navigation (and therefore one or more buttons), things get slightly more complex:

- <kbd>⇥</kbd> moves focus from one link or button to the next
- <kbd>⇧</kbd> + <kbd>⇥</kbd> moves focus backwards
- <kbd>⏎</kbd> (Return) follows a link
- <kbd>⏎</kbd> or <kbd>Space</kbd> presses a button
- <kbd>Esc</kbd> closes an open sub-nav

When a button is pressed, focus should remain *on the button* so that it can be immediately closed if needed, and if the user wants to move focus into the sub-nav they'd press <kbd>⇥</kbd> (and keep pressing <kbd>⇥</kbd> to continue through the sub-nav).

In other words, unlike a menu in a menubar, the sub-nav should not show automatically when the trigger is focused, and focus should not move into the sub-nav automatically. The arrow keys should do nothing (other than scroll the page, as they usually do).

### When does a sub-nav close?

The only question mark is when/if the sub-nav should close automatically. There are a couple of options when focus leaves the sub-nav or its trigger button:

1. The sub-nav remains open
2. The sub-nav closes

Option one needs two things to avoid overlapping sub-navs and covering page content outside the navigation by a still-open sub-nav:

- Close an open sub-nav when another sub-nav is opened
- Close an open sub-nav when focus leaves the navigation

I like things to be as simple as possible, so option 1 appeals to me. just close an open sub-nav when focus moves past it.


## Nothing more to add

There's really not a lot to it! Tab, tab, tab, and hit Return when you get to the page you want to visit. Nothing fancy and very easy to code, which is just as well as you're going to need navigation on pretty much every project you work on!
