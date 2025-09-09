---
title: How we can work together
intro: "Flexible ways to work with me: consultancy, contracting, or projects; tailored support to make your digital products more accessible."
permalink: approaches/index.html
override:tags: false
cta: false
---

Every organisation has different needs. Sometimes you need strategic advice, sometimes an extra pair of hands, and sometimes a defined project delivered end-to-end. I offer three flexible approaches so we can work in the way that best suits you.

<div class="highlight-boxes">
    <ul>
    {%- for approach in collections.approach | ordered %}
        <li>
            <h2>
                <a href="{{ approach.url  | replace(".html", "") }}">
                    {{- approach.data.title -}}
                </a>
            </h2>
            {{ approach.data.intro | smart | safe }}
        </li>
    {%- endfor %}
    </ul>
</div>
