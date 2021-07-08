---
title: Sass mixins for Increased Contrast Mode (and Dark Mode)
intro: |
    When I added a high contrast version of my website I used an almost-identical Sass mixin to the one I use for Dark Mode. Here's how it works.
date: 2021-06-22
tags:
    - CSS
    - Development
---

When I [added a high contrast version of my website](/blog/using-the-increased-contrast-mode-css-media-query) I used an almost-identical [SCSS (Sass) mixin](https://sass-lang.com/documentation/at-rules/mixin) to the one I use for Dark Mode. It's a riff on [the mixin Will Moore from 1Password wrote about](https://blog.1password.com/from-dark-to-light-and-back-again/) in late 2018, and here's how it looks:
 
```scss
@mixin high-contrast($background: null, $colour: null) {

  @media screen and (prefers-contrast: more) {

    @if ($background != null and $colour != null) {
      background-color: $background;
      color: $colour;
      @content;
    }
    @else if ($background != null and $colour == null) {
      background-color: $background;
      @content;
    }
    @else if ($colour != null and $background == null) {
      color: $colour;
      @content;
    }
    @else {
      @content;
    }
  }
}
```

This allows us to do any of the following:

1. Style the text colour only:
    ```scss
    @include high-contrast(black, white);
    ```
2. Style the background only:
    ```scss
    @include high-contrast($background: black);
    ```
3. Style the background and text colour:
    ```scss
    @include high-contrast($colour: white);
    ```
4. Style something other than background and text colour:
    ```scss
    @include high-contrast() {
      border-color: white;
    }
    ```
5. Style the background, text colour and something else:
    ```scss
    @include high-contrast(black, white) {
      border-color: white;
    }
    ```
6. Style the background and something else:
    ```scss
    @include high-contrast($background: black) {
      border-color: white;
    }
    ```
7. Style the text colour and something else:
    ```scss
    @include high-contrast($colour: white) {
      border-color: white;
    }
    ```

Pretty handy!

I mentioned I do the same for Dark Mode, and that mixin is almost identical; it's just the name of the mixin and the media query that's called that change:

```scss
@mixin dark-mode($background: null, $colour: null) {

  @media screen and (prefers-color-scheme: dark) {

    @if ($background != null and $colour != null) {
      background-color: $background;
      color: $colour;
      @content;
    }
    @else if ($background != null and $colour == null) {
      background-color: $background;
      @content;
    }
    @else if ($colour != null and $background == null) {
      color: $colour;
      @content;
    }
    @else {
      @content;
    }
  }
}
```

The `@includes` work exactly the same, substituting `high-contrast` for `dark-mode`.
