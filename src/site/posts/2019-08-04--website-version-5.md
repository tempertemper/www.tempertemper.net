---
title: Website version 5
intro: |
    I'm already more than a dozen releases into version 5 of my website, but I'm finally ready to 'officially' announce it!
date: 2019-08-04
tags:
    - Development
    - Design
summaryImage: version-5.png
summaryImageAlt: The number 5, set in very dark grey FS-Me light against a white background.
---

I'm already more than a dozen releases into version 5 of my website, but I'm finally ready to 'officially' announce it!

Towards the end of last year I decided to rebuild my website with a static site generator, rather than using a database-driven content management system. I had zero complaints about how my website was previously built, using the excellent [Perch CMS](https://grabaperch.com), but I felt I needed to stretch some coding muscles and freshen things up behind the scenes.

With a static site generator you get all the advantages of a CMS -- templates, data reuse, code includes, conditionals, etc., but there are *even more*:

- <b>Secure</b>: no database means nothing to hack -- it's just HTML!
- <b>Very fast</b>: no code to run when each webpage is loaded, and no database to query
- <b>All changes are tracked in git</b>: database backups sitting alongside source control always felt a bit clunky

Another nice side-effect has been that it has streamlined my [blog writing process](/blog/refining-my-writing-process).


## Choosing the right tool

My mission was to rebuild my website **like-for-like** so I knew that Jekyll wasn't the tool to use -- I've used it a lot over the years and I knew there would be a few places where I'd hit up against its limitations. Plus I didn't really want the Ruby dependency; for that reason [Hugo](https://gohugo.io/) was out too -- I wanted to keep it all JavaScript.

[Gatsby.js](https://www.gatsbyjs.com/) and [Vuepress](https://vuepress.vuejs.org/) were the front-runners until I came across [Eleventy](https://www.11ty.dev/). It's logical to use, light-weight, very flexible, very fast, and vanilla JavaScript all the way down.


## Some things have gone

There were also a few compromises I had to make as I was building the site, mainly due to time (rather than technical) constraints, and most temporary.

### No more forms

I saw this one coming. Without some kind of server side processing forms are impossible. The primary focus of my site is no longer getting work, so I don't mind replacing my contact form with a `mailto:` link.

My site is currently self-hosted on [Linode](https://www.linode.com/?r=b92d6fedd4c0b5608f758fa6becbba975ea10e7b) but I'm curious about Netlify and its [built-in form handling](https://docs.netlify.com/forms/setup), so adding forms back in is definitely on my to-do list.

### No more search

Another thing I don't mind losing at this stage is search. Rebuilding the search with JavaScript looks like a big (and interesting!) job; I didn't want to delay the launch any longer than I had to, so search is gone for now.

Reintroducing search will be interesting from a design as well as development point of view -- it used to be global, but maybe it would be more useful if it were scoped to specific areas of the site, like the blog or resources. Let's see.


## Some things have changed

### Categories are global

Blog posts used to have their own categories; resources too. I hit up against some technical challenges that were proving too time consuming to pull off without delaying things too much. But after giving it some thought, it dawned on me that the make-up of my new site is different: if someone wants to see articles on accessibility, for example, they're likely to want to see resources (and talks and videos, when they start appearing) on the same topic.


## Some things have improved

### IDs on headings

Since the new set up is fully customisable I can do things like generate `id`s on heading tags, using a slug based on the heading itself. That's without modifying the core application -- all through configuration.

So readers can link to an article's sub-heading using a fragment identifier like `https://www.tempertemper.net/blog/website-version-5#ids-on-headings`. I haven't exposed them in the UI though so they're just a nice hidden extra for developers, using their browser's inspector.

### Public repository

Since there're no passwords or keys in config, and no closed-source code in the new build of my site (I've made sure [none of that code](/blog/changing-your-git-history) is available in my repo's history), the GitHub repo [is now public](https://github.com/tempertemper/tempertemper.net). So if you're curious how I've put it all together you can have a look! You can also suggest a change to a page via the 'Edit this page on GitHub' link in every footer.

### Better code syntax highlighting

I've been writing (and plan to write more) articles about development, which means code examples. I've used [Prism.js](https://prismjs.com/) to add the right classes to my code [when the site is built](https://github.com/11ty/eleventy-plugin-syntaxhighlight), rather than letting the browser do it. I've customised the [A11y Dark theme](https://github.com/PrismJS/prism-themes/blob/master/themes/prism-a11y-dark.css) and I think it looks rather nice! All-in-all, much more readable than plain white code on a black background.

### Loads more refinements

I've implemented a whole host more changes, each of which probably warrants a blog post in itself:

- Countless accessibility fixes
- Rebuilt RSS (Atom) and JSON feeds
- Lots of subtle typography refinements
- Better bookmark icons and favicons
- Content revisions pretty much everywhere


## Moving on to other things

I'm at a point where I'm happy with the website. As I mentioned, there are still hefty things like adding search and forms, but I'm happy for those to wait.

For what seems like the longest time, I've been itching to get to grips with putting some videos together for [my YouTube channel](https://youtube.com/tempertemper). I'm looking forward to the learning curve -- getting to grips with the shooting and composition, then editing it all together. I have a good place to start too -- I've been giving a series of hour-long workshops on HTML fundamentals where I work, and those'll chunk down beautifully into lots of 10 minute tutorials.
