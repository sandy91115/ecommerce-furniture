// Global Wishlist Handler
document.addEventListener('DOMContentLoaded', function() {
    const wishlistServiceUrl = '/wishlist/toggle';
    const removeUrl = '/wishlist/remove/';

    // Toggle wishlist button handler (delegation)
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.wishlist-btn, .wishlist-toggle');
        if (!btn || !btn.dataset.productId) return;

        e.preventDefault();
        const productId = parseInt(btn.dataset.productId, 10);
        const wasAdded = btn.classList.contains('added');
        const label = btn.querySelector('span');

        btn.disabled = true;
        btn.setAttribute('aria-busy', 'true');

        if (label) {
            label.textContent = 'Updating...';
        }

        fetch(wishlistServiceUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ product_id: productId })
        })
        .then(async response => {
            const contentType = response.headers.get('content-type') || '';
            const data = contentType.includes('application/json') ? await response.json() : {};

            if (!response.ok || !data.success) {
                throw new Error(data.message || 'Error updating wishlist');
            }

            return data;
        })
        .then(data => {
            syncWishlistButtons(productId, data.added);
            updateNavbarCount(data.count);
        })
        .catch(err => {
            console.error('Wishlist error:', err);
            setWishlistButtonState(btn, wasAdded);
            alert(err.message || 'Network error. Please try again.');
        })
        .finally(() => {
            btn.disabled = false;
            btn.removeAttribute('aria-busy');
        });
    });

    // Navbar remove handler (existing + improved)
    window.removeFromWishlist = function(id) {
        if (!confirm('Remove from wishlist?')) return;
        fetch(removeUrl + id, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(async response => {
            const data = await response.json();

            if (!response.ok || !data.success) {
                throw new Error(data.message || 'Error removing wishlist item');
            }

            return data;
        })
        .then(data => {
            syncWishlistButtons(parseInt(id), false);
            updateNavbarCount(data.count);
            location.reload();
        })
        .catch(err => {
            console.error('Wishlist remove error:', err);
            alert(err.message || 'Unable to remove wishlist item.');
        });
    };

    function updateNavbarCount(count) {
        const countEls = document.querySelectorAll('.wishlist-count');
        countEls.forEach(el => el.textContent = count);
    }

    function syncWishlistButtons(productId, isAdded) {
        const buttons = document.querySelectorAll(`[data-product-id="${productId}"].wishlist-btn, [data-product-id="${productId}"].wishlist-toggle`);

        buttons.forEach(button => {
            setWishlistButtonState(button, isAdded);
        });
    }

    function setWishlistButtonState(button, isAdded) {
        button.classList.toggle('added', isAdded);
        const nextText = isAdded ? 'Remove from Wishlist' : 'Add to Wishlist';

        const label = button.querySelector('span');
        if (label) {
            label.textContent = nextText;
        }

        button.setAttribute('data-text', nextText);

        const icon = button.querySelector('svg');
        if (icon) {
            icon.style.color = isAdded ? '#ef4444' : '';
        }

        const title = isAdded ? 'Remove from wishlist' : 'Add to wishlist';
        button.setAttribute('title', title);
        button.setAttribute('aria-pressed', isAdded ? 'true' : 'false');
    }
});

