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

  form.addEventListener('submit', (event) => {
    let valid = true;
    syncPhone();

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
      clearError(field);
      const result = check(input);
      if (result !== true) {
        showError(field, typeof result === 'string' ? result : message);
        valid = false;
      }
    });

    if (!valid) {
      event.preventDefault();
      const firstError = form.querySelector('.field--error input, .field--error select');
      if (firstError) {
        firstError.focus();
      }
    }
  });
})();
