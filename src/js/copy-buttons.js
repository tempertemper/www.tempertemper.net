if (navigator.clipboard && window.isSecureContext) {
    document.querySelectorAll('[data-copy-button-label]').forEach(function (target) {
        const button = document.createElement('button');

        button.type = 'button';
        button.setAttribute('data-copy-target', target.id);
        button.textContent = target.getAttribute('data-copy-button-label');

        target.insertAdjacentElement('afterend', button);
    });

    function copyTextFromElement(element) {
        const paragraphs = Array.from(element.children);
        const containsOnlyParagraphs =
            paragraphs.length > 0 &&
            paragraphs.every(function (child) {
                return child.tagName === 'P';
            });
        const text = containsOnlyParagraphs
            ? paragraphs.map(function (paragraph) {
                return paragraph.innerText.trim();
            }).join('\n\n')
            : element.innerText.trim();

        return navigator.clipboard.writeText(text);
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
}
