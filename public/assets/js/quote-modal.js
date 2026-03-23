// Quote Modal JavaScript Handler
document.addEventListener('DOMContentLoaded', function() {
    // Quote modal elements
    const modal = document.getElementById('quotationModal');
    const form = document.getElementById('quotationForm');
    
    if (!modal || !form) return;
    
    // Open modal on Quote Now button click
    document.querySelectorAll('[data-quote-trigger]').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const productName = this.dataset.productName;
            const productPrice = this.dataset.productPrice;
            
            // Populate form fields
            document.getElementById('modalProductId').value = productId;
            document.getElementById('modalProductName').textContent = productName;
            document.getElementById('modalProductPrice').textContent = `Starting from $${productPrice}`;
            
            // Show modal - ensure centered positioning
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            
            // Focus first input
            setTimeout(() => {
                form.querySelector('input[name="customer_name"]').focus();
            }, 100);
        });
    });
    
    // Close modal functions
    window.closeQuotationModal = function() {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        form.reset();
    };
    
    // Close on backdrop click
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeQuotationModal();
        }
    });
    
    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeQuotationModal();
        }
    });
    
    // Form submission via AJAX
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        const productSlug = new URLSearchParams(window.location.search).get('slug') || 
                           document.querySelector('[data-product-slug]')?.dataset.productSlug ||
                           window.location.pathname.split('/').pop();
        
        fetch(`/quotation/${productSlug}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Quote request submitted successfully!', 'success');
                closeQuotationModal();
            } else {
                showNotification(data.message || 'Error submitting quote request', 'error');
            }
        })
        .catch(error => {
            console.error('Quote submission error:', error);
            showNotification('Error submitting quote request', 'error');
        });
    });
    
    // Utility notification function (shared)
    window.showNotification = function(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-[10000] p-4 rounded-xl shadow-2xl border max-w-sm transition-all duration-300 transform translate-x-full ${
            type === 'success' ? 'bg-emerald-500 text-white border-emerald-300' :
            type === 'error' ? 'bg-red-500 text-white border-red-300' : 
            'bg-blue-500 text-white border-blue-300'
        }`;
        notification.innerHTML = `
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0">
                    ${type === 'success' ? '✅' : type === 'error' ? '❌' : 'ℹ️'}
                </div>
                <div>${message}</div>
            </div>
        `;
        document.body.appendChild(notification);
        
        requestAnimationFrame(() => notification.classList.remove('translate-x-full'));
        
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => notification.remove(), 300);
        }, 4000);
    };
});

