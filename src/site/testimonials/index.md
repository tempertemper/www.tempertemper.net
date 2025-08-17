---
title: Testimonials for Martin at tempertemper
heading: Compliments
intro: What people are saying about Martin Underhill at tempertemper
layout: default
permalink: testimonials/index.html
noCta: true
override:tags: false
---

<ul class="index-list" reversed>
    {% for testimonial in collections.testimonial | sort(true, false, 'date') %}
        <li>
            <h2><a href="{{ testimonial.url | replace(".html", "") }}">{{ testimonial.data.title }}</a></h2>
            <blockquote>
                &#8220;{{ testimonial.data.intro | markdown | safe  | striptags(true) }}&#8221;
            </blockquote>
        </li>
    {% endfor %}
</ul>
