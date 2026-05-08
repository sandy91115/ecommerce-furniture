window.removeFromCart = function(id) {
    // No confirm - instant remove per user request
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || window.Laravel?.cart?.csrfToken || '';

    fetch(window.location.pathname.includes('furniture/furniture') ? '/furniture/cart/remove' : '/cart/remove', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ id: id })
    })
    .then(response => {
        if (!response.ok) throw new Error('HTTP ' + response.status);
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
};

console.log('Cart remove function loaded');
