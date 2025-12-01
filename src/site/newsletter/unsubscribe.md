---
title: Unsubscribe from the newsletter
intro: |
    Every tempertemper newsletter contains a link to unsubscribe. Just dig out any email I've sent you and you can remove yourself from the list.
permalink: /newsletter/unsubscribe.html
hideIntro: false
override:tags: false
layout: default
---

<form
    class="unsubscribe"
    id="unsubscribe"
    name="unsubscribe"
    action="/newsletter/unsubscribed"
    method="POST"
    data-netlify="true"
    netlify-honeypot="bot-field"
>
    <input type="hidden" name="form-name" value="unsubscribe">
    <div hidden>
        <label>
            Don’t fill this out if you’re human:
            <input name="bot-field" tabindex="-1" />
        </label>
    </div>
    <div class="input-group">
        <label for="email">Email address</label>
        <input
            id="email"
            name="email"
            type="text"
            inputmode="email"
            autocomplete="email"
            autocapitalize="none"
            autocorrect="off"
            spellcheck="false"
            aria-required="true"
        />
    </div>
    <button type="submit">Unsubscribe</button>
</form>

<p>If this form does not work for any reason, please <a href="/contact">get in touch</a> and I will remove your email manually.</p>
