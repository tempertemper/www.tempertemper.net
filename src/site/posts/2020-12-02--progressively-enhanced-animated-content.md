---
title: Progressively enhanced animated content
intro: |
    Respecting your users' preferences with `prefers-reduced-motion` is great, but what about users with older operating systems and browsers?
date: 2020-12-02
tags:
    - Development
    - Accessibility
---


I've read a lot of articles about using `prefers-reduced-motion` on the web; I've even [written one of them](/blog/reducing-motion)!

I'm a big fan of [Patrick H. Lauke's progressively enhanced approach](https://codepen.io/patrickhlauke/pen/YzPPdeo) where instead of telling the browser "here's an animated image, but don't show it if the user has set reduced motion on their system", we're only serving the animation if:

- the user has an operating system and browser that is able to reduce motion
- the user hasn't turned on the reduced motion setting

So instead of this SCSS, that I use to make the underscore on my logo blink:

```css
.underscore {
  animation: blink 2s steps(20, start) infinite;

  @media screen and (prefers-reduced-motion: reduce) {
    animation: none;
  }
}
```

We'd write this:

```css
@media screen and (prefers-reduced-motion: no-preference) {
  .underscore {
    animation: blink 2s steps(20, start) infinite;
  }
}
```

The crucial thing here is that users who can't opt in to reducing animation _won't get the animation_, where previously they got it whether they wanted it or not. Not very accessible!

The great news is that this approach extends to use within the `<picture>` element for animated content, as opposed to CSS-housed decoration. So, building on [code from a Brad Frost blog post](https://bradfrost.com/blog/post/reducing-motion-with-the-picture-element/):

```html
<picture>
    <source srcset="animated.gif" media="(prefers-reduced-motion: no-preference)"></source>
    <img srcset="static.jpg" alt="Description of the image" />
</picture>
```

I know we might be depriving some users of our lovely animations, but if we *can't be sure* a user won't have a bad experience with motion, we should be serving up static content.
