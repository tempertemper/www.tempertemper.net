---
title: Where I've spoken
customURL: events
intro: |
    I've [spoken](/speaking/) at conferences, meetups, internal events, and on podcasts across the UK and online. Here's what I've covered, with recordings where available.
---

{% set talks = [
    {
        date: "2026-07-01",
        event: "GOV.UK Design, Services Week",
        title: "Beyond components: creating accessible experiences"
    },
    {
        date: "2026-05-13",
        event: "Access:Given",
        title: "Beyond components: creating accessible experiences",
        url: "https://youtu.be/Og-4YqHjn1s"
    },
    {
        date: "2026-01-20",
        event: "Ministry of Testing Newcastle",
        title: "Mobile accessibility testing"
    },
    {
        date: "2025-10-22",
        event: "Design for All",
        title: "Insights from Martin Underhill, Accessibility Lead",
        url: "https://bristolcreativeindustries.com/design-for-all-feat-martin-underhill/"
    },
    {
        date: "2025-01-21",
        event: "Ministry of Testing Newcastle",
        title: "Accessibility empathy lab",
        url: "https://www.ministryoftesting.com/events/newcastle-meetup-21-january-2025"
    },
    {
        date: "2024-10-30",
        event: "Sage XD Coffee Chats",
        title: "Accessibility at Sage",
        url: "https://www.youtube.com/watch?v=svRwzyid3Gs"
    },
    {
        date: "2024-05-30",
        event: "Natter Manchester",
        title: "Creating a culture of accessibility",
        url: "https://www.youtube.com/watch?v=rMFoX0gzLfA"
    },
    {
        date: "2024-05-16",
        event: "Sage Global Accessibility Awareness Day (GAAD)",
        title: "Five things you need to know about HTML"
    },
    {
        date: "2024-01-23",
        event: "Ministry of Testing Newcastle",
        title: "Getting started with manual accessibility testing"
    },
    {
        date: "2023-07-20",
        event: "NUX Newcastle",
        title: "Creating a culture of accessibility",
        url: "https://nuxuk.org/2023/07/09/nux-newcastle-20-july-2023-creating-a-culture-of-accessibility/"
    },
    {
        date: "2023-05-19",
        event: "Sage XD Coffee Chats",
        title: "Accessibility doesn't just mean WCAG compliance",
        url: "https://www.youtube.com/watch?v=c_DBVuu-MNgc"
    },
    {
        date: "2023-05-18",
        event: "Sage Global Accessibility Awareness Day (GAAD)",
        title: "Accessibility doesn't just mean WCAG compliance"
    },
    {
        date: "2022-05-19",
        event: "Sage Global Accessibility Awareness Day (GAAD)",
        title: "Introduction to accessibility"
    },
    {
        date: "2021-05-05",
        event: "technica11y",
        title: "When and how to use the section element",
        url: "https://youtu.be/6YwUniqUJSM"
    },
    {
        date: "2018-11-21",
        event: "Sunderland Digital",
        title: "Accessible form patterns",
        url: "https://www.youtube.com/watch?v=v-Qwarwpsvc"
    },
    {
        date: "2018-09-27",
        event: "NUX Newcastle",
        title: "Accessible user interface patterns",
        url: "https://www.youtube.com/watch?v=JywNesobqB8"
    },
    {
        date: "2015-04-16",
        event: "Frontend NE",
        title: "Speedy, solid, semantic layout with Susy",
        url: "https://www.youtube.com/watch?v=-jh0rHHvIzw"
    },
    {
        date: "2014-02-17",
        event: "Super Mondays",
        title: "The importance of web standards"
    },
    {
        date: "2013-07-03",
        event: "Clavering House Business Centre, Newcastle",
        title: "FreeAgent and my sanity"
    },
    {
        date: "2013-06-05",
        event: "Clavering House Business Centre, Newcastle",
        title: "Evernote and the paperless office"
    },
    {
        date: "2012-10-20",
        event: "CAM Expo, Earls Court London",
        title: "Make the most of your website!"
    },
    {
        date: "2012-03-05",
        event: "Professional Beauty, Excel London",
        title: "Improve your business by communicating online"
    },
    {
        date: "2011-10-22",
        event: "CAM Expo, Earls Court London",
        title: "Using the web to grow your business"
    }
] %}

{% for yearGroup in talks | groupByYear %}
<h2>{{ yearGroup.year }}</h2>
<ol reversed class="index-list">
    {% for talk in yearGroup.items %}
    <li>
        <b>{{- talk.event }}</b>,
        <time datetime="{{ talk.date | isoDate }}">
            {{- talk.date | date('dayMonth') -}}
        </time>
        <br />
        {%- if talk.url %}<a href="{{ talk.url }}">{% endif %}
            {{ talk.title | smart | safe }}
        {%- if talk.url %}</a>{% endif %}
    </li>
    {% endfor %}
</ol>
{% endfor %}
