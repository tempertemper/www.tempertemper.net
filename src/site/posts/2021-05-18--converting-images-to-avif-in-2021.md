---
title: Converting images to AVIF in 2021
intro: |
    AVIF is amazing, but the big downside is that it's not an export option in any of my image software yet. Here's what I'm doing in the meantime.
date: 2021-05-18
tags:
    - Development
    - Performance
---

I've covered how great [AVIF image compression](/blog/avif-image-compression-is-incredible) is, but the big downside (for now, anyway) is that it isn't offered as an export option in any of the image software I use.

A quick search reveals [GIMP can export as AVIF](https://avif.io/blog/tutorials/use-avif-in-gimp/) and [there's a Figma plugin](https://www.figmatic.com/tutorials/how-to-export-avif-images-from-figma-using-tiny-image/), but I can't find much else.

I found a [web tool called avif.io](https://avif.io) that converts images to AVIF via a file uploader. It does it all locally using your browser too, rather than crunching the images on their server, which is neat. But it's a simple in/out tool so you don't get any control over the export quality and file size.

The method I'm using to convert my PNGs and JPEGs to AVIF is a command line tool called [ImageMagick](https://imagemagick.org), which I have installed via [Homebrew](https://brew.sh). Like avif.io, it's not as slick as it would be using an app's visual export functionality, but it's fine for now; especially as I've decided to [use my already-exported JPG or PNG as the base for the AVIF export](/blog/avif-image-compression-is-incredible#what-im-doing).



## Converting a single image

Converting single images is what I'll be doing most often, as:

- I use images pretty sparingly in my blog posts
- When I do use an image, there's usually only one

ImageMagick is pretty straightforward for single images:

```bash
convert my-great-image.png my-great-image.avif
```

I've use the ImageMagick `convert` command, followed by the name of the the image to convert (including its extension), then the name of the new image. The `.avif` file extension is what tells ImageMagick the export format.

Running that command assumes we're in the same directory/folder as the PNG (or JPG) we're using as our base; it also creates the new image in the same directory, which is probably what we're after. You can specify the 'from' and/or 'to' image directories if you prefer:

```bash
convert src/img/my-great-image.png src/img/my-great-image.avif
```

<i>Note: you can also use the `magick` command, but `convert` feels better as it's more of an action; also, I sometimes misspell 'magick'!</i>


## Converting a bunch of images at once

[When I added AVIF images to my website](https://github.com/tempertemper/tempertemper.net/pull/646), I had a lot of older images to convert, especially for [my case studies](https://www.tempertemper.net/portfolio/), which tend to use image more than blog posts. I wanted to create AVIF images from all of the PNGs in a folder, but I didn't want to have to run a command on each of them. Here's how I converted a batch of images to AVIF:

```bash
for image in *.png ; do convert "$image" "${image%.*}.avif" ; done
```

This looks for all of the PNGs in a directory and creates an AVIF file based on each, with the same file name, save for the `.avif` file extension.

Again, I had navigated to the directory that contained the files I wanted to convert, but I could equally have specified the full path if I hadn't been:

```bash
for image in src/img/*.png ; do convert "$image" "${image%.*}.avif" ; done
```
