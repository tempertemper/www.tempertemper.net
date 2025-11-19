---
title: An accessible pagination pattern (or two)
intro: A couple of weeks ago I added pagination to my blog, and it was an interesting delve into the various designs and markup patterns that can be used.
date: 2025-11-19
tags:
    - HTML
    - Accessibility
---

A couple of weeks ago, I added pagination to my blog. Well, my pal Damien Robson got the ball rolling by raising a pull request on my [GitHub repo](https://github.com/tempertemper/www.tempertemper.net) with the [core functionality](https://www.11ty.dev/docs/pagination/), and I took it from there.

It was an interesting delve into the various designs and markup patterns for pagination. At its simplest, pagination requires that the user:

- Has a mechanism to navigate from page to page
- Understands their current position within the pages


## A standard pattern

I remembered that the excellent Karl Groves had written [an article on accessible pagination](https://afixt.com/a-quick-primer-on-accessible-pagination/), so I used that as my starting reference point:

- Use `<a>` elements with `href` attributes, not `<button>`s
- Add `aria-current="page"` to the current page
- Wrap it all in a `<nav>` element

Pretty straightforward, and Karl's example uses one of the most common patterns you'll come across:

1. A link to the previous page
2. Numbered links to represent each page
3. A link to the next page


## How I decided to do it

I've always found the standard pagination pattern a bit too minimal. The information is all there implicitly, but you have to go through the whole component to work out where you currently sit in relation to all the pages. I decided to go with something a bit more verbose:

1. Set the scene by telling the user which page they're on and how many pages there are in total
2. Present links to a handful of key pages:
    1. The first page
    2. The previous page
    3. The next page
    4. The the last page

Here's a simplified example of my markup:

```html
<nav aria-label="pagination">
    <p>This is page 5 of 10. Go to:</p>
    <a href="/blog/page/1">First</a>
    <a href="/blog/page/4">Previous</a>
    <a href="/blog/page/6">Next</a>
    <a href="/blog/page/10">Last</a>
</nav>
```

I added a few embellishments, such as some hidden-from-assistive-technology icons to sit next to the links, for easy visual identification:

```html
<a href="/blog/page/1">
    <span aria-hidden="true">⇤</span>First
</a>
```

<i>I used 'Leftwards arrow to bar' (`⇤`) and 'Rightwards arrow to bar' (`⇥`) for the first and last pages, respectively, and 'Leftwards arrow' (`←`) and 'Rightwards arrow' (`→`) for the previous and next pages.</i>

### What about when a page doesn't exist?

If you're on the first page, the 'First' and 'Previous' links don't make sense, and if you're on the last page, the 'Next' and 'Last' links don't make sense.

Referring back to the [A Quick Primer on Accessible Pagination](https://afixt.com/a-quick-primer-on-accessible-pagination/) article, Karl gives styling for `.page-link[aria-disabled="true"]`, which suggests unusable links would remain on the page; this could be useful as users' expectations of the layout pattern would remain intact. But I'm very conscious that [disabled elements can be problematic for some people](/blog/how-to-avoid-disabled-buttons.html) as they:

- leave them to work out why the items are disabled and how to re-enable them
- are usually lower-than-ideal contrast (WCAG exempts 'inactive' elements from [1.4.11 Non-text Contrast](https://www.w3.org/TR/wcag/#non-text-contrast) and [1.4.3 Contrast (Minimum)](https://www.w3.org/TR/wcag/#contrast-minimum))

For me, it's better to remove the unusable links entirely, especially as we're already priming the user's expectations by telling them which page they're on before they reach the links. For example, if they're on page 1, they'll know that they're already on the first page and there's no previous page, so there are only 'Next' and 'Last' links:

```html
<nav aria-label="pagination">
    <p>This is page 1 of 10. Go to:</p>
    <a href="/blog/page/2">Next</a>
    <a href="/blog/page/10">Last</a>
</nav>
```


## The 'numbers' pattern is still valid

I used the more wordy pattern on my blog, but I have also used the more common 'numbers' pattern on some [client websites](/services/websites).

Digging into the pattern, the numbered links usually work like this:

- The central number represents the current page
- The link or links either side represent the page or pages prior to and following the current page
- The first page and last page are the left- and right-most page numbers
- There are gaps, often ellipses (`…`), between first page and the previous page(s), and the next page(s) and the last page, unless they're sequential (for example, you're on page 3)

Here's how it would look:

```html
<nav aria-label="Pagination">
    <a href="/blog/page/1">1</a>
    …
    <a href="/blog/page/4">4</a>
    <span aria-current="page">5</span>
    <a href="/blog/page/6">6</a>
    …
    <a href="/blog/page/10">10</a>
</nav>
```

Again, when a page isn't available, it's not linked to, so the first page, for example, would look like this:

```html
<nav aria-label="Pagination">
    <span aria-current="page">1</span>
    <a href="/blog/page/2">2</a>
    …
    <a href="/blog/page/10">10</a>
</nav>
```

You might notice a few differences between that and the example markup pattern Karl gave.

### No 'Previous'/'Next'

I removed the 'Previous' and 'Next' page links that sat either side of the numbers. Links to these pages are already in the number sequence, so presenting them again feels superfluous.

### Avoid icon-only links

The 'Previous' and 'Next' page links that I removed are icons in Karl's example. I tend to [avoid icons without text](/blog/what-i-wish-was-in-wcag-prohibit-icon-only-buttons.html) and [icon-only links](/blog/icon-only-links-fail-wcag.html) make me particularly uncomfortable. With them gone, I happily sidestep any icon-only controversy here!

### Remove the link for the current page

The current page would link to itself, which feels redundant. I made it text-only, which also makes it visually clearer that it's not a page you need to navigate to.

The `aria-current` stays, of course (although [I did have to check that was allowed](https://developer.mozilla.org/en-US/docs/Web/Accessibility/ARIA/Reference/Attributes/aria-current#associated_roles) on a `<span>`, and it is).

### Remove "navigation" from navigation

Finally, the label for my `<nav>` element is just "Pagination" so that screen reader users will hear "Pagination, navigation": the accessible name and the role. If the `aria-label` were `"Pagination navigation"`, it would read "Pagination navigation, navigation" in most screen reader software. Totally me just being super fussy, but anything to reduce a bit of noise.

### Something I considered and dismissed

I did think that maybe it would be useful to add the word 'Page' before each number, just for screen reader users, like this:

```html
<a href="/blog/page/10">
    <span class="visually-hidden">Page</span> 10
</a>
```

But I decided against it because:

- Sighted users don't get that information, so if it's not necessary visually it shouldn't be necessary non-visually
- Screen reader users already get the context that this is pagination from the `<nav>` container and its accessible name, so hearing "page" unnecessarily before each number would be repetitive and could get annoying


## Both patterns have their place

I ended up using the more explicit pattern because it surfaces the information I care about most: where you are and what options you have next. The sleeker 'numbers' pattern still works well in many situations and can be a better fit when space or convention calls for something more compact. The important thing is being intentional, rather than defaulting to whichever pattern comes bundled with a framework or library.
