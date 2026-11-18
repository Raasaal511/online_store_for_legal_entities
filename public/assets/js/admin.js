(() => {
  const filterForm = document.querySelector('[data-auto-filter]');
  if (filterForm) {
    let searchTimer = null;

    const submitFilters = () => {
      filterForm.requestSubmit ? filterForm.requestSubmit() : filterForm.submit();
    };

    filterForm.querySelectorAll('select, input[type="date"]').forEach((el) => {
      el.addEventListener('change', submitFilters);
    });

    const search = filterForm.querySelector('input[type="search"]');
    if (search) {
      search.addEventListener('input', () => {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(submitFilters, 350);
      });
    }
  }

  document.querySelectorAll('[data-auto-status]').forEach((form) => {
    const select = form.querySelector('select[name="status"]');
    if (!select) {
      return;
    }

    select.addEventListener('change', () => {
      form.classList.add('is-saving');
      form.requestSubmit ? form.requestSubmit() : form.submit();
    });
  });
})();
