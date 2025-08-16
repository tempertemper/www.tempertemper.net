---
title: My skills
intro: I bring together frontend development, user experience design, and accessibility to help teams build thoughtful, inclusive digital products.
permalink: skills/index.html
override:tags: false
---

My skills in frontend development, user experience design, and accessibility overlap and support each other. I help teams build inclusive, user-friendly digital products by combining solid implementation with considered design and a deep understanding of accessibility from the outset.

<ul>
{%- for skill in collections.skill %}
    <li>
        <a href="{{ skill.url  | replace(".html", "") }}">
            {{ skill.data.title }}
        </a>:
        {{ skill.data.intro | smart | safe }}
    </li>
{%- endfor %}
</ul>
