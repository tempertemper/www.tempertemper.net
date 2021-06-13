---
title: Opening links in a new tab or window is better avoided
intro: |
    A link to an external source opening in a new tab or window is something that appears innocuous but really isn't as straightforward it seems.
date: 2020-09-21
updated: 2020-10-21
tags:
    - Design
    - Development
summaryImage: large
---

I've had several very similar conversations with clients and stakeholders over the years about opening links to other websites in a new tab or window.

Their rationale is that it keeps users on their website (blog, digital product, etc.) but, as with most things in web design, it's not as simple as it seems.

<i>I'll use "tab" from here on in. instead of repeating "tab or window"; for ease of reading, but also because opening in a new tab, rather than window, is the default behaviour for most web browsers.</i>


## Open all external links in a new browser

Let's say we're going to go for it: in design, consistency is important, so *every* external link should open in a new browser tab.

First, we have to consider the implementation, and then there are the user experience implications.

### Implementation

Getting every link to open in a new tab can be done by hand or programmatically.

#### Manually
I'll put it right out there: this is arduous. It also means that you can't use good old Markdown for your links (unless you're using one of a handful of 'flavours' of Markdown, like [Kramdown](https://stackoverflow.com/questions/4425198/can-i-create-links-with-target-blank-in-markdown#answer-4705645)).

The good news is that Markdown allows you to write HTML inside of it, so where you'd normally write a link like this:

```md
This is a paragraph of text [with a link in it](https://www.example.com).
```

You'd write the HTML out in full:

```html
This is a paragraph of text <a href="https://www.example.com" target="_blank" rel="noreferrer noopener">with a link in it</a>.
```

There's [quite a lot going on there](https://owasp.org/www-community/attacks/Reverse_Tabnabbing); more than a standard `<a>` tag with its `href=""` attribute!

That increases the friction of writing, something Markdown looks to solve, but what if you *forget*? What if whoever proof reads your work forgets too? What about typos? Too error prone.

#### Automatically
So if you plan open all external links in a new tab you're probably going to want to do it automatically/programmatically. There are a handful of ways to do this:

- [Using JavaScript to look for external links and add the relevant attributes](https://stackoverflow.com/a/13147238), but JavaScript can't ensure *every* visitor gets the same experience as the script may fail to run, the user may have JavaScript turned off, or a [content blocker](https://computers.tutsplus.com/tutorials/ios9-content-blockers-what-they-are-and-what-they-do--cms-24975) might be getting in the way
- Server side, so that a script is run before the page is served; again, this would ensure every visitor got the right code, but it would slow the page rendering down slightly for the user
- At build time, if you're using a static site generator; which  is a solid method, though it will slow your build down slightly for the developer: external links are searched for and the appropriate code added as the pages are built, so that all users get the right experience and the page load isn't slowed

My biggest issue with automatic implementation is exceptions. What if you're linking to another site *you own* and you'd prefer the user *not* to have a new tab opened for them? This is where an automatic solution could get problematic.

### User experience problems

The 'how' is one thing, but opening a link in a new tab can have serious user experience implications.

#### Broken back button
This is a biggie, [backed up by UK government research](https://design.tax.service.gov.uk/hmrc-design-patterns/open-links-in-a-new-window-or-tab/):

> some users struggle to get back to a service because the back button does not work in the new window or tab

The user can't use the back button if a new tab has been opened since the new page is the first in that tab's history (although more on that later); the content the user might want to go back to is in a different tab.

Navigating back a page is a fundamental to the way the web works: most users know they can go back to the page they came from. In fact, browsers and operating systems offer multiple ways for users to go back a page:

- Software back button in the browser (pretty much all browsers)
- Hardware back button (on older Android devices)
- Keyboard commands like <kbd>⌘</kbd> or <kbd>ctrl</kbd> + left (<kbd>←</kbd>), or sometimes just backspace (<kbd>⌫</kbd>)
- Gestures, for example:
    - a swipe right from the left edge of the screen on iOS
    - a two-finger swipe on an Apple trackpad
    - a single finger swipe on an [Apple Magic Mouse](https://www.apple.com/uk/shop/product/MLA02Z/A/magic-mouse-2-silver)
- Hardware buttons on [lots of mice](https://www.microsoft.com/accessories/en-gb/products/mice/microsoft-classic-intellimouse)

Not only are that, but web browsers go out of their way to make the experience consistent for you: when you go back a page, you're not dumped at the top of the page you came from; you're taken to the same part of the page where you followed the link. This orients the user nicely and allows them to carry on reading where they left off.

And quite apart from the user experience issues, the [Nielsen Norman Group put it nicely](https://www.nngroup.com/articles/the-top-ten-web-design-mistakes-of-1999/):

> the strategy is self-defeating since it disables the Back button which is the normal way users return to previous sites

So by trying to keep users on our site by leaving a separate tab open where the user left off, if they can't go back in the typical manner, their attention may turn to something else entirely.

#### Disorienting
When links open in new tabs, we really don't help an already slightly disorienting situation, especially on smaller mobile devices' web browsers.

On iOS, for example, when a link opens in a new tab the user briefly sees all of the tabs in a kind of rolodex view before being shown the new page. This animation in itself could be confusing if you were expecting just to be taken to a new page.

Users with visual impairments may also be left wondering what just happened, [as the W3C point out](https://www.w3.org/TR/UNDERSTANDING-WCAG20/consistent-behavior-no-extreme-changes-context.html):

> individuals who are blind or have low vision may have difficulty knowing when a visual context change has occurred, such as a new window popping up

In order to mitigate this, be sure to tell users what will happen when they press the link. Again, [UK government have carried out research](https://design-system.service.gov.uk/styles/typography/#external-links) on this:

> If you need a link to open in a new tab - for example, to stop the user losing information they’ve entered into a form - then include the words ‘opens in new tab’ as part of the link

This is expanded upon by the [W3C](https://www.w3.org/TR/UNDERSTANDING-WCAG20/consistent-behavior-no-extreme-changes-context.html):

> warning users of context changes in advance minimizes confusion when the user discovers that the back button no longer behaves as expected

#### Fiddly
Having found themselves in a new tab and unable to use the back button to return to the previous page, the user has more work to do.

On desktop (and tablets with keyboards), there are keyboard shortcuts (usually <kbd>⌘</kbd> or <kbd>ctrl</kbd> + <kbd>w</kbd>) to:

- close the tab you're looking at
- present you with the previously viewed tab

This is great *if* you know the shortcut! Thankfully, there's always your mouse/trackpad pointer: find your pointer, hover over the tab, and press the 'x'. That's a bit of a faff for most people and can be seriously tricky for those with fine motor skill impairments or less than tip top eyesight.

On mobile devices it gets even worse. You may have to scroll back up a little to get the browser chrome to reappear, then:

1. Tap the tab browser icon (assuming you know that that's what those overlapping squares are on Safari for iOS, or the number in the wee square is on Chrome for Android)
2. Close the tab you're on (by tapping the tab's 'x' on either operating system, or, on iOS, swiping left)
3. Find the page you came from
4. Tap the page you came from to return to it

I'd argue swiping/tapping back is a lot easier than that!

#### Browsers to the rescue
Some web browsers have addressed the issue, as [Šime Vidas points out](https://twitter.com/simevidas/status/1318275647337267201?s=21):

> the back button *does* work with links that open in a new tab on Android … pressing the back button closes the new tab, so the previous page (the opener) becomes the active tab again

This behaviour is there in Safari for both iOS and macOS and it is a good solution as it:

- brings the user back to the page they came from
- removes the clutter of the now-read linked-to page
- takes care of the fiddliness of closing the tab and finding the opening page again
- still makes sense when following links with an "opens in new tab" warning, as we see a new tab opening

Unfortunately I didn't find this behaviour in Chrome, Firefox, Edge or Opera Mini for iOS; Opera Touch presented a back button but it takes the user back to a blank tab, rather than closing the tab and going back to the previous. On desktop/laptop browsers other than Safari, the behaviour isn't present. So there's a lack of consensus, which means it can't be relied upon.

Furthermore, if the user doesn't realise this behaviour is there (I didn't!) or notice that the back button is active on this new tab, they're in the same situation as everybody else.

And, having seen the new tab opening, either explicitly in their browser or via an animation on a mobile browser, how many people who use the back button may expect to be taken back but for the opened tab to remain open?

It's not a perfect solution, but I'm not sure one exists.


## Don't link to any articles outside your website

OK, so opening a link in a new tab is probably not a great idea, either from an implementation, user experience, or consistency-of-experience point of view. So let's go to the other extreme and refrain from linking to any content outside our own website.

That means we don't have to worry about writing HTML (and remembering to add those fancy attributes!), scripting our solution, or even how it affects our visitors. But consider this:

- External links are useful; are you telling me you'd prefer to be unhelpful to your users?
- External links are *expected* where reference is being made to another related topic
- Writing without external links is a lot of work, as you're going to have to produce another article (or articles) to support any point you make that would be too tangential
- Linking to outside sources helps the credibility of your writing
- Linking only to articles you've written yourself could damage your authority: "Why is this thing the case? Because I said so."
- Not having external links is just a bit weird; they're what makes the web what it is, after all


## Let the web be the web

You've probably already noticed what I do on my website: nothing.

I write my links as links (using Markdown for simplicity) and let the user go to a new page in the same browser tab.

I never have to worry about whether a link should open in a new tab or not, adding extra attributes to the `<a>` element, scripting, and I don't override the default browser behaviour and upset my user's expectations; nor do I have to worry about writing supporting articles for every point I make that needs more context.

### Give the user control

As a user, if I *want* to open a link in a new tab I still can, but I actively do it myself:

- Holding down <kbd>⌘</kbd> or <kbd>ctrl</kbd> before clicking the link with my mouse or trackpad
- Right click and choose 'Open Link in New Tab' from the drop-down menu
- On a touch screen, long press a link and choose 'Open in New Tab'

This is built-in behaviour and exactly the kind of control that users should have if they want it.

### Be careful when overriding default browser behaviour

By forcing a link to open in a new browser tab we're overriding default browser behaviour; what makes your website so special that you can change decades-established learned user behaviour?

Actively making life more difficult for visitors is never going to win any brownie points, though rules are sometimes there to be broken. There are times when opening a link in a new tab is exactly the right thing to do for the user, but these decisions should be carefully considered, well communicated, and used sparingly.

Opening a link in a new tab seems like it would allow the user to have their cake and eat it: read about something else (guidance, context, evidence) while staying put on the website or web app/product/service. The problem is that the user has chosen to follow a link; they *want to leave* your website. That's not to say they won't come straight back, but that's their decision to make.
