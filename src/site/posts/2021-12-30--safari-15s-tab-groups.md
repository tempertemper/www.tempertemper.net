---
title: Safari 15's Tab Groups
intro: |
    The road to Safari 15 was a bumpy old ride. Some things were announced at WWDC and then undone; others, like Safari's Tab Groups, turned out great.
date: 2021-12-30
tags:
    - Apple
summaryImage: tab-groups.jpg
summaryImageAlt: The words “Tab Groups” with the wallpaper for macOS 12 Monterey as a background. The wallpaper is overlapping colour gradients in pink and purple creating something that might be a canyon.
---

The road to Safari 15 was a bumpy old ride. Some things were [announced at WWDC](/blog/wwdc-2021-roundup#safari) and then sort of undone, like [using rounded rectangles for tabs on macOS](https://sixcolors.com/post/2021/10/safari-15-watch-old-tabs-edition/) and extending the website's background colour into the browser chrome. Other things turned out great, like moving the address bar to the bottom of the screen on iOS, and Tab Groups.

Grouping tabs has been around for a while; [Google Chrome launched it for their desktop browsers in 2020](https://blog.google/products/chrome/manage-tabs-with-google-chrome/), and while their solution makes managing those squillions of tabs a bit more sensible, it doesn't get to the *root* of the problem. Yes, you can collapse your groups to reduce the number of tabs on display, but Chrome's tab groups add *even more* noise up there in the tab bar:

- The tab group label (which is also a show/hide button)
- A continuous line for each 'open' group to mark its tabs
- Colour to provide extra differentiation between groups

With its settings/preferences in a tab rather than a floating 'pane', rounded tab shape, and weird ['kebab' menu](https://twitter.com/lukew/status/591296890030915585), Google Chrome already feels less like a native Mac app that it should; their tab groups really don't help.

Safari's Tab Groups, on the other hand, deal with the same problem nicely by moving the groups *to the side bar*. This feels in keeping with macOS in general:

- Tabs look and function like tabs everywhere on macOS
- Side bars are the de-facto design language for anything where lists of things are central (Mail, Notes, Messages, Photos, Maps, and so on)

It's an elegant solution and it tidies things up beautifully. No show/hide actions you might not expect, no unusual lines across the the bottom of tabs, no funky colours.


## How Safari's Tab Groups work

The Tab Group label in the sidebar controls the panel to the right, which might be one tab, or a hundred. I tend to keep the sidebar open but if I need more screen space, a quick `⌘` + `⇧` + `l` toggles it hidden.

You might think this would make knowing which group you're in and navigating between them more difficult but instead of the side bar with the current Tab Group highlighted in the list of groups, you get:

- A button in the browser chrome with a downward-pointing disclosure chevron, labelled with the current Tab Group's name
- A dropdown list of all Tab Groups when the button is pressed, with the current group marked with a tick

### Syncing

Abstracting the groups to a sidebar or dropdown menu adds a little to the initial learning curve compared to Google Chrome's implementation but one huge advantage is that it translates perfectly to mobile.

This means all Tab Groups and their tabs can synchronise seamlessly between all your devices via iCloud. And as you navigate around in each tab, the page is synced automatically across all of your devices, meaning you can pick up on your iPhone where you left off on your Mac.

### Enhancement

As all relatively complex design should be, Tab Groups are an *enhancement* to the way we use tabs, not a replacement. If you didn't know about them, you would continue to use Safari as you always had; when you start using Tab Groups, you'll still have a generic, non-grouped tab area for non-grouped tabs.

### Managing Tab Groups

Opening a new Tab Group is easy, whether on Mac or a mobile device; you can create an empty group or populate a new one with all the tabs currently in the generic tab area.

Moving tabs from the generic tab area to a group is straightforward, as is moving a tab from one group to another. Either using a right-click/long-press menu or dragging and dropping. Renaming, reordering, and removing Tab Groups is also extremely simple.

### Not bookmarks

Tab Groups differ from Bookmarks in that they represent working documents: new tabs can be opened, tabs can be closed, a web page in a tab can be navigated around, and it's all remembered and synced. Bookmarks are a more permanent place for reference or quick linking. I have GitHub bookmarked, but I also have GitHub open at certain repositories within several Tab Groups.


## Organisation

The primary selling point of Tab Groups is that they keep things tidy. Here are some examples of how I use Tab Groups:

- When I'm doing some research, say for a blog article, or something I'm looking to buy, I'll open a Tab Group to keep things nicely compartmentalised
- I have a Tab Group for publishing, with tabs open on my website's GitHub repository's Pull Requests page, Netlify's Deploys page, and so on
- I'm teaching myself [to play the bass guitar](https://twitter.com/tempertemper/status/1449290497822965763), so I have a Tab Group with tablature and YouTube videos for songs I fancy learning


## Focus

Aside from organisation, an unexpected benefit of Tab Groups is that they help with *focus*. I can't tell you how many times over the years I've been searching for a particular website in that mess of tabs, only to be distracted by another tab I'd forgotten about as I cycle through. Because Tab Groups keep things segmented, I don't have that problem anymore!

Suffice to say, I'm a big fan of Tab Groups, and they've become an integral part of how I browse the web. I'm a big fan of Safari in general: it's fast, easy on my battery and CPU, the most [Mac-assed Mac app](https://daringfireball.net/linked/2020/03/20/mac-assed-mac-apps) of all the web browsers; and now it has a great way to keep on top of my tabs!
