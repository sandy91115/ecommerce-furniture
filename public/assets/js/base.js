// Navbar improvements
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.nav-shop-link > span:not(.shop-dropdown-label):not(.shop-dropdown-arrow)').forEach(span => {
        span.remove();
    });

    // Mobile menu toggle
    const hamburger = document.querySelector('.hamburger');
    const mainMenu = document.querySelector('.main-menu');
    const header = document.querySelector('.header-area');
    const closeMobileMenu = () => {
        mainMenu?.classList.remove('mobile-open', 'active');
        hamburger?.classList.remove('opened');
        header?.classList.remove('mobile-menu-open');
        document.body.classList.remove('menu_overlay');
        hamburger?.setAttribute('aria-expanded', 'false');
    };
    
    if (hamburger && mainMenu) {
        const toggleMobileMenu = function(event) {
            event.preventDefault();
            event.stopPropagation();
            event.stopImmediatePropagation();

            const isOpen = !mainMenu.classList.contains('mobile-open');
            mainMenu.classList.toggle('mobile-open', isOpen);
            mainMenu.classList.toggle('active', isOpen);
            hamburger.classList.toggle('opened', isOpen);
            header?.classList.toggle('mobile-menu-open', isOpen);
            document.body.classList.toggle('menu_overlay', isOpen);
            hamburger.setAttribute('aria-expanded', String(isOpen));
            hamburger.querySelectorAll('.line').forEach((line, index) => {
                line.style.transitionDelay = `${index * 50}ms`;
            });
        };

        hamburger.addEventListener('click', toggleMobileMenu, true);

        mainMenu.addEventListener('click', function(event) {
            const link = event.target.closest('a');
            const menuItem = link?.closest('li');

            if (!link || !menuItem || window.matchMedia('(min-width: 1025px)').matches) {
                return;
            }

            const submenu = Array.from(menuItem.children).find(child =>
                child.classList?.contains('sub-menu') || child.classList?.contains('mega-menu')
            );

            if (!submenu) {
                return;
            }

            event.preventDefault();
            event.stopPropagation();
            event.stopImmediatePropagation();

            const shouldOpen = !menuItem.classList.contains('active');
            menuItem.parentElement?.querySelectorAll(':scope > li.active').forEach(item => {
                if (item !== menuItem) item.classList.remove('active');
            });
            menuItem.classList.toggle('active', shouldOpen);
        }, true);
        
        // Close on outside click
        document.addEventListener('click', function(e) {
            if (!mainMenu.contains(e.target) && !hamburger.contains(e.target)) {
                closeMobileMenu();
            }
        });

        mainMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                closeMobileMenu();
            });
        });
    }

    // Navbar smooth scroll highlight + enhanced active states
    window.addEventListener('scroll', () => {
        const sections = document.querySelectorAll('section[id], [id^=\"sec-\"]');
        const navLinks = document.querySelectorAll('.sub-menu-item, .home-link');
        
        let current = '';
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            if (scrollY >= sectionTop - 200) {
                current = section.getAttribute('id');
            }
        });
        
        navLinks.forEach(link => {
            link.classList.remove('active', 'bg-primary/10', 'text-primary');
            const href = link.getAttribute('href');
            if ((href === `#${current}` || 
                 new URL(link.href, window.location.origin).pathname === window.location.pathname.replace(/\/$/, '')) &&
                href !== '#') {
                link.classList.add('active', 'bg-primary/10', 'text-primary', 'font-semibold');
                link.closest('li')?.classList.add('active-parent');
            }
        });
    });
    
    // Breadcrumb highlight sync
    const activeLinks = document.querySelectorAll('.sub-menu-item.active, .home-link.active');
    if (activeLinks.length > 0) {
        activeLinks[0].scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
});

// Home V2 Smooth Scroll effect
const scrollButton = document.querySelector('#scroll-button');
if (scrollButton) {
    scrollButton.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector('#sec-2');
        if (target) {
            target.scrollIntoView({ behavior: 'smooth' });
        }
    });
}



// Product UI Interactions
document.addEventListener('DOMContentLoaded', function() {
    // Quickview Modal Toggle
    document.querySelectorAll('.quick-view').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.dataset.productId || this.closest('.group').querySelector('[data-product-id]')?.dataset.productId;
            console.log('Quickview for product:', productId);
            alert(`Quickview: Product ID ${productId} - Modal implementation needed`);
        });
    });

    // Wishlist Toggle
    document.querySelectorAll('.wishlist-toggle').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.dataset.productId;
            fetch('/wishlist/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ product_id: productId })
            })
            .then(response => response.json())
            .then(data => {
                this.classList.toggle('text-red-500', data.added);
                const wishlistCount = document.querySelector('.wishlist-count');
                if (wishlistCount && typeof data.count !== 'undefined') wishlistCount.textContent = data.count;
                const svg = this.querySelector('svg');
                if (svg) {
                    svg.innerHTML = data.added ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" fill="currentColor"/>' : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" fill="none"/>';
                }
                console.log(data.message);
            })
            .catch(err => console.error('Wishlist error:', err));
        });
    });

    // Add to Cart
    document.querySelectorAll('.add-to-cart').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.dataset.productId;
            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ product_id: productId, quantity: 1 })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) throw new Error(data.message || 'Unable to add to cart');
                // Update cart indicators
                const cartCount = document.querySelector('.cart-count');
                if (cartCount) cartCount.textContent = data.cart_count;
                // Show notification
                showNotification('Added to cart!', 'success');
            })
            .catch(err => {
                console.error('Cart error:', err);
                showNotification('Error adding to cart', 'error');
            });
        });
    });
});

// Utility notification function
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-xl transition-all duration-300 transform translate-x-full ${
        type === 'success' ? 'bg-green-500 text-white' :
        type === 'error' ? 'bg-red-500 text-white' : 'bg-blue-500 text-white'
    }`;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => notification.classList.remove('translate-x-full'), 100);
    
    // Animate out
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}
