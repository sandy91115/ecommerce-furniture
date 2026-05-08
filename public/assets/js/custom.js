

  // Simulate loading delay
setTimeout(() => {    const skeleton = document.getElementById('loading-skeleton');
        const mainContent = document.getElementById('main-content');   if (skeleton && mainContent) {     skeleton.style.display = 'none';      mainContent.style.display = 'block';    }  }, 800);

  // Sales Chart
document.addEventListener('DOMContentLoaded', function() {
       const canvas = document.getElementById('salesChart');    if (!canvas || typeof Chart === 'undefined') return;
           const ctx = canvas.getContext('2d');    const salesChartData = window.salesChartData || {};
    
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: Object.keys(salesChartData),
        datasets: [{
          label: 'Revenue',
          data: Object.values(salesChartData),
          borderColor: 'rgb(59, 130, 246)',
          backgroundColor: 'rgba(59, 130, 246, 0.1)',
          tension: 0.4,
          fill: true,
          pointBackgroundColor: 'rgb(59, 130, 246)',
          pointBorderColor: '#fff',
          pointHoverBackgroundColor: '#fff',
          pointHoverBorderColor: 'rgb(59, 130, 246)'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: {
              color: 'rgba(0,0,0,0.05)'
            }
          },
          x: {
            grid: {
              display: false
            }
          }
        },
        animation: {
          duration: 2000,
          easing: 'easeInOutQuart'
        }
      }
    });
  });

    const currentUrl = new URL(window.location.href);
    const normalizePath = (pathname) => pathname.replace(/\/$/, '') || '/';
    const hasMatchingParams = (targetUrl, activeUrl) => {
        const targetEntries = Array.from(targetUrl.searchParams.entries());

        if (!targetEntries.length) {
            return false;
        }

        return targetEntries.every(([key, value]) => activeUrl.searchParams.get(key) === value);
    };

    const currentPath = normalizePath(currentUrl.pathname);

    const subMenuItems = document.querySelectorAll('.sub-menu-item');
    subMenuItems.forEach((item) => {
        const itemUrl = new URL(item.href, window.location.origin);
        const itemPath = normalizePath(itemUrl.pathname);
        const isShopDropdownItem = Boolean(item.closest('.shop-dropdown-menu'));
        const isCurrentItem = itemPath === currentPath && (
            !isShopDropdownItem || hasMatchingParams(itemUrl, currentUrl)
        );

        if (isCurrentItem) {
            item.classList.add('active');

            // Highlight all parent menus recursively
            let parentMenu = item.closest('.parent-menu-item');
            while (parentMenu && !parentMenu.classList.contains('processed')) {
                const parentLink = parentMenu.querySelector('a');
                if (parentLink) {
                    parentLink.classList.add('active');
                }
                parentMenu.classList.add('processed');
                parentMenu = parentMenu.closest('.parent-parent-menu-item');
            }

            // Highlight the top-level parent menu
            const topLevelMenu = item.closest('.parent-parent-menu-item');
            if (topLevelMenu) {
                const topLevelLink = topLevelMenu.querySelector('.home-link');
                if (topLevelLink) {
                    topLevelLink.classList.add('active');
                }
            }
        }
    });



        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.js-tag-field').forEach(function (field) {
                if (field.dataset.ready === 'true') {
                    return;
                }

                field.dataset.ready = 'true';

                const list = field.querySelector('.js-tag-list');
                const input = field.querySelector('.js-tag-input');
                const addButton = field.querySelector('.js-tag-add');
                const form = field.closest('form');

                function normalizeTag(value) {
                    return value.trim().replace(/\s+/g, ' ');
                }

                function updateSpacing() {
                    list.classList.toggle('mb-3', list.children.length > 0);
                }

                function createTagChip(tag) {
                    const chip = document.createElement('span');
                    chip.className = 'js-tag-chip inline-flex items-center gap-2 rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-blue-800';
                    chip.dataset.tag = tag.toLowerCase();

                    const label = document.createElement('span');
                    label.textContent = tag;

                    const removeButton = document.createElement('button');
                    removeButton.type = 'button';
                    removeButton.className = 'js-remove-tag text-blue-700 transition hover:text-blue-900';
                    removeButton.setAttribute('aria-label', 'Remove ' + tag);
                    removeButton.innerHTML = '&times;';

                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'tags[]';
                    hiddenInput.value = tag;

                    chip.appendChild(label);
                    chip.appendChild(removeButton);
                    chip.appendChild(hiddenInput);

                    return chip;
                }

                function hasTag(tag) {
                    const tagKey = tag.toLowerCase();

                    return Array.from(list.querySelectorAll('.js-tag-chip')).some(function (chip) {
                        return chip.dataset.tag === tagKey;
                    });
                }

                function addTag(rawValue) {
                    const tag = normalizeTag(rawValue);

                    if (!tag || hasTag(tag)) {
                        input.value = '';
                        return;
                    }

                    list.appendChild(createTagChip(tag));
                    input.value = '';
                    updateSpacing();
                }

                function commitInput() {
                    addTag(input.value);
                }

                addButton.addEventListener('click', commitInput);

                if (form) {
                    form.addEventListener('submit', commitInput);
                }

                input.addEventListener('keydown', function (event) {
                    if (event.key === 'Enter' || event.key === ',') {
                        event.preventDefault();
                        commitInput();
                    }

                    if (event.key === 'Backspace' && input.value === '') {
                        const chips = list.querySelectorAll('.js-tag-chip');
                        const lastChip = chips[chips.length - 1];

                        if (lastChip) {
                            lastChip.remove();
                            updateSpacing();
                        }
                    }
                });

                input.addEventListener('blur', commitInput);

                input.addEventListener('paste', function (event) {
                    const pastedText = (event.clipboardData || window.clipboardData).getData('text');

                    if (!/[,\r\n]/.test(pastedText)) {
                        return;
                    }

                    event.preventDefault();

                    pastedText.split(/[\r\n,]+/).forEach(function (tag) {
                        addTag(tag);
                    });
                });

                list.addEventListener('click', function (event) {
                    const button = event.target.closest('.js-remove-tag');

                    if (!button) {
                        return;
                    }

                    const chip = button.closest('.js-tag-chip');

                    if (chip) {
                        chip.remove();
                        updateSpacing();
                        input.focus();
                    }
                });

                updateSpacing();
            });
        });
      



