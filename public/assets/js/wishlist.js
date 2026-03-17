document.addEventListener('DOMContentLoaded', function() {
    // Wishlist toggle functionality
    document.querySelectorAll('.wishlist-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const productId = this.dataset.productId;
            const isAdded = this.classList.contains('added');
            
            const url = isAdded ? 
                `{{ route('wishlist.remove', ':id') }}`.replace(':id', productId) : 
                `{{ route('wishlist.add', ':id') }}`.replace(':id', productId);
            
            const method = isAdded ? 'DELETE' : 'POST';
            
            fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (!isAdded) {
                        this.classList.add('added');
                        this.innerHTML = '<span>Remove from Wishlist</span>';
                        this.dataset.action = 'remove';
                    } else {
                        this.classList.remove('added');
                        this.innerHTML = '<span>Add to Wishlist</span>';
                        this.dataset.action = 'add';
                    }
                    
                    // Update navbar count
                    const countEl = document.querySelector('.hdr_wishList_btn span');
                    if (countEl) {
                        countEl.textContent = data.count || 0;
                    }
                }
            })
            .catch(error => {
                console.error('Wishlist error:', error);
            });
        });
    });
    
    // Navbar wishlist button (if exists)
    const navWishlistBtn = document.querySelector('.hdr_wishList_btn');
    if (navWishlistBtn) {
        navWishlistBtn.addEventListener('click', function() {
            window.location.href = '{{ route("frontend.wishlist") }}';
        });
    }
});

