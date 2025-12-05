(function () {
  const ERROR_CLASS = 'form-error';
  const ERROR_TEXT_CLASS = 'error-text';

  // Basic validators

  function required(message) {
    return function (value) {
      if (!value.trim()) {
        return message;
      }
      return '';
    };
  }

  function emailFormat(message) {
    const emailPattern = /^[^@\s]+@[^@\s]+\.[^@\s]+$/;
    return function (value) {
      const trimmed = value.trim();
      if (!trimmed) {
        return '';
      }
      if (!emailPattern.test(trimmed)) {
        return message;
      }
      return '';
    };
  }

  function maxLength(limit, message) {
    return function (value) {
      if (value.trim().length > limit) {
        return message;
      }
      return '';
    };
  }

  function phoneCharacters(message) {
    // Digits, spaces, brackets, plus, hyphen, dot
    const phonePattern = /^[0-9+\-().\s]*$/;

    return function (value) {
      const trimmed = value.trim();
      if (!trimmed) {
        return '';
      }
      if (!phonePattern.test(trimmed)) {
        return message;
      }
      return '';
    };
  }

  // Shared helpers

  function clearErrors(fields) {
    fields.forEach(field => {
      const el = document.getElementById(field.id);
      if (!el) return;

      const group = el.closest('.input-group');
      if (group) group.classList.remove(ERROR_CLASS);

      el.removeAttribute('aria-invalid');
      el.removeAttribute('aria-describedby');

      const existing = document.getElementById(field.id + '-error');
      if (existing) existing.remove();
    });
  }

  function addInlineError(fieldId, message) {
    const el = document.getElementById(fieldId);
    if (!el) return;

    const group = el.closest('.input-group');
    if (!group) return;

    group.classList.add(ERROR_CLASS);

    const error = document.createElement('p');
    error.id = fieldId + '-error';
    error.className = ERROR_TEXT_CLASS;
    error.textContent = message;

    // Place error between label and input
    group.insertBefore(error, el);

    el.setAttribute('aria-invalid', 'true');
    el.setAttribute('aria-describedby', error.id);
  }

  function renderErrorSummary(form, errors, summaryConfig) {
    const existing = form.querySelector('.error-summary');
    if (existing) existing.remove();

    const container = document.createElement('div');
    container.className = 'error-summary';
    container.setAttribute('role', 'alert');
    container.setAttribute('tabindex', '-1');

    const titleId = 'error-summary-title';
    const heading = document.createElement('h2');
    heading.id = titleId;
    heading.textContent = summaryConfig && summaryConfig.heading
      ? summaryConfig.heading
      : 'There is a problem';
    container.appendChild(heading);

    if (summaryConfig && summaryConfig.intro) {
      const intro = document.createElement('p');
      intro.textContent = summaryConfig.intro;
      container.appendChild(intro);
    }

    const list = document.createElement('ul');

    errors.forEach(error => {
      const li = document.createElement('li');
      const link = document.createElement('a');
      link.href = '#' + error.id;
      link.textContent = error.message;
      li.appendChild(link);
      list.appendChild(li);
    });

    container.appendChild(list);
    container.setAttribute('aria-labelledby', titleId);

    const firstChild = form.firstElementChild;
    if (firstChild) {
      form.insertBefore(container, firstChild);
    } else {
      form.appendChild(container);
    }

    return container;
  }

  function attachFormValidation(options) {
    const formSelector = options.formSelector;
    const fields = options.fields || [];
    const errorSummaryConfig = options.errorSummary;

    const form = document.querySelector(formSelector);
    if (!form) return;

    form.addEventListener('submit', event => {
      // Remove existing summary
      const existingSummary = form.querySelector('.error-summary');
      if (existingSummary) existingSummary.remove();

      clearErrors(fields);

      const errors = [];

      fields.forEach(field => {
        const el = document.getElementById(field.id);
        if (!el) return;

        const value = el.value || '';

        if (Array.isArray(field.validators)) {
          for (const validate of field.validators) {
            const message = validate(value, el);
            if (message) {
              errors.push({ id: field.id, message });
              break;
            }
          }
        }
      });

      if (errors.length) {
        event.preventDefault();

        // Add inline errors for all fields with errors
        errors.forEach(error => {
          addInlineError(error.id, error.message);
        });

        if (errorSummaryConfig) {
          const summaryEl = renderErrorSummary(form, errors, errorSummaryConfig);
          summaryEl.focus();
        } else {
          const first = errors[0];
          const firstEl = document.getElementById(first.id);
          if (firstEl) firstEl.focus();
        }
      }
    });
  }

  // Newsletter subscribe form

  attachFormValidation({
    formSelector: 'form[name="newsletter"]',
    fields: [
      {
        id: 'email',
        validators: [
          required('Enter your email address'),
          emailFormat('Enter an email address in the correct format')
        ]
      }
    ]
  });

  // Newsletter unsubscribe form

  attachFormValidation({
    formSelector: 'form[name="unsubscribe"]',
    fields: [
      {
        id: 'email',
        validators: [
          required('Enter your email address'),
          emailFormat('Enter an email address in the correct format')
        ]
      }
    ]
  });

  // Search form

  attachFormValidation({
    formSelector: '#search-form',
    fields: [
      {
        id: 'search',
        validators: [
          required('Enter a search term'),
          maxLength(100, 'Search term must be 100 characters or fewer')
        ]
      }
    ]
  });

  // Contact form

  attachFormValidation({
    formSelector: 'form[name="contact"]',
    fields: [
      {
        id: 'name',
        validators: [
          required('Enter your name'),
          maxLength(100, 'Name must be 100 characters or fewer')
        ]
      },
      {
        id: 'email',
        validators: [
          required('Enter your email address'),
          emailFormat('Enter an email address in the correct format'),
          maxLength(100, 'Email address must be 100 characters or fewer')
        ]
      },
      {
        id: 'phone',
        validators: [
          maxLength(20, 'Phone number must be 20 characters or fewer'),
          phoneCharacters('Phone number can only include numbers, spaces, and some special characters like brackets')
        ]
      },
      {
        id: 'message',
        validators: [
          required('Enter your message'),
          maxLength(1000, 'Message must be 1000 characters or fewer')
        ]
      }
    ],
    errorSummary: {
      heading: 'Just a moment',
      intro: 'Something needs to be fixed in the message you tried to send.'
    }
  });

})();
