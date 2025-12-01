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

  function attachFormValidation({ formSelector, fields }) {
    const form = document.querySelector(formSelector);
    if (!form) return;

    form.addEventListener('submit', event => {
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

        const first = errors[0];
        addInlineError(first.id, first.message);

        const firstEl = document.getElementById(first.id);
        if (firstEl) firstEl.focus();
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

})();
