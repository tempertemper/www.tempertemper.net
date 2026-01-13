---
title: Speaking
intro: I'm a UK-based accessibility consultant and speaker, available for conferences and meetups, both in person and remotely.
permalink: speaking/index.html
---

I deliver talks, workshops, and hands-on labs focused on practical accessibility. My work focuses on how accessibility fits into product development, with content tailored to designers, developers, testers, and product managers. I aim to build capability, confidence, and a shared responsibility, covering topics such as:

- Making sense of WCAG without losing sight of users
- Writing accessible interfaces with HTML, ARIA, and CSS
- Common accessibility pitfalls in the design of components and interactions
- Accessibility testing in practice, beyond automated tools
- Navigating accessibility trade-offs in real products

I’m also happy to tailor the topic to whatever accessibility-related area you’d like me to speak about.


## Bio

Copy and paste your preferred bio for event websites and programmes:

<p id="bio-short">
    Martin Underhill is an accessibility consultant with over a decade of experience working on accessible digital products. He has been writing and obsessing over semantic markup since 2002, and takes a pragmatic, real-world approach to accessibility shaped by years working on live products. Martin works closely with designers and developers, helping teams understand not just what to do, but why it matters.
</p>

<button type="button" data-copy-target="bio-short">
    Copy bio text
</button>

<details>
    <summary>Long bio</summary>
    <div id="bio-long">
        <p>Martin Underhill is an accessibility consultant with over a decade of experience working on accessible digital products. He has been writing and obsessing over semantic markup since 2002, and takes a pragmatic, real-world approach to accessibility shaped by years working on live products. Martin works closely with designers and developers, helping teams understand not just what to do, but why it matters.</p>
        <p>Until recently, Martin was Accessibility Lead at Sage, a FTSE 100 software company, responsible for accessibility across more than 40 products used in 23 countries. His work focused on embedding accessibility into everyday practice; building organisational capability, establishing governance, and supporting teams to deliver accessible outcomes in real-world product environments.</p>
        <p>Martin now works independently advising organisations on accessibility strategy, regulatory alignment, and sustainable implementation. His work spans WCAG and EN 301 549 conformance, compliance with accessibility legislation such as the European Accessibility Act, and guiding teams beyond audit-driven approaches towards long-term, embedded accessibility.</p>
        <p>A long-time community organiser and speaker, Martin co-founded Frontend NE and has spoken at conferences and events across the UK. His talks are practical and experience-led, often incorporating hands-on labs and real examples, and focus on helping teams apply accessibility confidently in everyday product work.</p>
    </div>
    <button type="button" data-copy-target="bio-long">
        Copy long bio text
    </button>
</details>


## Photo

This image is free to use for event promotion.

  <img class="thumbnail" src="/assets/img/martin-underhill-tempertemper--square-1024x1024.jpg" alt="Picture of Martin Underhill" />

<ul>
    <li>
        <a href="/assets/img/martin-underhill-tempertemper--square-512x512.jpg" download>Download 512px by 512px image (jpg)</a>
    </li>
    <li>
        <a href="/assets/img/martin-underhill-tempertemper--square-1024x1024.jpg" download>Download 1024px by 1024px image (jpg)</a>
    </li>
</ul>

### Alt/descriptive text

Martin Underhill, a friendly white man with a bald head, short brown beard, and glasses. He’s wearing a casual dark blue shirt and is leaning against a grey stone wall.


<script>
    (function () {
        function copyTextFromElement(element) {
            const text = element.innerText.trim();

            if (navigator.clipboard && window.isSecureContext) {
                return navigator.clipboard.writeText(text);
            }

            // Fallback for older browsers
            const textarea = document.createElement('textarea');
            textarea.value = text;
            textarea.style.position = 'fixed';
            textarea.style.left = '-9999px';
            document.body.appendChild(textarea);
            textarea.select();

            try {
                document.execCommand('copy');
            } finally {
                document.body.removeChild(textarea);
            }
        }

        document.addEventListener('click', function (event) {
            const button = event.target.closest('[data-copy-target]');
            if (!button) return;

            const targetId = button.getAttribute('data-copy-target');
            const target = document.getElementById(targetId);
            if (!target) return;

            copyTextFromElement(target).then(function () {
                const originalText = button.textContent;
                button.textContent = 'Copied';
                button.disabled = true;

                setTimeout(function () {
                    button.textContent = originalText;
                    button.disabled = false;
                }, 2000);
            });
        });
    })();
</script>
