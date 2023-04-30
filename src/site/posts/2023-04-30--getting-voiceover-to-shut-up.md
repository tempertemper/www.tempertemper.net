---
title: Getting VoiceOver to shut up
intro: The whole point of VoiceOver is that it talks out loud, but sometimes you need it to be quiet for a moment.
date: 2023-04-30
tags:
    - Accessibility
    - Apple
summaryImage: shhh-emoji.png
summaryImageAlt: The ‘Shushing face’ emoji.
---

I give a lot of demos using VoiceOver, and most of the time it involves moving the VoiceOver cursor through an interface, interacting with items, and talking people through what I'm doing. Sometimes, when I need jump in and explain something, I have to wait for VoiceOver to finish talking. Or do I?

If you're a designer, developer, or quality assurance tester who uses VoiceOver on a regular basis but it's not your primary method of getting around your computer, this is one of the first key commands you should know:

- Press the <kbd>⌃</kbd> (Control) key once to hush VoiceOver immediately
- Press <kbd>⌃</kbd> again to get VoiceOver to restart reading where it left off

You can also just move to the next bit of content on the page and it'll start talking again from there.

### With Trackpad Commander

This works whether using keyboard shortcuts or with [Trackpad Commander](/blog/voiceovers-trackpad-commander-on-mac), but Trackpad Commander also has a gesture to do the same thing: double tap with two fingers.


## Muting VoiceOver

Pressing <kbd>⌃</kbd> pauses VoiceOver until you press it again or move to the next bit of content on the page, but what if you want it to be quiet indefinitely? That's easy too!

1. Open VoiceOver Utility
2. Choose 'Speech' in the sidebar
3. Make sure you're in the 'Voices' tab
4. Check 'Mute speech'

To turn it back on, simply uncheck the 'Mute speech' checkbox.

You can also do this from VoiceOver's menus:

1. With VoiceOver running, open Commands Help with <kbd>⌃</kbd> + <kbd>⌥</kbd> (Option) + two presses of the <kbd>h</kbd> key
2. Choose either the 'Audio' menu or the 'Speech' menu
4. Go to 'Mute speech toggle' and press <kbd>⏎</kbd> (Enter/Return)

You could, of course, just press the mute volume key on your keyboard but if you want sound other than VoiceOver to play that's not going to cut it. Unfortunately, I couldn't find a shortcut to toggle VoiceOver's mute speech functionality directly.


### With Trackpad Commander

There is, however, a way of doing it directly with Trackpad Commander: just double tap with three fingers to toggle mute/unmute.


## One more noise-reducing tip

Once you're comfortable using VoiceOver, here's another tip to make it speak less:

1. Open VoiceOver Utility
2. Choose 'Verbosity' in the sidebar
3. Go to the 'Hints' tab
4. Uncheck 'Speak instructions for using the item in the VoiceOver cursor'

This turns off the instructions you get immediately after VoiceOver has read you the content, such as "You are currently on a link. To click this link, press Control-Option-Space" when you land on a link.
