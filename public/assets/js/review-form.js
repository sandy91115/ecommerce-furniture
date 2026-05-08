function initReviewExplorer() {
    const explorer = document.querySelector('[data-review-explorer]');
    if (!explorer) return;

    const reviewList = explorer.querySelector('[data-review-list]');
    const reviewItems = reviewList ? Array.from(reviewList.querySelectorAll('[data-review-item]')) : [];
    const visibleCount = explorer.querySelector('[data-review-visible-count]');
    const activeLabel = explorer.querySelector('[data-review-active-label]');
    const emptyState = explorer.querySelector('[data-review-empty-state]');
    const clearButton = explorer.querySelector('[data-review-clear]');
    const ratingButtons = Array.from(explorer.querySelectorAll('[data-review-rating-filter]'));
    const sortButtons = Array.from(explorer.querySelectorAll('[data-review-sort-btn]'));

    if (!reviewList || !reviewItems.length) {
        if (clearButton) clearButton.classList.add('hidden');
        return;
    }

    let activeRating = null;
    let activeSort = sortButtons.find((button) => button.classList.contains('is-active'))?.dataset.reviewSortBtn || 'top';

    const labelMap = {
        top: 'Top reviews',
        latest: 'Most recent reviews',
        highest: 'Highest rated reviews',
        lowest: 'Lowest rated reviews',
    };

    const getNumber = (item, key) => Number.parseInt(item.dataset[key] || '0', 10);

    const getComparator = () => {
        switch (activeSort) {
            case 'latest':
                return (a, b) => getNumber(b, 'reviewCreated') - getNumber(a, 'reviewCreated');
            case 'highest':
                return (a, b) => (getNumber(b, 'reviewRating') - getNumber(a, 'reviewRating')) || (getNumber(b, 'reviewCreated') - getNumber(a, 'reviewCreated'));
            case 'lowest':
                return (a, b) => (getNumber(a, 'reviewRating') - getNumber(b, 'reviewRating')) || (getNumber(b, 'reviewCreated') - getNumber(a, 'reviewCreated'));
            case 'top':
            default:
                return (a, b) => (getNumber(b, 'reviewRating') - getNumber(a, 'reviewRating')) || (getNumber(b, 'reviewCreated') - getNumber(a, 'reviewCreated'));
        }
    };

    const applyState = () => {
        [...reviewItems].sort(getComparator()).forEach((item) => reviewList.appendChild(item));

        let visibleItems = 0;

        reviewItems.forEach((item) => {
            const matchesRating = activeRating === null || getNumber(item, 'reviewRating') === activeRating;
            item.hidden = !matchesRating;

            if (matchesRating) {
                visibleItems += 1;
            }
        });

        ratingButtons.forEach((button) => {
            const isActive = Number.parseInt(button.dataset.reviewRatingFilter || '0', 10) === activeRating;
            button.classList.toggle('is-active', isActive);
        });

        sortButtons.forEach((button) => {
            button.classList.toggle('is-active', button.dataset.reviewSortBtn === activeSort);
        });

        if (visibleCount) {
            visibleCount.textContent = String(visibleItems);
        }

        if (activeLabel) {
            activeLabel.textContent = activeRating === null
                ? (labelMap[activeSort] || 'Top reviews')
                : `${activeRating}-star reviews`;
        }

        if (emptyState) {
            emptyState.classList.toggle('hidden', visibleItems !== 0);
        }

        if (clearButton) {
            clearButton.classList.toggle('hidden', activeRating === null);
        }
    };

    ratingButtons.forEach((button) => {
        button.addEventListener('click', () => {
            const clickedRating = Number.parseInt(button.dataset.reviewRatingFilter || '0', 10);
            activeRating = activeRating === clickedRating ? null : clickedRating;
            applyState();
        });
    });

    sortButtons.forEach((button) => {
        button.addEventListener('click', () => {
            activeSort = button.dataset.reviewSortBtn || 'top';
            applyState();
        });
    });

    if (clearButton) {
        clearButton.addEventListener('click', () => {
            activeRating = null;
            applyState();
        });
    }

    applyState();
}

function initReviewForm() {
    const form = document.getElementById('reviewForm');
    if (!form) return;

    const stars = Array.from(form.querySelectorAll('.star'));
    let currentRating = Number.parseInt(form.querySelector('input[name="rating"]:checked')?.value || '0', 10);

    const updateStars = (rating) => {
        stars.forEach((star) => {
            const starRating = Number.parseInt(star.dataset.rating || '0', 10);
            star.classList.toggle('filled', starRating <= rating);
        });
    };

    stars.forEach((star) => {
        const starRating = Number.parseInt(star.dataset.rating || '0', 10);

        star.addEventListener('mouseover', () => updateStars(starRating));
        star.addEventListener('focus', () => updateStars(starRating));
        star.addEventListener('mouseout', () => updateStars(currentRating));
        star.addEventListener('blur', () => updateStars(currentRating));
        star.addEventListener('click', () => {
            const radio = form.querySelector(`input[name="rating"][value="${starRating}"]`);

            currentRating = starRating;

            if (radio) {
                radio.checked = true;
            }

            updateStars(currentRating);
        });
    });

    updateStars(currentRating);

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const formData = new FormData(form);
        const submitButton = form.querySelector('button[type="submit"]');
        const originalText = submitButton.innerHTML;
        const formContainer = form.closest('.review-form');

        submitButton.disabled = true;
        submitButton.innerHTML = '<span class="flex items-center gap-2"><svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none" opacity="0.25"/><path fill="currentColor" opacity="1" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg> Submitting...</span>';

        try {
            formContainer.querySelectorAll('.review-form-feedback').forEach((node) => node.remove());

            const response = await fetch('/reviews', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json',
                },
            });

            const data = await response.json();

            if (!response.ok) {
                const firstError = data.errors ? Object.values(data.errors)[0]?.[0] : null;
                throw new Error(firstError || data.message || 'Please fix the highlighted fields and try again.');
            }

            if (data.success) {
                form.reset();
                currentRating = 0;
                updateStars(0);

                const successDiv = document.createElement('div');
                successDiv.className = 'review-form-feedback mb-4 rounded-[20px] border border-green-200 bg-green-50 px-4 py-3 text-green-700';
                successDiv.innerHTML = `<svg class="mr-2 inline h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>${data.message || 'Review submitted successfully! It will appear after admin approval.'}`;
                formContainer.insertBefore(successDiv, form);

                setTimeout(() => successDiv.remove(), 5000);

                if (window.loadReviews) window.loadReviews();
            } else {
                alert(data.message || 'Please fix errors and try again.');
            }
        } catch (error) {
            console.error('Review submission error:', error);
            alert(error.message || 'Network error. Please check your connection and try again.');
        } finally {
            submitButton.disabled = false;
            submitButton.innerHTML = originalText;
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    initReviewExplorer();
    initReviewForm();
});