function removeFromWishlist(id) {
    if (!confirm('Remove this item from wishlist?')) return;

    fetch('/wishlist/remove/' + id, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(err => console.error('Error:', err));
}

function getCsrfToken() {
    return window.Laravel?.cart?.csrfToken || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
}


function removeFromCart(id) {
    if (!confirm('Are you sure you want to remove this item?')) return;

    if (!window.Laravel?.cart?.removeUrl) {
        alert('Cart configuration not loaded');
        return;
    }

    fetch(window.Laravel.cart.removeUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': getCsrfToken(),
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            id: id
        })
    })
    .then(response => {
        if (!response.ok) throw new Error(`HTTP ${response.status}`);
        return response.json();
    })
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Remove failed');
        }
    })
    .catch(err => {
        console.error('Remove error:', err);
        alert('Error removing item. Please try again.');
    });
}

document.addEventListener('DOMContentLoaded', function () {
    const cartBtn = document.querySelector('.hdr_cart_btn');
    const cartPopup = document.querySelector('.hdr_cart_popup');

    if (cartBtn && cartPopup) {
        // Toggle cart popup on button click
        cartBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            cartPopup.classList.toggle('cart-active');
        });

        // Close on outside click
        document.addEventListener('click', function (e) {
            if (!cartPopup.contains(e.target) && !cartBtn.contains(e.target)) {
                cartPopup.classList.remove('cart-active');
            }
        });

        // Close on Escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && cartPopup.classList.contains('cart-active')) {
                cartPopup.classList.remove('cart-active');
            }
        });

        // Update cart count after AJAX (if needed)
        function updateCartCount() {
            // Triggered by cart changes
            location.reload(); // Simple, matches existing remove logic
        }
    }
});


