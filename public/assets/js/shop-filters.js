document.addEventListener('DOMContentLoaded', function () {
  const form = document.querySelector('[data-shop-filter-form]');
  const productsGrid = document.getElementById('products-grid');
  const paginationContainer = document.getElementById('pagination-container');
  const priceFields = form ? form.querySelectorAll('[data-price-field]') : [];
  const categoryField = form ? form.querySelector('[data-category-filter]') : null;
  const selectFields = form ? form.querySelectorAll('select') : [];
  const shopUrl = form ? form.dataset.shopUrl || form.action : '';
  const categoryBaseUrl = form ? (form.dataset.categoryBaseUrl || '').replace(/\/$/, '') : '';
  const currentCategorySlug = form ? (form.dataset.currentCategory || '').trim() : '';

  if (!form || !productsGrid || !paginationContainer) {
    return;
  }

  function debounce(func, wait) {
    let timeout;

    return function executedFunction(...args) {
      const later = () => {
        clearTimeout(timeout);
        func(...args);
      };

      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
    };
  }

  function updateResults(data, url) {
    productsGrid.innerHTML = data.products_html;
    paginationContainer.innerHTML = data.pagination_html;
    bindPagination();

    if (url) {
      window.history.replaceState({}, '', url);
    }
  }

  function fetchResults(url) {
    return fetch(url, {
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
      },
    })
      .then(response => {
        if (!response.ok) {
          throw new Error('Request failed');
        }

        return response.json();
      });
  }

  function buildUrl() {
    const formData = new FormData(form);
    const params = new URLSearchParams();
    const selectedCategory = categoryField ? String(categoryField.value || '').trim() : '';

    formData.forEach((value, key) => {
      const normalizedValue = String(value).trim();

      if (key === 'category' || normalizedValue === '') {
        return;
      }

      params.append(key, normalizedValue);
    });

    const baseUrl = selectedCategory && categoryBaseUrl
      ? `${categoryBaseUrl}/${encodeURIComponent(selectedCategory)}`
      : shopUrl;

    const queryString = params.toString();
    return queryString ? `${baseUrl}?${queryString}` : baseUrl;
  }

  function shouldNavigate() {
    const selectedCategory = categoryField ? String(categoryField.value || '').trim() : '';
    return selectedCategory !== currentCategorySlug;
  }

  function handleFilter(e) {
    if (e) {
      e.preventDefault();
    }

    const url = buildUrl();

    if (shouldNavigate()) {
      window.location.href = url;
      return;
    }

    fetchResults(url)
      .then(data => updateResults(data, url))
      .catch(error => {
        console.error('Filter error:', error);
        window.location.href = url;
      });
  }

  function bindPagination() {
    paginationContainer.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', function (e) {
        e.preventDefault();

        fetchResults(this.href)
          .then(data => updateResults(data, this.href))
          .catch(error => {
            console.error('Pagination error:', error);
            window.location.href = this.href;
          });
      });
    });
  }

  const debouncedFilter = debounce(handleFilter, 500);

  form.addEventListener('submit', handleFilter);

  if (categoryField) {
    categoryField.addEventListener('change', handleFilter);
  }

  selectFields.forEach(field => {
    if (field === categoryField) {
      return;
    }

    field.addEventListener('change', debouncedFilter);
  });

  priceFields.forEach(field => {
    field.addEventListener('focus', function () {
      if (this.value) {
        this.select();
      }
    });

    field.addEventListener('input', debouncedFilter);

    field.addEventListener('keydown', function (event) {
      if (event.key === 'Enter') {
        handleFilter(event);
      }
    });
  });

  bindPagination();
});
