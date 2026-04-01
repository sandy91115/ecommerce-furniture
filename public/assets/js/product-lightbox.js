document.addEventListener('DOMContentLoaded', function () {
    const galleries = document.querySelectorAll('[data-product-gallery]');
    const modal = document.getElementById('product-lightbox-modal');
    const closeBtn = document.getElementById('lightbox-close');
    const swiperEl = document.querySelector('.product-lightbox-swiper');
    const productNameEl = document.getElementById('lightbox-product-name');
    const productPriceEl = document.getElementById('lightbox-product-price');
    const canHoverZoom = window.matchMedia('(hover: hover) and (pointer: fine)').matches;

    let productSwiper = null;

    function parseImages(value) {
        try {
            const parsed = JSON.parse(value || '[]');
            return Array.isArray(parsed) ? parsed : [];
        } catch (error) {
            return [];
        }
    }

    function destroySwiper() {
        if (productSwiper) {
            productSwiper.destroy(true, true);
            productSwiper = null;
        }
    }

    function closeLightbox() {
        if (!modal) {
            return;
        }

        modal.classList.add('hidden');
        document.body.style.overflow = '';
        destroySwiper();
    }

    function buildLightboxSlides(imageItems, productName) {
        if (!swiperEl) {
            return;
        }

        const wrapper = swiperEl.querySelector('.swiper-wrapper');
        if (!wrapper) {
            return;
        }

        wrapper.innerHTML = '';

        imageItems.forEach(function (item, index) {
            const slide = document.createElement('div');
            slide.className = 'swiper-slide flex items-center justify-center h-full';

            const zoomContainer = document.createElement('div');
            zoomContainer.className = 'swiper-zoom-container flex h-full w-full items-center justify-center';

            const image = document.createElement('img');
            image.src = item.url;
            image.alt = item.alt || productName || 'Product image ' + (index + 1);
            image.className = 'max-h-full max-w-full object-contain';
            image.loading = 'lazy';

            zoomContainer.appendChild(image);
            slide.appendChild(zoomContainer);
            wrapper.appendChild(slide);
        });
    }

    function openLightbox(gallery, initialIndex) {
        if (!modal || !swiperEl || typeof Swiper === 'undefined') {
            return;
        }

        const imageItems = parseImages(gallery.dataset.productImages);
        if (!imageItems.length) {
            return;
        }

        buildLightboxSlides(imageItems, gallery.dataset.productName);

        if (productNameEl) {
            productNameEl.textContent = gallery.dataset.productName || 'Product';
        }

        if (productPriceEl) {
            productPriceEl.textContent = gallery.dataset.productPrice || '';
        }

        destroySwiper();
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        productSwiper = new Swiper(swiperEl, {
            initialSlide: initialIndex || 0,
            loop: imageItems.length > 1,
            speed: 350,
            keyboard: {
                enabled: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
                dynamicBullets: true,
            },
            zoom: {
                maxRatio: 3,
            },
        });
    }

    galleries.forEach(function (gallery) {
        const mainImage = gallery.querySelector('[data-product-main-image]');
        const stage = gallery.querySelector('.product-gallery__stage');
        const thumbButtons = Array.from(gallery.querySelectorAll('[data-product-thumb]'));
        const openButtons = gallery.querySelectorAll('[data-product-lightbox-open]');
        const imageItems = parseImages(gallery.dataset.productImages);
        let currentIndex = 0;

        if (!mainImage || !stage || !imageItems.length) {
            return;
        }

        function resetZoom() {
            stage.classList.remove('is-zoomed');
            mainImage.style.transformOrigin = 'center center';
        }

        function setActiveImage(index) {
            const item = imageItems[index] || imageItems[0];
            if (!item) {
                return;
            }

            currentIndex = index;
            mainImage.src = item.url;
            mainImage.alt = item.alt || gallery.dataset.productName || 'Product image';

            thumbButtons.forEach(function (button, buttonIndex) {
                const isActive = buttonIndex === index;
                button.classList.toggle('is-active', isActive);
                button.setAttribute('aria-pressed', isActive ? 'true' : 'false');
            });

            resetZoom();
        }

        thumbButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                const nextIndex = Number(button.dataset.index || 0);
                setActiveImage(nextIndex);
            });
        });

        if (canHoverZoom) {
            stage.addEventListener('mousemove', function (event) {
                const bounds = stage.getBoundingClientRect();
                const horizontal = ((event.clientX - bounds.left) / bounds.width) * 100;
                const vertical = ((event.clientY - bounds.top) / bounds.height) * 100;

                mainImage.style.transformOrigin = horizontal + '% ' + vertical + '%';
                stage.classList.add('is-zoomed');
            });

            stage.addEventListener('mouseleave', resetZoom);
        }

        openButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                openLightbox(gallery, currentIndex);
            });
        });

        mainImage.addEventListener('error', function () {
            mainImage.src = '/assets/img/product/default.jpg';
        });

        setActiveImage(0);
    });

    if (closeBtn) {
        closeBtn.addEventListener('click', closeLightbox);
    }

    if (modal) {
        modal.addEventListener('click', function (event) {
            if (event.target === modal) {
                closeLightbox();
            }
        });
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
            closeLightbox();
        }
    });
});

