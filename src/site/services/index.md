---
title: How I can help
intro: Accessibility and design services for teams that care about inclusive, user-friendly digital products; from strategic support to hands-on help.
permalink: services/index.html
override:tags: false
---

I work with organisations of all sizes to make their digital products more accessible and user-friendly. Whether you need strategic direction, a specialist embedded in your team, or a website built with accessibility in mind, I offer a few ways of working:


<ul>
{%- for service in collections.service %}
    <li>
        <a href="{{ service.url  | replace(".html", "") }}">
            {{ service.data.title }}
        </a>:
        {{ service.data.intro | smart | safe }}
    </li>
{%- endfor %}
</ul>
