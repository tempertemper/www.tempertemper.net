---
title: Newsletter archive
intro: |
    An archive of my monthly newsletter, from the first one I published back in August 2020.
permalink: /newsletter/archive.html
hideIntro: false
override:tags: false
layout: default
---

<ol class="index-list" reversed>
    {%- for newsletter in collections.newsletter | reverse %}
    <li><a href="/newsletter/{{ newsletter.page.fileSlug }}">{{ newsletter.data.title }}</a></li>
    {%- endfor %}
</ol>
