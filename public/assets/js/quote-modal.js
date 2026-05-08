document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('quotationModal');
    const form = document.getElementById('quotationForm');

    if (!modal) {
        return;
    }

    const productIdField = document.getElementById('modalProductId');
    const productName = document.getElementById('modalProductName');
    const productPrice = document.getElementById('modalProductPrice');
    const statusBox = document.getElementById('quotationFormStatus');
    const submitButton = form ? form.querySelector('button[type="submit"]') : null;
    const submitButtonLabel = submitButton ? submitButton.querySelector('span') : null;
    const closeButtons = modal.querySelectorAll('[data-quotation-close]');
    const errorFields = modal.querySelectorAll('[data-error-for]');

    function clearStatus() {
        if (!statusBox) {
            return;
        }

        statusBox.className = 'hidden rounded-[16px] border px-4 py-3 text-sm';
        statusBox.textContent = '';
    }

    function setStatus(message, type) {
        if (!statusBox) {
            return;
        }

        const stateClasses = {
            success: 'border-emerald-200 bg-emerald-50 text-emerald-700',
            error: 'border-red-200 bg-red-50 text-red-700',
            info: 'border-blue-200 bg-blue-50 text-blue-700'
        };

        statusBox.className = `rounded-[16px] border px-4 py-3 text-sm ${stateClasses[type] || stateClasses.info}`;
        statusBox.textContent = message;
    }

    function clearErrors() {
        if (!errorFields) return;
        errorFields.forEach(function (errorField) {
            errorField.textContent = '';
            errorField.classList.add('hidden');
        });

        if (form) {
            form.querySelectorAll('input, textarea').forEach(function (field) {
                field.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                field.removeAttribute('aria-invalid');
            });
        }

        clearStatus();
    }

    function setFieldError(fieldName, message) {
        if (!form) return;
        const field = form.querySelector(`[name="${fieldName}"]`);
        const errorField = modal.querySelector(`[data-error-for="${fieldName}"]`);

        if (field) {
            field.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
            field.setAttribute('aria-invalid', 'true');
        }

        if (errorField) {
            errorField.textContent = message;
            errorField.classList.remove('hidden');
        }
    }

    function setSubmittingState(isSubmitting) {
        if (!submitButton) {
            return;
        }

        submitButton.disabled = isSubmitting;
        submitButton.classList.toggle('pointer-events-none', isSubmitting);
        submitButton.classList.toggle('opacity-70', isSubmitting);

        if (submitButtonLabel) {
            submitButtonLabel.textContent = isSubmitting ? 'Sending...' : 'Send Quote Request';
        }
    }

    function resetFormFields() {
        if (!form) return;
        form.querySelectorAll('input, textarea').forEach(function (field) {
            if (field.type === 'hidden' || field.name === '_token') {
                return;
            }

            field.value = field.dataset.defaultValue || '';
        });
    }

    function updateProductMeta(triggerButton) {
        if (!triggerButton) {
            return;
        }

        if (productIdField && triggerButton.dataset.productId) {
            productIdField.value = triggerButton.dataset.productId;
        }

        if (productName && triggerButton.dataset.productName) {
            productName.textContent = triggerButton.dataset.productName;
        }

        if (productPrice && triggerButton.dataset.productPrice) {
            productPrice.textContent = triggerButton.dataset.productPrice;
        }
    }

    function openModal(triggerButton) {
        updateProductMeta(triggerButton);
        modal.classList.remove('hidden');
        modal.classList.add('flex'); // Ensure flex is there when hidden is removed
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('overflow-hidden');

        window.setTimeout(function () {
            if (form) {
                const firstField = form.querySelector('input[name="customer_name"]');
                if (firstField) {
                    firstField.focus();
                }
            }
        }, 100);
    }

    window.closeQuotationModal = function (options) {
        const settings = options || {};

        modal.classList.add('hidden');
        modal.classList.remove('flex');
        modal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('overflow-hidden');

        if (settings.resetForm) {
            resetFormFields();
            clearErrors();
        }
    };

    // Use event delegation for triggers
    document.addEventListener('click', function (event) {
        const trigger = event.target.closest('[data-quote-trigger]');
        if (trigger) {
            clearErrors();
            openModal(trigger);
        }
    });

    closeButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            window.closeQuotationModal();
        });
    });

    modal.addEventListener('click', function (event) {
        if (event.target === modal) {
            window.closeQuotationModal();
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
            window.closeQuotationModal();
        }
    });

    if (form) {
        form.addEventListener('submit', async function (event) {
            if (!window.fetch) {
                return;
            }

            event.preventDefault();
            clearErrors();
            setSubmittingState(true);

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json().catch(function () {
                    return {};
                });

                if (response.ok && data.success) {
                    window.showNotification(data.message || 'Quote request submitted successfully.', 'success');
                    window.closeQuotationModal({ resetForm: true });
                    return;
                }

                if (response.status === 422 && data.errors) {
                    setStatus(data.message || 'Please check the form and try again.', 'error');

                    Object.keys(data.errors).forEach(function (fieldName) {
                        if (Array.isArray(data.errors[fieldName]) && data.errors[fieldName].length > 0) {
                            setFieldError(fieldName, data.errors[fieldName][0]);
                        }
                    });

                    return;
                }

                const fallbackMessage = data.message || 'Error submitting quote request. Please try again.';
                setStatus(fallbackMessage, 'error');
                window.showNotification(fallbackMessage, 'error');
            } catch (error) {
                console.error('Quote submission error:', error);
                setStatus('Error submitting quote request. Please try again.', 'error');
                window.showNotification('Error submitting quote request. Please try again.', 'error');
            } finally {
                setSubmittingState(false);
            }
        });
    }

    window.showNotification = function (message, type) {
        const variant = type || 'info';
        const badgeText = variant === 'success' ? 'OK' : variant === 'error' ? 'ERR' : 'INFO';
        const notification = document.createElement('div');

        notification.className = `fixed top-4 right-4 z-[10000] p-4 rounded-xl shadow-2xl border max-w-sm transition-all duration-300 transform translate-x-full ${
            variant === 'success' ? 'bg-emerald-500 text-white border-emerald-300' :
            variant === 'error' ? 'bg-red-500 text-white border-red-300' :
            'bg-blue-500 text-white border-blue-300'
        }`;

        notification.innerHTML = `
            <div class="flex items-center gap-3">
                <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-white/20 text-xs font-bold">${badgeText}</div>
                <div>${message}</div>
            </div>
        `;

        document.body.appendChild(notification);

        requestAnimationFrame(function () {
            notification.classList.remove('translate-x-full');
        });

        window.setTimeout(function () {
            notification.classList.add('translate-x-full');

            window.setTimeout(function () {
                notification.remove();
            }, 300);
        }, 4000);
    };

    if (modal.dataset.openOnLoad === 'true') {
        const initialTrigger = document.querySelector('[data-quote-trigger]');
        if (initialTrigger) {
            openModal(initialTrigger);
        }
    }
});
