---
title: Text snippets on Apple devices
intro: |
    I've been using Apple's built-in Text Replacement instead of TextExpander for a while now. It's pretty basic, but it's free and it does the job.
date: 2020-08-13
tags:
    - Apple
    - Workflows
---

I used to use [TextExpander](https://textexpander.com) a lot. I happily paid for major version upgrades when they were released, and when they started charging annually I didn't mind forking out the $18 or so each year. But when they moved to monthly subscription I was out. I tend not to notice annual subscriptions in the same way I do monthly ones.

And with that new found scrutiny, I realised I could get the majority of the functionality I was getting from TextExpander for *free*. How? Apple's built-in Text Replacement feature.


## Text Replacement on iOS and macOS

If you're not sure what Text Replacement is, head to:

- Settings → General → Keyboard → Text Replacement (iOS)
- System Preferences → Keyboard → Text (macOS)

I've used Text Replacement on my iPhone for a long time, where TextExpander wasn't available system-wide. I had tried the [TextExpander Custom Keyboard](https://www.imore.com/best-custom-keyboards-ios-8) but it didn't run well on the iPhone 6 I was using at the time, and the colour scheme and goofy TextExpander [logo on the keyboard](https://textexpander.com/blog/textexpander-tip-try-our-ios-keyboard-and-one-tap-text-snippet-keys) didn't help. So I used Text Replacement on my phone and TextExpander on my Mac.


## Text snippets

I didn't use TextExpander for anything particularly powerful; just small conveniences like autocompleting an email address here, or adding an email signature there.

Even then, there weren't *that many* snippets on my iOS device -- just often-typed words; especially those that contained <kbd>@</kbd> signs and other fiddly punctuation.

The convention I used was either double initial letter or an abbreviation that I knew wouldn't clash with a real word, so:

- <kbd>eemail</kbd> would output my email
- <kbd>wweb</kbd> would output my website address
- <kbd>fene</kbd> would output 'Frontend NE'

So when it came to dropping TextExpander, I just added the more hefty snippets to my Text Replacement library and I was away! I added things like:

- <kbd>ssigp</kbd> to output my multi-line personal email signature (replacing the <kbd>p</kbd> with another letter like <kbd>f</kbd> would output my Frontend NE signature)
- <kbd>hhome</kbd> to output my home address
- <kbd>llorem</kbd> to output a bunch of [lorem ipsum](https://en.wikipedia.org/wiki/Lorem_ipsum) text


## Emoji overrides

Another good use that I found for Text Replacement is to override Apple's default emojis, for example:

- For <kbd>:)</kbd>, I've changed the default 'smile' emoji from the 'smiling face with squinting eyes', that's slightly blushing (😊) to the simpler 'slightly smiling face' (🙂)
- For <kbd>:D</kbd>, change the default 'grinning face with open mouth' (😃) to the less eager looking 'grinning face with squinting eyes' (😄)

On top of that, I mapped a few custom emojis to emoticons that aren't part of Apple's default replacements, like:

- <kbd>;P</kbd> for 'winking face with stuck-out tongue' (😜)
- <kbd>:o</kbd> for 'surprised face with open mouth' (😮)
- <kbd>:\\</kbd> for 'confused face' (😕)

(If you're thinking of following suite, be sure to use a backslash for that last one -- a forward slash will start getting in the way when typing the <kbd>https://</kbd> part of URLs!)

And finally, I've got a bunch text shortcuts set up to output frequently used emojis that don't visually map to combinations of colons, semicolons, brackets, etc., such as:

- <kbd>llike</kbd> for 'thumbs up' (👍)
- <kbd>ffav</kbd> for 'star' (⭐️)
- <kbd>hheart</kbd> for 'blue heart' (💙), which I tend to use instead of a red heart, not for any other reason than I prefer blue!
- <kbd>ssob</kbd> for 'loudly crying face' (😭)


## ASCII art

Emojis are great, but sometimes ASCII art feels like the right response:

- <kbd>sshrug</kbd> to output a shrugging character: <code>¯\\_(ツ)_/¯</code>
- <kbd>fflip</kbd> to output a character flipping a table over: <code>(╯°□°）╯︵ ┻━┻</code>
- <kbd>sserious</kbd> to output a serious face: <code>(ಠ_ಠ)</code>


## Symbols

I remember the first time I saw someone on Slack using carat symbols (<code>^</code>) as upward-pointing arrows to refer to the message above. I wasn't sure what the carats were for until I eventually asked; I get that it's easier to user <kbd>shift</kbd> + <kbd>6</kbd> than to search for an upwards-pointing arrow, but I prefer that people know what the symbols I use mean without having to ask, so I use much more explicit arrows. These are more difficult to find, so I mapped:

- <kbd>uup</kbd> to output <code>↑</code>
- <kbd>rright</kbd> to output <code>→</code>
- <kbd>ddown</kbd> to output <code>↓</code>
- <kbd>lleft</kbd> to output <code>←</code>

And I've got a bunch of semi-often used symbol characters set up too; stuff like:

- <kbd>hhalf</kbd> to output <code>½</code>
- <kbd>ttm</kbd> to output <code>™</code>
- <kbd>ccommand</kbd> to output <code>⌘</code>
- <kbd>nnumber</kbd> to output <code>№</code>


## Syncing

As I mentioned, I used to have TextExpander on my Mac and Text Replacement on my phone. Any time I wanted to add, remove or change something that would be useful on both devices, I'd have to update both.

Moving to Text Replacement only was going to make that easier, or at least it was *supposed* to. In fact, it [didn't sync across devices](https://www.macstadium.com/blog/science-confirmed-text-replacements-do-not-sync) at first.

Happily, Apple made a change in late 2017 that meant [the snippets *did* sync](https://daringfireball.net/linked/2017/12/04/stucki-text-replacement-syncing), and that more or less coincided with TextExpander's monthly billing, so I took the plunge and went all-in with Apple's Text Replacement.

I had some heart-in-mouth issues for the first year or so though: whenever I updated my Mac's operating system (even just security patches) it would remove all of the snippets from my mac, replacing them with the defaults. After some searching, I found that [deleting the Text Replacement cache](https://discussions.apple.com/thread/7882683?answerId=31852903022#31852903022) pulled the saved snippets down from iCloud, putting things back they way they should be.

For the last year or more, Text Replacement syncing has worked as expected without any need to clear the cache, and I couldn't be happier!


## Missing functionality not an issue

While I didn't use TextExpander to anywhere near its full potential, I did use one or two smart features, for example:

- I mapped <kbd>ddate</kbd> to output the current date in the format I use most (<code>YYYY-MM-DD</code>)
- reuse content from other snippets (like variables) that can be included in other snippets

Other features like using the contents of the clipboard, form-filling with templates, sharing snippets across a team, etc., either weren't useful or just never really caught on for me.

I still miss my <kbd>ddate</kbd> shortcut a wee bit, but overall I'm happier not to be paying for something that I only make very light use of.
