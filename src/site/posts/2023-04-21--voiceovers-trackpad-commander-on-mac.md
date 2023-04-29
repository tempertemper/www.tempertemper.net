---
title: VoiceOver's Trackpad Commander on Mac
intro: Did you know there's a way to make VoiceOver on a Mac behave like VoiceOver on an iPhone or iPad? No? Let me introduce you to Trackpad Commander!
date: 2023-04-21
updated: 2023-04-29
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

If you haven't used VoiceOver on an Apple touch-screen device before, the good news is it's pretty simple. Here are the basic commands for Trackpad Commander on macOS:

<section class="table-wrapper" aria-labelledby="caption" tabindex="0">
    <table>
        <caption id="caption">Basic Trackpad Commander commands</caption>
        <thead>
            <tr>
                <th>Gesture</th>
                <th>Command</th>
                <th>Keyboard equivalent</th>
            </tr>
        </thead>
        <tr>
            <td>Flick right with one finger</td>
            <td>Move to next bit of content on the page</td>
            <td><kbd>⌃</kbd> (Control) + <kbd>⌥</kbd> (Option) + <kbd>→</kbd> (Right arrow key)</td>
        </tr>
        <tr>
            <td>Flick left with one finger</td>
            <td>Move to previous bit of content on the page</td>
            <td><kbd>⌃</kbd> + <kbd>⌥</kbd> + <kbd>←</kbd> (Left arrow key)</td>
        </tr>
        <tr>
            <td>Flick right with two fingers</td>
            <td>Move to next content area (or 'Window Spot', see below)</td>
            <td><kbd>⌃</kbd> + <kbd>⌥</kbd> + <kbd>⇧</kbd> (Shift) + <kbd>→</kbd></td>
        </tr>
        <tr>
            <td>Flick left with two fingers</td>
            <td>Move to previous content area</td>
            <td><kbd>⌃</kbd> + <kbd>⌥</kbd> + <kbd>⇧</kbd> + <kbd>←</kbd></td>
        </tr>
        <tr>
            <td>Double tap with one finger</td>
            <td>Activate an interactive item (like a button or link)</td>
            <td><kbd>⌃</kbd> + <kbd>⌥</kbd> + <kbd>Space</kbd></td>
        </tr>
        <tr>
            <td>Rotate two fingers clockwise</td>
            <td>Change to the next content type in the Rotor (headings, landmarks, and so on)</td>
            <td><kbd>⌃</kbd> + <kbd>⌥</kbd> + <kbd>u</kbd>, then <kbd>→</kbd> to cycle forwards through rotor items</td>
        </tr>
        <tr>
            <td>Rotate two fingers anticlockwise</td>
            <td>Change to the previous content type in the Rotor (headings, landmarks, and so on)</td>
            <td><kbd>⌃</kbd> + <kbd>⌥</kbd> + <kbd>u</kbd>, then <kbd>←</kbd> to cycle backwards through rotor items</td>
        </tr>
        <tr>
            <td>Flick down with one finger</td>
            <td>Jump to next instance of the content type selected in the Rotor</td>
            <td>In the Rotor, <kbd>↓</kbd> (Down arrow key) to move to next instance of the current content type, then <kbd>⏎</kbd> (Enter/Return) to go to it</td>
        </tr>
        <tr>
            <td>Flick up with one finger</td>
            <td>Jump to previous instance of the content type selected in the Rotor</td>
            <td>In the Rotor, <kbd>↑</kbd> (Up arrow key) to move to previous instance of the current content type, then <kbd>⏎</kbd> to go to it</td>
        </tr>
    </table>
</section>

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
