---
title: Using Pandoc to convert Markdown to Jira's Textile in Sublime Text
intro: |
    It's a real pain that Jira's plain text input isn't Markdown. Here's how to I write in Markdown and export to Jira's version of Textile.
date: 2021-07-22
updated: 2021-07-28
tags:
    - Development
    - Workflows
    - Markdown
    - Tools
summaryImage: pandoc.png
summaryImageAlt: The Markdown logo, a capital ‘M’ and an arrow pointing down, and the Jira logo, a diamond made of two interlocking left and right pointing arrow-heads, followed by the word ‘Jira’.
---

I use [Jira](https://www.atlassian.com/software/jira) a fair amount at work. It's a very powerful software package but, unlike most of Atlassian's other products which use [Markdown](/resources/what-is-markdown), Jira uses creaky old Textile; in fact it goes further than that: it uses [its own variant of creaky old Textile](https://jira.atlassian.com/secure/WikiRendererHelpAction.jspa?section=all)!

Unsurprisingly, I don't write in Textile day-to-day:

- It's an ancient format
- It's more difficult to read than Markdown
- It does too much ([alignment and other styling](https://textile-lang.com/category/attributes/), too many [text level semantics](/blog/be-careful-with-strikethrough))
- It's far from ubiquitous

On top of that, I prefer to write my tickets in [my favourite text editor, Sublime Text](/blog/still-a-sucker-for-sublime), and copy/paste the finished ticket into Jira because:

- The tickets I write are often very long, and it isn't fun to type directly in a `<textarea>`
- I can take a while to write a ticket, meaning Jira could time out and I lose my work
- If it's an existing ticket, there's a risk that someone else may edit the ticket description at the same time, causing a clash/conflict
- There's no syntax highlighting in Jira's editor

There don't seem to be any decent Textile packages for Sublime Text (or VS Code, as far as I can tell) along the lines of [the excellent MarkdownEditing](https://sublimetext-markdown.github.io/MarkdownEditing/) and, since I prefer to write in Markdown anyway, I decided to explore the best way to:

1. Write in Markdown
2. Export my work to a Textile document (ideally the Jira variant)
3. Copy and paste the Textile code into Jira

Reader, I found it! The software that converts my Markdown documents to Jira Textile is called [Pandoc](https://pandoc.org).


## Install Pandoc

First, we need to install the [Sublime Text package](https://packagecontrol.io/packages/Pandoc), which is easy enough if you use [Package Control](https://packagecontrol.io):

1. Open the command palette with <kbd>cmd</kbd> + <kbd>shift</kbd> + <kbd>p</kbd>
2. Type <kbd>install</kbd>
3. Choose 'Package Control: Install Package'
4. Type <kbd>pandoc</kbd>
5. Choose the Pandoc package that installs from `https://github.com/tbfisher/sublimetext-Pandoc`

Next we need to install Pandoc system-wide; [as with Wget](/blog/downloading-a-website-as-html-files), I like to use [Homebrew](https://docs.brew.sh/Installation):

```bash
brew install pandoc
```

<i>This is for macOS; the ways to [install Pandoc on Windows or operating systems](https://pandoc.org/installing.html) is different.</i>


## Configure Pandoc to convert Markdown to Jira's Textile

Neither Textile nor the Jira's version of Textile appear in Pandoc's default formats, so we have to add some config for that ourselves. In Sublime Text, go to:

1. 'Sublime Text' in the menu bar
2. 'Preferences'
3. 'Package Settings'
4. 'Pandoc'
5. 'Settings – User'

This opens `~/Library/Application Support/Sublime Text 3/Packages/User/Pandoc.sublime-settings`.

Paste the following into the empty file and save it:

```json
{
  "user": {
    "transformations": {
      "Jira Textile": {
        "new-buffer": 1,
        "scope": {
          "text.html.markdown": "markdown"
        },
        "pandoc-arguments": [
          "--from", "markdown-auto_identifiers",
          "--to", "jira"
        ]
      }
    }
  }
}
```

Specifying the `user` object means that we don't overwrite any default settings. We then set up a `Jira` transformation that tells Pandoc to:

- Identify a Markdown document
- Convert it from Markdown to Jira Textile
- Open a new file tab in Sublime Text, containing the Textile code

<details>
<summary>How to export to classic Textile</summary>

To export to classic Textile, change the last item in the array from `"jira"` to `"textile"`; you'll probably want to change the name of the transformation from `"Jira Textile"` to `"Textile"` too.

```json
{
  "user": {
    "transformations": {
      "Textile": {
        "new-buffer": 1,
        "scope": {
          "text.html.markdown": "markdown"
        },
        "pandoc-arguments": [
          "--from", "markdown-auto_identifiers",
          "--to", "textile"
        ]
      },
      "Jira Textile": {
        "new-buffer": 1,
        "scope": {
          "text.html.markdown": "markdown"
        },
        "pandoc-arguments": [
          "--from", "markdown-auto_identifiers",
          "--to", "jira"
        ]
      }
    }
  }
}
```
</details>


## Convert a Markdown file to Jira's Textile

The first thing we need when converting a Markdown file to Textile is a Markdown document. Basically, any document that Sublime Text recognises as Markdown will do:

- A Markdown document, saved with the .md file extension
- An unsaved file that has been manually identified as Markdown via the syntax menu in:
    - The status bar's syntax select menu
    - Sublime Text's View menu

Once Sublime knows it's a Markdown document:

1. Open Sublime Text's Command Palette <kbd>cmd</kbd> + <kbd>shift</kbd> + <kbd>p</kbd>
2. Choose 'Pandoc'
3. Choose 'Jira Textile'

And that's how you can avoid having to write Jira tickets in Textile!

---

Huge thanks due to [Albert Krewinkel](https://twitter.com/kraut0xA) who pointed out that my original article didn't address Jira's version of Textile. Pandoc has an [option to export in `jira`](https://twitter.com/kraut0xA/status/1418916123874107393) as well as [plain old `textile`](https://textile-lang.com)!

In Jira's Textile, things like headings, lists, emphasis, and blockquotes are all the same, but certain useful things are different. I have to admit, I tended to avoid things like links and code where possible, or add them in afterwards, which was a pain:

- For links, Textile uses double quotes and a colon `"Example":https://www.example.com`; Jira uses braces and a pipe `[Example|https://www.example.com]`
- For inline code, Textile uses `@` symbols `@like this@`; Jira uses double braces {% raw %}`{{like this}}`{% endraw %}
- For code blocks, Textile uses `bc.` before the code block (or `bc..` and a `p.` to end it); Jira uses the more sensible `{code}` to start and end the code block

By exporting Jira's version directly, there's no extra work manually adding links and code, so I can use as many as I like!

