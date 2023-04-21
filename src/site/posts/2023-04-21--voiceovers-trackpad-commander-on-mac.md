---
title: VoiceOver's Trackpad Commander on Mac
intro: Did you know there's a way to make VoiceOver on a Mac behave like VoiceOver on an iPhone or iPad? No? Let me introduce you to Trackpad Commander!
date: 2023-04-21
tags:
    - Accessibility
    - Apple
---

By default, [VoiceOver on a Mac](/blog/getting-started-with-voiceover-on-macos) is controlled by keyboard commands. This is different to VoiceOver on iOS or iPadOS, which uses swipe gestures instead (since iPhones and iPads are primarily touch-screen devices).

If you like the idea of swiping around an interface using VoiceOver rather than learning all sorts of funky key combinations, Trackpad Commander is worth a try.

Essentially, by turning on the Trackpad Commander you're switching from a purely keyboard-driven experience to a touch-driven one. To turn it on:

1. Open VoiceOver Utility
2. Choose 'Commanders' in the sidebar
3. Ensure you're on the 'Trackpad' tab
4. Check 'Enable Trackpad Commander'

Sensibly, the gestures are all the same as on iOS/iPadOS. So if you're already used to using VoiceOver on an iPhone or iPad, you'll be fine.

If you haven't used VoiceOver on an Apple touch-screen device before, the good news is it's very simple! Here are the basic commands:

- <b>Flick right with one finger</b>: Move to next bit of content on the page
- <b>Flick left with one finger</b>: Move to previous bit of content on the page
- <b>Rotate two fingers clockwise</b>: Change to the next item in the Rotor (headings, landmarks, and so on)
- <b>Rotate two fingers anticlockwise</b>: Change to the previous item in the Rotor (headings, landmarks, and so on)
- <b>Flick down with one finger</b>: Jump to next instance of the item selected in the rotor
- <b>Flick up with one finger</b>: Jump to previous instance of the item selected in the rotor


## No trackpad pointer with Trackpad Commander!

Unlike typical VoiceOver, which lets you use the normal trackpad pointer in conjunction with VoiceOver, Trackpad Commander disables typical pointer use.

Something that confused me at first was the hover mode (for want of an official term for the feature). This is when you hold your finger on the trackpad for a brief period and a blue ring cursor about the size of a fingertip appears.

Instead of a typical pointer where you hover over something and click to activate it, Trackpad Commander's pointer is used to change VoiceOver's focus from one 'Window Spot' to another within an app (something you can also do using the Window Spots option in VoiceOver's Rotor).

Window Spots are sort of high-level content zones within a app's window. Here's an example using Apple's Notes app that might illustrate it better; its Window Spots are:

- The toolbar
- The search box
- The folder list
- The list of notes within the chosen folder
- The main content area, for reading and editing the chosen note

This maps roughly onto most apps. Email apps, web browsers, even code editors all have toolbars, search, a list of items (sometimes more than one list if there's a hierarchy), and a main content area.

The cursor behaviour works like this:

- Every Window Spot except the current one is dimmed
- When you move the cursor over a different Windows Spot:
    - The new Windows Spot is undimmed
    - The previously in-view Windows Spot is dimmed
    - VoiceOver announces the title/name of the newly focused Windows Spot
- Taking your finger off the trackpad leaves focus on the most recently hovered frame and the dimmed surrounding frames are undimmed; you can now interact with that Window Spot

In this mode, since it's all about the current window, you can't move the cursor outside of the currently interactive window. It's also worth mentioning that the top-left of the trackpad represents the top-left of the window, the top-right of the trackpad represents the top-right of the window, and so on.


## Is Trackpad Commander better?

For many people, learning and memorising the various VoiceOver keyboard shortcuts, and contorting their fingers to activate them is a lot to ask. In fact, people with the use of only one hand would have to rely on Sticky Keys, so they may prefer the gesture-based Trackpad Commander.

Conceptually, Trackpad Commander is much simpler than the default way to control VoiceOver, so it could be an easier way for people unfamiliar with screen reader software to get started.

I've been using VoiceOver with the keyboard for years so I'm comfortable enough getting around that way, but I'll definitely be presenting Trackpad Commander as an option when introducing the software to the designers, developers, and testers I work with.
