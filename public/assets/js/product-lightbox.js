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
<<<<<<< HEAD
        if (!modal || !swiperEl) {
=======
        if (!modal || !swiperEl || typeof Swiper === 'undefined') {
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
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
<<<<<<< HEAD
        modal.classList.remove('is-static-lightbox');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        if (typeof Swiper === 'undefined') {
            const slides = Array.from(swiperEl.querySelectorAll('.swiper-slide'));
            const activeIndex = initialIndex || 0;

            modal.classList.add('is-static-lightbox');
            slides.forEach(function (slide, index) {
                slide.classList.toggle('is-static-active', index === activeIndex);
            });

            return;
        }

=======
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
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

<<<<<<< HEAD
        function updateMainImage(item) {
            const displaySrc = item.medium || item.url;

            if (!displaySrc) {
                return;
            }

            mainImage.src = displaySrc;

            if (item.srcset) {
                mainImage.srcset = item.srcset;
            } else {
                mainImage.removeAttribute('srcset');
            }

            if (!mainImage.hasAttribute('sizes')) {
                mainImage.sizes = '(max-width: 1023px) 100vw, 58vw';
            }
        }

=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        function setActiveImage(index) {
            const item = imageItems[index] || imageItems[0];
            if (!item) {
                return;
            }

            currentIndex = index;
<<<<<<< HEAD
            updateMainImage(item);
=======
            mainImage.src = item.url;
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
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

<<<<<<< HEAD
        function keepActiveThumbVisible() {
            const thumbs = gallery.querySelector('.product-gallery__thumbs');
            const activeThumb = thumbButtons[currentIndex];

            if (!thumbs || !activeThumb) {
                return;
            }

            const targetLeft = activeThumb.offsetLeft - (thumbs.clientWidth - activeThumb.offsetWidth) / 2;
            thumbs.scrollTo({ left: Math.max(0, targetLeft), behavior: 'smooth' });
        }

=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
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

<<<<<<< HEAD
        if (thumbButtons.length > 1) {
            let autoTimer = window.setInterval(function () {
                setActiveImage((currentIndex + 1) % imageItems.length);
                keepActiveThumbVisible();
            }, 3500);

            gallery.addEventListener('mouseenter', function () {
                window.clearInterval(autoTimer);
            });

            gallery.addEventListener('mouseleave', function () {
                autoTimer = window.setInterval(function () {
                    setActiveImage((currentIndex + 1) % imageItems.length);
                    keepActiveThumbVisible();
                }, 3500);
            });
        }

=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
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
<<<<<<< HEAD

    document.querySelectorAll('[data-simple-carousel]').forEach(function (carousel) {
        const track = carousel.querySelector('[data-carousel-track]');
        const next = carousel.querySelector('[data-carousel-next]');
        const prev = carousel.querySelector('[data-carousel-prev]');

        if (!track) {
            return;
        }

        function scrollByCard(direction) {
            const item = track.querySelector('[data-carousel-item]');
            const amount = item ? item.getBoundingClientRect().width + 16 : track.clientWidth * 0.85;
            track.scrollBy({ left: amount * direction, behavior: 'smooth' });

            window.setTimeout(function () {
                if (direction > 0 && track.scrollLeft + track.clientWidth >= track.scrollWidth - 8) {
                    track.scrollTo({ left: 0, behavior: 'smooth' });
                } else if (direction < 0 && track.scrollLeft <= 8) {
                    track.scrollTo({ left: track.scrollWidth, behavior: 'smooth' });
                }
            }, 360);
        }

        if (next) {
            next.addEventListener('click', function () {
                scrollByCard(1);
            });
        }

        if (prev) {
            prev.addEventListener('click', function () {
                scrollByCard(-1);
            });
        }

        if (carousel.dataset.autoplay === 'true') {
            let timer = window.setInterval(function () {
                scrollByCard(1);
            }, 4200);

            carousel.addEventListener('mouseenter', function () {
                window.clearInterval(timer);
            });

            carousel.addEventListener('mouseleave', function () {
                timer = window.setInterval(function () {
                    scrollByCard(1);
                }, 4200);
            });
        }
    });

    const relatedSlider = document.querySelector('[data-related-slider]');
    const relatedNext = document.querySelector('[data-related-next]');
    const relatedPrev = document.querySelector('[data-related-prev]');

    if (relatedSlider) {
        function scrollRelated(direction) {
            const item = relatedSlider.querySelector('.related-product-card');
            const amount = item ? item.getBoundingClientRect().width + 16 : relatedSlider.clientWidth * 0.85;
            relatedSlider.scrollBy({ left: amount * direction, behavior: 'smooth' });
        }

        if (relatedNext) {
            relatedNext.addEventListener('click', function () {
                scrollRelated(1);
            });
        }

        if (relatedPrev) {
            relatedPrev.addEventListener('click', function () {
                scrollRelated(-1);
            });
        }
    }
});
=======
});

>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
