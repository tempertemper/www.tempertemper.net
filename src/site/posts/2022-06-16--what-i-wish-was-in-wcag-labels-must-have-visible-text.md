---
title: "What I wish was in WCAG: prohibit icon-only buttons"
intro: The Web Content Accessibility Guidelines (WCAG) ensure that buttons have a programmatical accessible name, but it doesn't have to be visible.
date: 2022-06-16
tags:
    - Accessibility
summaryImage: icon-only-button.png
summaryImageAlt: A big blue button on a grey background; the button is labelled with a question mark icon.
---

I had a conversation this week where I was asked a great question: what was one thing I wished WCAG included? For me it would be to ensure that every button's label has visible text.

WCAG requires we [provide a visible label buttons on our buttons](https://www.w3.org/TR/WCAG21/#headings-and-labels), but this can be with a 'text alternative' like an image or icon. In turn, [these icons need an accessible name](https://www.w3.org/TR/WCAG21/#text-alternatives), which is great for people like screen reader users, but what about people who can see the icon but don't have access to its accessible text?


## Icon meaning

A lot has been written about link wording, so if [a link is a promise](https://www.nngroup.com/articles/link-promise/), shouldn't we be ensuring our users know what will happen when they press a button?

### Differences in understanding

Some icons are very widely used and it is assumed that all of our users know what they mean. But I'm not sure that's fair. *I* know what will happen when I press a 'B' button above a text editor, or the button with the [floppy disk icon](https://www.hanselman.com/blog/the-floppy-disk-means-save-and-14-other-old-people-icons-that-dont-make-sense-anymore), but will everyone?

Per Axbom explored the [international differences around the meaning of the checkmark icon](https://twitter.com/axbom/status/1437687684508659713?s=21&t=TsIMCi7SN7UzfotcnaZSAg) on Twitter:

> Struggling with an icon (as one does). The struggle here is that the check mark is common for "ok/ready/done". In Sweden it has historically been used to mark wrong answers on tests. The opposite, really.

There are some more insights in the thread:

> In Finland, ✓ often stands for väärin, i.e., "wrong", due to its similarity to a slanted v.

> In Japan, the O mark is used instead of the check mark, and the X or ✓ mark are commonly used for wrong.

> Quite similar to Portugal [where] c … would definitely mean correct (c for "correto" or "certo" = right).

Are we happy that some users might have to do extra work to understand what pressing a button will do? Do they know if the action triggered by pressing it will be reversible or not? Is the stress that causes for some people acceptable? Some better options would be:

1. Pair the icon with a text label
2. Remove the icon altogether, relying solely on text

There are, as always, downsides to each of these approaches, but either way it's a huge improvement:

1. We'd still be creating a bit of extra work for some users as they reconcile their understanding of the icon with the text that sits next to it
2. Many dyslexic users have a better experience when visual cues like icons are used, so we'd be forcing them to read the label text

But, even if it's slightly more work for some, a text label will allow the button's function to be understood by everyone.

### Running out of ideas

Some icons might make sense to those that are familiar with a certain culture or of a certain age group, but another problem with icons is that it gets tough to keep them unambiguous as the number of applications grows.

Sure, we might start out with a well known clutch of four or five icons, but as functionality grows over time we reach for increasingly obscure and tenuous illustrations, and hope our users know what they mean.

Doesn't sound like a great strategy to me. Adding some text next to an icon makes its function unambiguous; now that icon that looks a bit like a potato isn't crucial, it's essentially decoration, and it's the text that does the work.


## Speech recognition software

Have you ever played the icon-only button guessing game? Unfortunately, it's not a particularly fun game.

When I'm testing an interface with speech recognition software like Apple's Voice Control and encounter an icon-only button, I need to ensure I can press it. My first guess is often wrong. My second guess too. In fact, sometimes I have to give up and use the 'Show numbers' command, which assigns each interactive interface element (buttons, links, form fields) with a number; I can then say "Click 7", or whatever number has been assigned to the button I was trying to press.

I can only imagine how infuriating this must be for someone who *depends* on speech recognition software.

On the a11y rules podcast, Tori Clark talked about [visible labels and using Dragon](https://a11yrules.com/podcast/tori-clark-talks-about-visible-labels-and-dragon/), a speech recognition software package:

> Fortunately, there does seem to be some common language and some common icon, so for me if I see a house icon that’s meant to be for the homepage, I can pretty reliably say "home" and click on that link because the link text that’s not visible is 'home'. I do the same thing for mobile menus: I can say "menu" or "main menu" or "mobile menu" and it works.
> 
> But occasionally some people try to get fancy and they have an accessible name of "toggle navigation". And there’s no way I could guess that. I think my favourite example though is the trash can for a 'remove' or 'delete' icon. I have had to guess so many different accessible names from "remove" to "cancel" to "delete" to "trash". So I often jokingly say, when I’m trying to tell people how to do better: icon only buttons are trash.

Adding a text label alongside an icon removes this guesswork and lets the user know *exactly* what to say to activate the button.


## I can dream, I suppose

I know it'll probably never happen: the web is riddled with icon-only buttons, and the extra space used with the addition of text labels will break the layouts of a million websites and web applications. But here's wishing.
