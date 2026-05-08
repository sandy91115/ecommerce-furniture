import { expect, test } from '@playwright/test';

test.describe('customer authentication forms', () => {
  test('login form toggles password visibility and reports invalid credentials', async ({ page }) => {
    await page.goto('/login', { waitUntil: 'domcontentloaded' });

    const password = page.locator('#password');
    await expect(page.getByRole('heading', { name: 'Access your account' })).toBeVisible();
    await expect(password).toHaveAttribute('type', 'password');

    await page.locator('[data-toggle-password="password"]').click();
    await expect(password).toHaveAttribute('type', 'text');

    await page.locator('[data-toggle-password="password"]').click();
    await expect(password).toHaveAttribute('type', 'password');

    await page.locator('#email').fill(`missing-${Date.now()}@example.test`);
    await password.fill('not-a-real-password');
    await page.getByRole('button', { name: 'Sign in' }).click();

    await expect(page.locator('.auth-feedback--error')).toContainText('Invalid credentials.');
  });

  test('register form keeps the full phone value in sync with country picker', async ({ page }) => {
    await page.goto('/register', { waitUntil: 'domcontentloaded' });

    await expect(page.getByRole('heading', { name: 'Create your account' })).toBeVisible();

    await page.locator('[data-country-toggle]').click();
    await page.locator('[data-country-option][data-code="+1"]').click();
    await page.locator('#phone_number').fill('4155552671');

    await expect(page.locator('#phone_country_code')).toHaveValue('+1');
    await expect(page.locator('#phone')).toHaveValue('+14155552671');

    const password = page.locator('#password');
    const confirmPassword = page.locator('#password_confirmation');

    await expect(password).toHaveAttribute('type', 'password');
    await page.locator('[data-toggle-password="password"]').click();
    await expect(password).toHaveAttribute('type', 'text');

    await expect(confirmPassword).toHaveAttribute('type', 'password');
    await page.locator('[data-toggle-password="password_confirmation"]').click();
    await expect(confirmPassword).toHaveAttribute('type', 'text');
  });
});
