(() => {
  const form = document.querySelector('[data-validate]');
  if (!form) {
    return;
  }

  const phoneRules = {
    '+7': { digits: 10, placeholder: '(999) 123-45-67' },
    '+375': { digits: 9, placeholder: '29 123-45-67' },
    '+998': { digits: 9, placeholder: '90 123-45-67' },
    '+996': { digits: 9, placeholder: '700 123-456' },
    '+992': { digits: 9, placeholder: '90 123-45-67' },
    '+993': { digits: 8, placeholder: '65 12-34-56' },
    '+994': { digits: 9, placeholder: '50 123-45-67' },
    '+995': { digits: 9, placeholder: '555 12-34-56' },
    '+374': { digits: 8, placeholder: '91 123456' },
  };

  const phoneCode = form.querySelector('#phone_code');
  const phoneNumber = form.querySelector('#phone_number');
  const phoneHidden = form.querySelector('#phone');
  const formAlert = document.getElementById('form-alert');
  const submitButton = form.querySelector('button[type="submit"]');

  const ensureErrorSlot = (field) => {
    let error = field.querySelector('.field-error');
    if (!error) {
      error = document.createElement('p');
      error.className = 'field-error';
      error.setAttribute('aria-live', 'polite');
      field.appendChild(error);
    }
    return error;
  };

  const showError = (field, message) => {
    field.classList.add('field--error');
    const error = ensureErrorSlot(field);
    error.textContent = message;
  };

  const clearError = (field) => {
    field.classList.remove('field--error');
    const error = field.querySelector('.field-error');
    if (error) {
      error.textContent = '';
    }
  };

  const clearAllErrors = () => {
    form.querySelectorAll('.field').forEach(clearError);
  };

  const showAlert = (type, message) => {
    if (!formAlert) {
      return;
    }
    formAlert.hidden = false;
    formAlert.classList.remove('alert--hidden', 'alert--success', 'alert--error');
    formAlert.classList.add(type === 'success' ? 'alert--success' : 'alert--error');
    formAlert.textContent = message;
  };

  const hideAlert = () => {
    if (!formAlert) {
      return;
    }
    formAlert.hidden = true;
    formAlert.classList.add('alert--hidden');
    formAlert.classList.remove('alert--success', 'alert--error');
    formAlert.textContent = '';
  };

  const onlyDigits = (value) => value.replace(/\D+/g, '');

  const formatRuNumber = (digits) => {
    const d = digits.slice(0, 10);
    if (d.length <= 3) {
      return d.length ? `(${d}` : '';
    }
    if (d.length <= 6) {
      return `(${d.slice(0, 3)}) ${d.slice(3)}`;
    }
    if (d.length <= 8) {
      return `(${d.slice(0, 3)}) ${d.slice(3, 6)}-${d.slice(6)}`;
    }
    return `(${d.slice(0, 3)}) ${d.slice(3, 6)}-${d.slice(6, 8)}-${d.slice(8)}`;
  };

  const syncPhone = () => {
    if (!phoneCode || !phoneNumber || !phoneHidden) {
      return;
    }

    const code = phoneCode.value;
    const rule = phoneRules[code] || phoneRules['+7'];
    let digits = onlyDigits(phoneNumber.value);

    if (code === '+7' && digits.startsWith('8') && digits.length === 11) {
      digits = digits.slice(1);
    }
    if (code === '+7' && digits.startsWith('7') && digits.length === 11) {
      digits = digits.slice(1);
    }

    digits = digits.slice(0, rule.digits);
    phoneNumber.value = code === '+7' ? formatRuNumber(digits) : digits;
    phoneNumber.placeholder = rule.placeholder;
    phoneNumber.maxLength = code === '+7' ? 18 : rule.digits;
    phoneHidden.value = digits ? `${code} ${digits}` : '';
  };

  if (phoneCode && phoneNumber) {
    phoneCode.addEventListener('change', () => {
      syncPhone();
      phoneNumber.focus();
    });
    phoneNumber.addEventListener('input', syncPhone);
    syncPhone();
  }

  const validateClient = () => {
    let valid = true;
    syncPhone();
    clearAllErrors();

    const checks = [
      {
        selector: '[name="company_name"]',
        message: 'Укажите название компании.',
        check: (el) => el.value.trim().length >= 2,
      },
      {
        selector: '[name="contact_name"]',
        message: 'Укажите имя контактного лица.',
        check: (el) => el.value.trim().length >= 2,
      },
      {
        selector: '#phone_number',
        message: 'Введите корректный номер телефона.',
        check: () => {
          const code = phoneCode ? phoneCode.value : '+7';
          const rule = phoneRules[code] || phoneRules['+7'];
          const digits = onlyDigits(phoneNumber ? phoneNumber.value : '');
          if (digits.length !== rule.digits) {
            return `Введите номер полностью: ${rule.digits} цифр после кода страны.`;
          }
          return true;
        },
      },
      {
        selector: '[name="email"]',
        message: 'Введите корректный email.',
        check: (el) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(el.value.trim()),
      },
    ];

    checks.forEach(({ selector, message, check }) => {
      const input = form.querySelector(selector);
      if (!input) {
        return;
      }
      const field = input.closest('.field');
      if (!field) {
        return;
      }
      const result = check(input);
      if (result !== true) {
        showError(field, typeof result === 'string' ? result : message);
        valid = false;
      }
    });

    return valid;
  };

  const applyServerErrors = (errors = {}) => {
    Object.entries(errors).forEach(([key, message]) => {
      if (key === '_form') {
        return;
      }
      const selector = key === 'phone' ? '#phone_number' : `[name="${key}"]`;
      const input = form.querySelector(selector);
      const field = input ? input.closest('.field') : null;
      if (field) {
        showError(field, String(message));
      }
    });
  };

  const resetForm = () => {
    form.reset();
    if (phoneCode) {
      phoneCode.value = '+7';
    }
    syncPhone();
  };

  form.addEventListener('submit', async (event) => {
    event.preventDefault();
    hideAlert();

    if (!validateClient()) {
      const firstError = form.querySelector('.field--error input, .field--error select');
      if (firstError) {
        firstError.focus();
      }
      return;
    }

    if (submitButton) {
      submitButton.disabled = true;
      submitButton.textContent = 'Отправляем…';
    }

    try {
      const response = await fetch(form.action, {
        method: 'POST',
        body: new FormData(form),
        headers: {
          Accept: 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
        },
        credentials: 'same-origin',
      });

      const payload = await response.json();

      if (!payload.success) {
        applyServerErrors(payload.errors || {});
        showAlert('error', payload.message || 'Проверьте заполнение формы.');
        return;
      }

      resetForm();
      if (payload.csrfToken) {
        const tokenInput = form.querySelector('input[name="_token"]');
        if (tokenInput) {
          tokenInput.value = payload.csrfToken;
        }
      }
      showAlert('success', payload.message || 'Заявка принята.');
      formAlert?.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    } catch (error) {
      showAlert('error', 'Не удалось отправить заявку. Попробуйте ещё раз.');
    } finally {
      if (submitButton) {
        submitButton.disabled = false;
        submitButton.textContent = 'Отправить заявку';
      }
    }
  });
})();
