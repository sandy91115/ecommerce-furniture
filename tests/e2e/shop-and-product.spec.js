import { expect, test } from '@playwright/test';
import { findFirstShopProduct, sortedCopy } from './helpers.js';

test.describe('shop listing', () => {
  test('shows product cards with working primary links and images', async ({ page }) => {
    await page.goto('/shop', { waitUntil: 'domcontentloaded' });

    const cards = page.locator('.shop-product-card');
    await expect(cards.first()).toBeVisible();

    const firstCard = cards.first();
    const titleLink = firstCard.locator('.shop-product-card__title a');
    const image = firstCard.locator('.shop-product-card__image');

    await expect(titleLink).toHaveAttribute('href', /\/product\//);
    await expect(image).toBeVisible();
    expect(((await image.getAttribute('alt')) || '').trim()).not.toBe('');
    expect(await image.evaluate((node) => node.complete && node.naturalWidth > 0)).toBe(true);
  });

  test('sorts products by name without leaving the page', async ({ page }) => {
    await page.goto('/shop', { waitUntil: 'domcontentloaded' });
    await expect(page.locator('.shop-product-card').first()).toBeVisible();

    const responsePromise = page.waitForResponse(
      (response) =>
        response.url().includes('/shop') &&
        response.url().includes('sort_by=name-asc') &&
        response.request().headers()['x-requested-with'] === 'XMLHttpRequest'
    );

    await page.locator('#shop_sort + .nice-select').click();
    await page.locator('#shop_sort + .nice-select .option[data-value="name-asc"]').click();
    const response = await responsePromise;

    expect(response.ok()).toBe(true);
    await expect(page).toHaveURL(/sort_by=name-asc/);

    const names = await page.locator('.shop-product-card__title a').evaluateAll((links) =>
      links.map((link) => link.textContent.trim()).filter(Boolean)
    );

    expect(names.length).toBeGreaterThan(0);
    expect(names).toEqual(sortedCopy(names));
  });

  test('shows an empty state for a price filter with no matches', async ({ page }) => {
    await page.goto('/shop', { waitUntil: 'domcontentloaded' });

    await page.locator('#min_price').fill('99999999');
    const responsePromise = page.waitForResponse(
      (response) =>
        response.url().includes('/shop') &&
        response.url().includes('min_price=99999999') &&
        response.request().headers()['x-requested-with'] === 'XMLHttpRequest'
    );

    await page.locator('.shop-filter-submit').click();
    const response = await responsePromise;

    expect(response.ok()).toBe(true);
    await expect(page.locator('.shop-empty-state')).toContainText('No products found');
    await expect(page).toHaveURL(/min_price=99999999/);
  });
});

test.describe('product detail and cart', () => {
  test('opens gallery lightbox and changes desktop detail tabs', async ({ page }) => {
    const product = await findFirstShopProduct(page, { quotation: false });
    test.skip(!product, 'No purchasable product exists in the local database.');

    await page.goto(product.href, { waitUntil: 'networkidle' });

    await expect(page.locator('.product-gallery__main-image')).toBeVisible();
    await expect(page.getByRole('heading', { name: product.title }).first()).toBeVisible();

    await page.locator('[data-product-lightbox-open]').click();
    await expect(page.locator('#product-lightbox-modal')).toBeVisible();
    await page.locator('#lightbox-close').click();
    await expect(page.locator('#product-lightbox-modal')).toBeHidden();

    await page.getByRole('link', { name: 'Technical Specifications' }).click();
    await expect(page.locator('#content2')).toBeVisible();
    await expect(page.locator('#content2')).toContainText(/Technical Specifications|Availability|SKU|Category/i);
  });

  test('adds a product to cart, updates quantity, and removes it', async ({ page }) => {
    const product = await findFirstShopProduct(page, { quotation: false });
    test.skip(!product, 'No purchasable product exists in the local database.');

    await page.goto(product.href, { waitUntil: 'domcontentloaded' });

    const quantityInput = page.locator('#productQuantity');
    await quantityInput.fill('2');
    await expect(quantityInput).toHaveValue('2');

    await page.getByRole('button', { name: 'Add to Cart' }).click();
    await page.waitForURL(/\/cart$/);

    const item = page.locator('[data-cart-item]').first();
    await expect(page.getByRole('heading', { name: 'Shopping Cart' })).toBeVisible();
    await expect(item).toContainText(product.title);
    await expect(item.locator('[data-cart-quantity]')).toHaveValue('2');

    const updateResponse = page.waitForResponse((response) => response.url().includes('/cart/update'));
    await item.locator('[data-cart-increment]').click();
    expect((await updateResponse).ok()).toBe(true);
    await expect(item.locator('[data-cart-quantity]')).toHaveValue('3');
    await expect(page.locator('#cart-total')).toBeVisible();

    const removeResponse = page.waitForResponse((response) => response.url().includes('/cart/remove'));
    await item.locator('[data-cart-remove]').click();
    expect((await removeResponse).ok()).toBe(true);
    await expect(page.locator('body')).toContainText('Your cart is empty');
  });

  test('opens quotation modal and validates required quote fields before submit', async ({ page }) => {
    const product = await findFirstShopProduct(page, { quotation: true });
    test.skip(!product, 'No quotation product exists in the local database.');

    await page.goto(product.href, { waitUntil: 'domcontentloaded' });
    await page.getByRole('button', { name: 'Quote Now' }).click();

    const modal = page.locator('#quotationModal');
    const form = modal.locator('#quotationForm');

    await expect(modal).toBeVisible();
    await expect(modal.locator('#modalProductName')).toContainText(product.title);
    expect(await form.evaluate((node) => node.checkValidity())).toBe(false);

    await modal.locator('#quotationCustomerName').fill('Playwright Tester');
    await modal.locator('#quotationEmail').fill('tester@example.com');
    await modal.locator('#quotationPhone').fill('+919876543210');
    await modal.locator('#quotationCity').fill('Delhi');
    await modal.locator('#quotationPincode').fill('110092');
    await modal.locator('#quotationMessage').fill('Need size, finish, and delivery quote details.');

    expect(await form.evaluate((node) => node.checkValidity())).toBe(true);

    await modal.getByRole('button', { name: 'Cancel' }).click();
    await expect(modal).toBeHidden();
  });
});
