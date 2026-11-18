(() => {
  const form = document.querySelector('[data-validate]');
  if (!form) {
    return;
  }

  const showError = (field, message) => {
    field.classList.add('field--error');
    let error = field.querySelector('.field-error');
    if (!error) {
      error = document.createElement('p');
      error.className = 'field-error';
      field.appendChild(error);
    }
    error.textContent = message;
  };

  const clearError = (field) => {
    field.classList.remove('field--error');
    const error = field.querySelector('.field-error');
    if (error) {
      error.remove();
    }
  };

  form.addEventListener('submit', (event) => {
    let valid = true;
    const fields = [
      {
        name: 'company_name',
        message: 'Укажите название компании.',
        check: (value) => value.trim().length >= 2,
      },
      {
        name: 'contact_name',
        message: 'Укажите имя контактного лица.',
        check: (value) => value.trim().length >= 2,
      },
      {
        name: 'phone',
        message: 'Введите корректный номер телефона.',
        check: (value) => (value.replace(/\D+/g, '').length >= 10),
      },
      {
        name: 'email',
        message: 'Введите корректный email.',
        check: (value) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value.trim()),
      },
    ];

    fields.forEach(({ name, message, check }) => {
      const input = form.querySelector(`[name="${name}"]`);
      if (!input) {
        return;
      }
      const field = input.closest('.field');
      if (!field) {
        return;
      }
      clearError(field);
      if (!check(input.value || '')) {
        showError(field, message);
        valid = false;
      }
    });

    if (!valid) {
      event.preventDefault();
      const firstError = form.querySelector('.field--error input');
      if (firstError) {
        firstError.focus();
      }
    }
  });
})();
