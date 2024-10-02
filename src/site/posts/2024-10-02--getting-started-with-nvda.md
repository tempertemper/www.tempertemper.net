---
title: Getting started with NVDA
intro: Everyone who works in digital product development should be familiar with screen reader software. Here's how to get started with NVDA on Windows.
date: 2024-10-02
tags:
    - Accessibility
related: getting-started-with-voiceover-on-macos
---

In my opinion, everyone who works in digital product development should be at the very least familiar with screen reader software. Some, though, should have a working knowledge of how to use it.

I've written about how to [get started with VoiceOver on macOS](/blog/getting-started-with-voiceover-on-macos) but VoiceOver is an Apple-only screen reader so what if you're a Windows user?

The good news is you've got plenty of options, from the built-in Narrator to the two most widely used screen readers in the world: JAWS and NVDA. Let's talk about the latter.

Page contents:

<nav aria-label="Page contents">

1. [Install NVDA](#install-nvda)
2. [Open your web browser](#open-your-web-browser)
3. [Configure NVDA](#configure-nvda)
4. [Navigation commands to get started](#navigation-commands-to-get-started)
5. [Form filling](#form-filling)

</nav>


## Install NVDA

NVDA isn't built in to Windows, so the first thing you need to do is [download the most recent version for free](https://www.nvaccess.org/download/).

When setting up, make sure NVDA doesn't launch on startup as you're probably not going to want it running all the time. For when you do want to do some testing with it, it's a good idea to pin the app to your Taskbar so that it's easy to fire up whenever needed.


## Open your web browser

This bit is much easier than with VoiceOver on Mac since NVDA works with any browser you might have on Windows. [GOV.UK recommend](https://www.gov.uk/service-manual/technology/testing-with-assistive-technologies#which-assistive-technologies-to-test-with) testing with Firefox, Chrome, or Edge, but every other browser I can think of is based on the same Chromium engine that Chrome and Edge are built on, so you can test with Opera, Brave, Vivaldi; whatever you like.

Having said that, GOV.UK list only Chrome or Edge as the browsers to test with JAWS, so I like to reserve Chromium browsers for JAWS and use Firefox for my NVDA testing; that way I get as broad a sweep of browsers in my testing as possible.


## Configure NVDA

Like with most software you test with, the default settings are pretty much what you want to be testing with since [very few users will change them](https://archive.uie.com/brainsparks/2011/09/14/do-users-change-their-settings/).

But there are a few things I do to make NVDA more useable for me, all of which you'll find by going to NVDA in the Taskbar's System Tray.

### Prevent automatic reading

First up, take full control. By default, NVDA will read its way through all of the content on the page until you stop it. I turn this off in Preferences → Settings… → Browse Mode → Automatically say all on page load.

### Disable mouse tracking

Next, keep things keyboard-only. I don't like that my focus position is moved to where my mouse cursor is hovering if I accidentally nudge the mouse, so I turn off mouse tracking in Preferences → Settings… → Mouse → Enable mouse tracking.

### Enable highlighting

I like to see where my cursor is, so I turn on highlighting, which places a blue rectangle around the element that I'm currently focused on. It can be a wee bit buggy sometimes when the page scrolls but I still prefer it. Activate it in Preferences → Settings… → Vision → Enable Highlighting.

### Speech viewer

Finally, I turn Speech viewer on in Tools → Speech viewer, which keeps a text-based log of all of the things NVDA reads out. Great in case I miss something it says, or I want to copy/paste something that it read out.


## Navigation commands to get started

Screen reader software can be noisy, so the first thing to know is how to pause it. [Like VoiceOver](/blog/getting-voiceover-to-shut-up), the <kbd>Ctrl</kbd> key will shut NVDA up. Aside from that, you only need a handful of commands to get around:

<dl>
    <dt><kbd>↓</kbd> (down arrow key)</dt>
        <dd>Go to next thing (heading, paragraph, list item, etc.)</dd>
    <dt><kbd>↑</kbd> (up arrow key)</dt>
        <dd>Go to previous thing (heading, paragraph, list item, etc.)</dd>
    <dt><kbd>⏎</kbd> (Enter/Return)</dt>
        <dd>Follow a link, press a button, or interact with a form field</dd>
    <dt><kbd>Space</kbd></dt>
        <dd>Press a button</dd>
    <dt><kbd>k</kbd></dt>
        <dd>Go to next link (useful for links that appear inside a sentence since, unlike VoiceOver, NVDA doesn’t stop before and after a link)</dd>
    <dt><kbd>⇧</kbd> (Shift) + <kbd>k</kbd></dt>
        <dd>Go to previous link</dd>
    <dt><kbd>h</kbd></dt>
        <dd>Go to next heading (<a href="https://webaim.org/projects/screenreadersurvey10/#finding">most screen reader users use headings to find information</a> on a page)</dd>
    <dt><kbd>⇧</kbd> + <kbd>h</kbd></dt>
        <dd>Go to previous heading</dd>
</dl>

Something else that will come in useful when navigating the web isn't an NVDA command, but a keyboard-only shortcut that's available whether you're using NVDA or not: go back a page with <kbd>Alt</kbd> + <kbd>←</kbd> (left arrow).


## Form filling

When you move your NVDA cursor to a form field, it will be read out but, unlike VoiceOver, simply typing will not do anything. In fact, it'll probably trigger a bunch of NVDA shortcuts that'll navigate you all over the page, and you'll end up disoriented.

As mentioned above, to interact with a form field when it has focus, press <kbd>⏎</kbd> and start typing (or, if you're on something like a `<select>`, [use default keyboard behaviour](/blog/how-to-browse-the-web-with-the-keyboard-alone#select-dropdowns) to choose an option). When finished, press the <kbd>Esc</kbd> key to go back to exit the form field and go back into 'navigation mode'.


## Take your time

NVDA is a powerful tool and there's loads more to learn, but that should get you started. Screen reader usage is a very different way to use the web for many of us, and can be noisy and disorienting when you're getting to grips with it. Patience and persistence are the key: find yourself a quiet spot where you can spend an uninterrupted hour with the software and you'll be well on your way.

Not only will it give you an amazing insight into one of the many ways people use the web, but it may even offer a better understanding underlying HTML/ARIA that makes up a web page. Good luck!
