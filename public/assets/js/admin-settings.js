(function () {
    function getMetaContent(name) {
        const el = document.querySelector(`meta[name="${name}"]`);
        return el ? el.getAttribute('content') : null;
    }

    function currentTheme() {
        return document.documentElement.classList.contains('dark') ? 'dark' : 'light';
    }

    function setTheme(theme) {
        document.documentElement.classList.toggle('dark', theme === 'dark');
    }

    async function persistTheme(theme) {
        const updateUrl = getMetaContent('admin-settings-update-url');
        const csrfToken = getMetaContent('csrf-token');

        if (!updateUrl || !csrfToken) return;

        try {
            const response = await fetch(updateUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ admin_theme_mode: theme }),
            });

            const data = await response.json().catch(() => null);

            if (!response.ok || !data || data.success !== true) {
                console.error('Theme save failed:', { status: response.status, data });
            }
        } catch (error) {
            console.error('Theme save error:', error);
        }
    }

    function attachFilePreviews() {
        document.querySelectorAll('input[type="file"]').forEach((input) => {
            input.addEventListener('change', function () {
                const file = input.files && input.files[0];
                if (!file) return;

                const reader = new FileReader();
                reader.onload = function (loadEvent) {
                    let preview = input.parentElement?.querySelector('.preview-img');

                    if (!preview) {
                        preview = document.createElement('img');
                        preview.className =
                            'preview-img max-w-32 h-16 object-contain border rounded-lg shadow mt-2 mb-2';
                        input.parentElement?.insertBefore(preview, input.nextSibling);
                    }

                    preview.src = loadEvent.target?.result;
                };

                reader.readAsDataURL(file);
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        const themeToggle = document.querySelector('[data-theme-toggle]');

        try {
            localStorage.setItem('adminTheme', currentTheme());
        } catch (_) {
            // Ignore storage errors (e.g., private mode)
        }

        if (themeToggle) {
            themeToggle.addEventListener('click', async function () {
                const next = currentTheme() === 'dark' ? 'light' : 'dark';
                setTheme(next);

                try {
                    localStorage.setItem('adminTheme', next);
                } catch (_) {
                    // Ignore storage errors
                }

                await persistTheme(next);
            });
        }

        attachFilePreviews();
    });
})();
