---
title: Downloading a website as HTML files
intro: |
    How do you download a website as HTML, including the CSS, JavaScript files, and image assets? Wget is the easiest way to do it; here's what I do.
date: 2021-07-08
tags:
    - Development
    - Tools
---

This is another one of those notes-to-self blog posts, for something I do every now and then, but not enough that I know how to do it off the top of my head.

I've been moving the handful of the excellent clients I still do freelance work for from [Perch](https://grabaperch.com), my previous go-to tool to build a site with, to new platforms. Most of them have moved to a statically built website where I act as [Webmaster](/blog/lets-make-webmasters-a-thing-again):

- writing only the code that needs to be written
- not putting in extra work to design the content management system (CMS) experience
- ensuring content is marked up correctly
- questioning updates where necessary
- keeping them accountable for content updates

The place I usually start when rebuilding a site statically is to download the entire outgoing website as the rendered HTML pages. I then [host the site to a new platform](/blog/moving-to-netlify) (almost always [Netlify](https://www.netlify.com)) and systematically refactor the site in a new framework (normally [Eleventy](https://www.11ty.dev)). This allows me to work steadily over the course of a few weeks, which is important now that I'm not freelancing full time, so I can spread the work over evenings and weekends.

But how do you go about downloading a whole website? Turns out it's quite easy using [Wget](https://www.gnu.org/software/wget/).


## Getting set up

First up, installing Wget. There are [lots ways to install it](http://wget.addictivecode.org/FrequentlyAskedQuestions.html#download), but [I did it with Homebrew](https://formulae.brew.sh/formula/wget) with:

```bash
brew install wget
```

I then opened Terminal and navigated to the directory I wanted to download the site into.

It's worth noting that the site download will be bundled in a folder, so you don't need to be too careful for fear that the root directory files and folders will be dumped in your location, mixing with any existing files and folders in there. This makes it easy to throw out if you need to tweak the configuration options.


## Running Wget

Then it's just a case of running Wget with those options I mentioned:

```bash
wget --recursive --domains=www.example.com --page-requisites --adjust-extension www.example.com
```

Here's what's going on with that command:

- Download every page of the website (`--recursive`)
- Don't follow any links outside of the website (`--domains www.example.com`)
- Download all of the assets, like images, CSS, JavaScript, etc. (`--page-requisites`)
- Add the .html extension to all HTML files (`--adjust-extension`), even if the website files don't have an extension or use something else like .php
- Finish with the URL to download (`www.example.com`)

It's worth mentioning that there are shorthand versions for all of these; here's how it would look:

```bash
wget -r -D example.com -p -E www.example.com
```

I don't use Wget enough to commit those to memory, so I prefer the more descriptive method so that I know what's going on without referencing the documentation.

### Run locally without a server

If you prefer not to upload the website to a server straight away, and view it on your machine, you probably don't want to go to the extra hassle of running a server locally. There's an option (`--convert-links` or `-k`) to rewrite all internal URLs so that they're relative, rather than absolute or root relative, which allows you to simply open a website file in your browser and navigate around:

```bash
wget --recursive --domains=www.example.com --page-requisites --convert-links --adjust-extension www.example.com
```

### Downloading a specific directory

To download a specific area of the website, just the blog, for example, you can add it to the URL and add `--no-parent` (`-np`) just before the value:

```bash
wget --recursive --domains=example.com --page-requisites --convert-links --adjust-extension --no-parent www.example.com/blog
```

### Downloading from multiple locations

If any assets are served from a different server/domain (maybe your images are on a CDN), you can add it to your `--domains` list like this:

```bash
wget --recursive --domains=www.example.com,exampleimages.cdn.com --page-requisites --convert-links --adjust-extension www.example.com
```

### Windows URL compatibility

To make sure the URLs work on Windows, add `--restrict-file-names=windows`.

### Loads more options

There are a load of [options I haven't mentioned](https://www.gnu.org/software/wget/manual/wget.html#Download-Options), but I find the first command on this page usually gets me what I want.

