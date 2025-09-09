---
title: Resources for website owners
intro: Do you have a website? Not the technical type? Read on to receive free tips, useful to anyone looking to get the most out of their website.
layout: default
resources: true
noCta: true
permalink: resources/index.html
override:tags: false
---

I tend not to write much here anymore. For articles on design, frontend development, and accessibility, [head to my blog](/blog/).

<ol class="hfeed index-list" reversed>
    {%- for post in collections.resource | reverse %}
        {%- include "post-in-list.html" %}
    {%- endfor %}
</ol>
