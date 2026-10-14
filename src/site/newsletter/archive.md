---
title: Newsletter archive
intro: |
    An archive of my monthly newsletter, from the first one I published back in August 2020.
permalink: /newsletter/archive.html
hideIntro: false
override:tags: false
layout: default
---

{%- set years = collections.newsletter | getYears %}
{%- for year in years | reverse %}
<h2>{{ year }}</h2>
<ol class="index-list" reversed>
    {%- set yearList = collections.newsletter | filterByYear(year) %}
    {%- for newsletter in yearList | reverse %}
    <li><a href="/newsletter/{{ newsletter.page.fileSlug }}">{{ newsletter.data.title }}</a></li>
    {%- endfor %}
</ol>
{%- endfor %}
