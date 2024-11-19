---
title: Avatars and alt text
intro: I really enjoyed Nicolas Steenhout's recent article on Alt text for avatars or user photos. But there is a context where I would break his rule…
date: 2024-11-19
tags:
    - Accessibility
---

I really enjoyed Nicolas Steenhout's recent article on [Alt text for avatars or user photos](https://nicolas-steenhout.com/alt-text-for-avatars/). He highlights the importance of ensuring users are provided with the same information visually and non-visually via HTML, saying:

> User photos and avatars are informational images. Do use clear and concise alternate text to describe them. Otherwise blind screen reader users won't have access to the same information as sighted people.

Agreed! So why am I writing this? Well, there is a context in which user avatars should be:

- presented visually
- hidden from assistive technology like screen reader software

Where I work there are lots of lists; some present bank transactions, some payslips, and some present people. Many of those lists of people include their avatar photo, which is a great visual hooks for people glancing through the list. But is it useful to convey the same information to blind screen reader users?

As long as the avatars sit alongside the person's name and a bunch of other information, all of which is enough to identify the person, I'd say the description of the person is superfluous.

A blind screen reader user should get the same *experience* as a sighted user: fly through that list to quickly identify each person until they land on the one they're after. But if they get exactly the same *content* it could be a worse experience.

So, in this context, I'd use an empty 'alt' tag for each image, like this:

```html
<li>
    <img src="martin-underhill.jpg" alt="" />
    <span class="name">Martin Underhill</span>
    <!-- Rest of the information -->
</li>
```

The alternative would be unnecessary information in our context:

```html
<li>
    <img src="martin-underhill.jpg" alt="A friendly looking man with a bald head, beard, and glasses." />
    <span class="name">Martin Underhill</span>
    <!-- Rest of the information -->
</li>
```

Of course, as Nicolas says, describe the avatar photos in other places, like each person's profile page. But, just as an icon that is paired with some text can provide a visual hook only and should be hidden from assistive technology, a user avatar or photo is sometimes only for sighted users.
