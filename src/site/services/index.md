---
title: How I can help
intro: Accessibility and design services for teams that care about inclusive, user-friendly digital products; from strategic support to hands-on help.
permalink: services/index.html
override:tags: false
cta: false
---

I work with organisations of all sizes to make their digital products, processes, and culture more accessible and user-friendly. Whether you need strategic direction, a specialist embedded in your team, or a clearly defined project, there are [different approaches](/approaches/) we can take.

The services I offer cover a range of needs, including accessibility audits, training and workshops, and strategic guidance.

<div class="highlight-boxes">
    <ul>
    {%- for service in collections.service | ordered %}
        <li>
            <h2>
                <a href="{{ service.url  | replace(".html", "") }}">
                    {{- service.data.title -}}
                </a>
            </h2>
            {{ service.data.intro | smart | safe }}
        </li>
    {%- endfor %}
    </ul>
</div>
