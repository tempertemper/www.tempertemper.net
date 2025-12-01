(function () {
  const form = document.querySelector('form[name="newsletter"]');
  if (!form) return;

  const ERROR_CLASS = 'form-error';
  const ERROR_TEXT_CLASS = 'error-text';

  // Validation rules
  const fields = [
    { id: 'email', required: 'Enter your email address' }
  ];

  function clearErrors() {
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

  function addInlineError(field, message) {
    const el = document.getElementById(field.id);
    if (!el) return;

    const group = el.closest('.input-group');
    if (!group) return;

    group.classList.add(ERROR_CLASS);

    const error = document.createElement('p');
    error.id = field.id + '-error';
    error.className = ERROR_TEXT_CLASS;
    error.textContent = message;

    // Place error between label and input
    group.insertBefore(error, el);

    el.setAttribute('aria-invalid', 'true');
    el.setAttribute('aria-describedby', error.id);
  }

  form.addEventListener('submit', event => {
    clearErrors();

    const errors = [];

    fields.forEach(field => {
      const el = document.getElementById(field.id);
      if (!el) return;

      const value = (el.value || '').trim();

      if (!value) {
        errors.push({ id: field.id, message: field.required });
        return;
      }

      if (field.id === 'email') {
        const emailPattern = /^[^@\s]+@[^@\s]+\.[^@\s]+$/;

        if (!emailPattern.test(value)) {
          errors.push({
            id: field.id,
            message: 'Enter an email address in the correct format'
          });
        }
      }
    });

    if (errors.length) {
      event.preventDefault();

      const first = errors[0];
      const field = fields.find(f => f.id === first.id);

      if (field) {
        addInlineError(field, first.message);
        document.getElementById(field.id).focus();
      }
    }
  });
})();
