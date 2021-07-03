---
title: Styling code snippets in Keynote
intro: |
    No sooner than I had published my story on why I like Keynote, I spotted a post in my RSS feeds on how to highlight code syntax in Keynote.
date: 2021-06-15
tags:
    - Apple
---

Just after I published my story on [why I like Keynote](/blog/id-forgotten-how-much-i-like-keynote), I was catching up on my RSS feeds and spotted a post from Sara Soueidan about [how to highlight code syntax in Keynote](https://www.sarasoueidan.com/blog/copy-paste-from-vscode-to-keynote/).

There's nothing built into the app, so, unless you want to select various parts of the code text yourself and change the font colour, there are essentially three options:

1. Take a screen shot of some already-syntax-highlighted code from your text editor, then drop it into Keynote
2. Copy from VS Code with the `copyWithSyntaxHighlighting` setting on, then paste into Keynote
3. Copy from [Slides Code Highlighter](https://romannurik.github.io/SlidesCodeHighlighter/), then paste into Keynote

Option 1 is what I've always used, but it's a pain because:

- text sizes are fiddly to match up
- crop boundaries (essentially the padding/frame around the code) are manual, and therefore almost always slightly inconsistent
- if you make a change, you've got to take another screenshot

Option 2 is out as I'm still using [Sublime Text](/blog/still-a-sucker-for-sublime), and there's no equivalent of VS Code's `copyWithSyntaxHighlighting`.

Option 3 works quite nicely; it's still *much* easier than styling it all myself and it allows me to make edits easily.

I've got my 'Code' slide template set up to add a dark background and same-coloured border (padding/frame) to the code text box, but there are there are still a couple of manual steps once I've pasted the code in from Slides Code Highlighter:

- For some reason, the pasted text gets a white (`#ffffff`) 'Text background' value, so that has to be made transparent again
- [I like the Operator Mono typeface](/blog/operator-mono-and-why-i-want-italics-in-my-code-editor) for my snippets, so I change that

Those corrections/adjustments are really easy to do, so I'm happy to make them, though as Sara says:

> none of these approaches are as convenient as having built-in syntax highlighting in Keynote

The Slides Code Highlighter technique is a tolerable workaround, so I'll run with that until Apple adds a syntax highlighting to Keynote. But I'm not holding my breath.

---

Here's the Slides Code Highlighter custom theme I threw together to match the colours on my website:

```json
{
  "bgColor": "#1a1a1c",
  "textColor": "#f2f2f2",
  "punctuationColor": "#adacaf",
  "stringAndValueColor": "#ffe648",
  "keywordTagColor": "#ff508c",
  "commentColor": "#adacaf",
  "typeColor": "#00d7e9",
  "numberColor": "#ffe648",
  "declarationColor": "#9786e9",
  "dimmedColor": "#adacaf",
  "highlightColor": "#ff508c",
  "lineHeight": 1.5
}
```