// Cart quantity update (cart page) - now uses Laravel globals
function updateQuantity(id, change) {
    const input = event.target.closest('.inc-dec')?.querySelector('input') || event.target.parentElement.querySelector('input');
    if (!input) return;
    
    let quantity = parseInt(input.value || 1) + change;
    if (quantity < 1) quantity = 1;

    input.value = quantity;
    updateCart(id, quantity);
}

function updateCart(id, quantity) {
    if (!window.Laravel?.cart) {
        console.error('Cart config not loaded');
        return;
    }

    fetch(window.Laravel.cart.updateUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': getCsrfToken(),
            'Accept': 'application/json'
        },
        body: JSON.stringify({ id, quantity })
    })
    .then(res => res.json())
    .catch(console.error);
}

const addCouponCode = document.querySelector('.add-coupon-code');
const couponWrapper = document.querySelector('.coupon-wrapper');
if (addCouponCode && couponWrapper) {
    addCouponCode.addEventListener('click', () => {
        couponWrapper.classList.toggle('hidden');
        couponWrapper.classList.toggle('flex');
    });
}



document.addEventListener('DOMContentLoaded', function () {
    const placeOrderBtn = document.getElementById('place-order-btn');
    const checkoutForm = document.getElementById('checkout-form');

    if (placeOrderBtn && checkoutForm) {
        placeOrderBtn.addEventListener('click', function (e) {
            e.preventDefault();

            const paymentError = document.getElementById('payment-method-error');
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
            if (paymentError) {
                paymentError.classList.toggle('hidden', Boolean(paymentMethod));
            }

            if (!paymentMethod) {
                return;
            }

            // Basic validation, including fields attached with form="checkout-form".
            const requiredFields = Array.from(checkoutForm.elements).filter(field => field.required);
            let isValid = true;
            requiredFields.forEach(field => {
                const valid = field.type === 'checkbox' || field.type === 'radio'
                    ? field.checked || Boolean(document.querySelector(`input[name="${field.name}"]:checked`))
                    : field.value.trim() !== '';

                if (!valid) {
                    isValid = false;
                    field.classList.add('border-red-500');
                } else {
                    field.classList.remove('border-red-500');
                }
            });

            if (!isValid) {
                alert('Please fill in all required fields.');
                return;
            }

            placeOrderBtn.disabled = true;
            placeOrderBtn.querySelector('span').innerText = 'Processing...';

            const formData = new FormData(checkoutForm);

            fetch('/checkout', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json().then(data => ({ ok: response.ok, status: response.status, data })))
                .then(({ ok, data }) => {
                    if (!ok) {
                        const firstError = data.errors ? Object.values(data.errors).flat()[0] : null;
                        throw new Error(firstError || data.message || 'Please check the checkout form.');
                    }

                    if (data.success) {
                        window.location.href = data.redirect;
                    } else {
                        alert(data.message || 'Something went wrong. Please try again.');
                        placeOrderBtn.disabled = false;
                        placeOrderBtn.querySelector('span').innerText = 'Place Order';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert(error.message || 'An error occurred. Please try again.');
                    placeOrderBtn.disabled = false;
                    placeOrderBtn.querySelector('span').innerText = 'Place Order';
                });
        });
    }
});
function togglePassword() {
    const password = document.getElementById('password');
    const icon = document.getElementById('toggleIcon');
    if (!password || !icon) {
        return;
    }

    if (password.type === 'password') {
        password.type = 'text';
        icon.classList.remove('mdi-eye-outline');
        icon.classList.add('mdi-eye-off-outline');
    } else {
        password.type = 'password';
        icon.classList.remove('mdi-eye-off-outline');
        icon.classList.add('mdi-eye-outline');
    }
}

// Simulate loading delay
setTimeout(() => {
    const loadingSkeleton = document.getElementById('loading-skeleton');
    const mainContent = document.getElementById('main-content');

    if (!loadingSkeleton || !mainContent) {
        return;
    }

    loadingSkeleton.style.display = 'none';
    mainContent.style.display = 'block';
}, 800);

// Sales Chart
document.addEventListener('DOMContentLoaded', function () {
    const salesChartCanvas = document.getElementById('salesChart');
    const salesChartData = window.salesChartData;

    if (!salesChartCanvas || typeof Chart === 'undefined' || !salesChartData) {
        return;
    }

    const ctx = salesChartCanvas.getContext('2d');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: Object.keys(salesChartData),
            datasets: [{
                label: 'Revenue',
                data: Object.values(salesChartData),
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: 'rgb(59, 130, 246)',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: 'rgb(59, 130, 246)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            animation: {
                duration: 2000,
                easing: 'easeInOutQuart'
            }
        }
    });
});
function openModal(modalId, id = null) {
    const modal = document.getElementById(modalId);
    if (!modal) {
        console.error('Modal not found:', modalId);
        return;
    }

    if (id && modalId === 'editModal') {
        // Show loading state
        modal.innerHTML = '<div class="bg-white p-8 rounded-lg"><i class="fas fa-spinner fa-spin text-2xl"></i> Loading...</div>';

        // Fetch menu data via AJAX
        fetch(`/admin/menus/${id}/edit`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
            .then(res => {
                if (!res.ok) throw new Error(`HTTP ${res.status}: ${res.statusText}`);
                return res.json();
            })
            .then(data => {
                if (data.menu) {
                    document.getElementById('editForm').action = `/admin/menus/${id}`;
                    document.getElementById('editMenuId').value = id;
                    populateForm(data.menu);
                    modal.classList.remove('hidden');
                } else {
                    throw new Error('Menu data not found');
                }
            })
            .catch(error => {
                console.error('Edit modal error:', error);
                modal.innerHTML = '<div class="bg-white p-8 rounded-lg text-red-600"><i class="fas fa-exclamation-triangle mr-2"></i>Error loading menu data</div>';
                setTimeout(() => modal.classList.add('hidden'), 3000);
            });
    } else {
        modal.classList.remove('hidden');
    }
}

function populateForm(menu) {
    document.getElementById('edit_menu_type').value = menu.menu_type || '';
    document.getElementById('edit_title').value = menu.title || '';
    document.getElementById('edit_url').value = menu.url || '';
    document.getElementById('edit_parent_id').value = menu.parent_id || '';
    document.getElementById('edit_order').value = menu.order || 0;
    document.getElementById('edit_status').value = menu.status || 'active';
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
    }
}

// Menu type filter uses blade onclick, no need for duplicate listener here
// document.getElementById('menuTypeFilter').addEventListener('change', function() {
//     window.location.href = `/admin/menus?type=${this.value}`;
// }); // Removed duplicate


// Pricing Switcher
document.addEventListener('DOMContentLoaded', function () {
  const monthlyRadio = document.getElementById('monthly');
  const yearlyRadio = document.getElementById('yearly');
  if (!monthlyRadio || !yearlyRadio) return;
  const highlighter = document.querySelector('.highlighter');
  const prices = document.querySelectorAll('.price');

    const updatePrices = () => {
        const isYearly = yearlyRadio.checked;

        prices.forEach(price => {
            const newPrice = isYearly ? price.dataset.yearly : price.dataset.monthly;
            price.textContent = `$${newPrice}`;
        });

        highlighter.style.transform = isYearly ? 'translateX(100%)' : 'translateX(0)';
    };

    monthlyRadio.addEventListener('change', updatePrices);
    yearlyRadio.addEventListener('change', updatePrices);

    updatePrices();
});
