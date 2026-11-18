(() => {
  const form = document.querySelector('[data-auto-filter]');
  if (!form) {
    return;
  }

  let searchTimer = null;

  const submitFilters = () => {
    form.requestSubmit ? form.requestSubmit() : form.submit();
  };

  form.querySelectorAll('select, input[type="date"]').forEach((el) => {
    el.addEventListener('change', submitFilters);
  });

  const search = form.querySelector('input[type="search"]');
  if (search) {
    search.addEventListener('input', () => {
      clearTimeout(searchTimer);
      searchTimer = setTimeout(submitFilters, 350);
    });
  }
})();
