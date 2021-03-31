// Prevent Double Submits, from https://www.bram.us/2020/11/04/preventing-double-form-submissions/
document.querySelectorAll('form').forEach(form => {
  form.addEventListener('submit', (e) => {
    // Prevent if already submitting
    if (form.classList.contains('is-submitting')) {
      e.preventDefault();
      // ↓ Uncomment for testing
      // console.info('Successive submit suppressed');
    }
    // Add a visual indicator to show the user it is submitting
    form.classList.add('is-submitting');
    // ↓ Uncomment for testing
    // e.preventDefault();
  });
});

