---
title: Newsletter archive
intro: |
    Blah
permalink: /newsletter/archive.html
override:tags: false
layout: default
---

An archive of my monthly newsletter, from the first one I published back in August 2020.

<ol class="index-list" reversed>
    {%- for newsletter in collections.newsletter | reverse %}
    <li><a href="/newsletter/{{ newsletter.page.fileSlug }}">{{ newsletter.data.title }}</a></li>
    {%- endfor %}
</ol>
