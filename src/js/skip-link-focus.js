const skipLink = document.querySelector('.skip-to-content');
const main = document.querySelector('main');

function removeMainTabindex() {
    main.removeAttribute('tabindex');
}

skipLink.addEventListener('click', () => {
    const hadTabindex = main.hasAttribute('tabindex');

    if (!hadTabindex) {
        main.setAttribute('tabindex', '-1');
    }

    main.addEventListener('focus', () => {
        main.addEventListener('blur', () => {
            if (!hadTabindex) {
                removeMainTabindex();
            }
        }, { once: true });
    }, { once: true });

    main.focus();
});
