import { expect, test } from '@playwright/test';
import { expectHealthyPage } from './helpers.js';

const publicPages = [
  ['home page', '/', /CAROM STUDIOS|Furniture|Wooden|Shop/i],
  ['shop page', '/shop', 'Shop'],
  ['about page', '/about', /About|Story|CAROM/i],
  ['contact page', '/contact', /Contact|Get in Touch/i],
  ['faq page', '/faq', /FAQ|Question/i],
  ['blog page', '/blog', /Blog|News|Articles/i],
  ['cart page', '/cart', /Shopping Cart|Your cart is empty/i],
  ['login page', '/login', 'Access your account'],
  ['register page', '/register', 'Create your account'],
  ['privacy policy', '/privacy-policy', /Privacy|Policy/i],
  ['terms page', '/terms-and-conditions', /Terms|Conditions/i],
  ['return policy', '/return-policy', /Return|Policy/i],
];

test.describe('public page health', () => {
  for (const [name, path, expectedText] of publicPages) {
    test(`${name} loads without a server error`, async ({ page }) => {
      await expectHealthyPage(page, path, expectedText);
      expect((await page.title()).trim(), `${path} page title`).not.toBe('');
    });
  }

  test('unauthenticated protected pages redirect to the right login screens', async ({ page }) => {
    await page.goto('/checkout', { waitUntil: 'domcontentloaded' });
    await expect(page).toHaveURL(/\/login$/);
    await expect(page.locator('body')).toContainText('Access your account');

    await page.goto('/admin', { waitUntil: 'domcontentloaded' });
    await expect(page).toHaveURL(/\/panel\/login$/);
    await expect(page.locator('body')).toContainText('Admin Login');
  });
});

test.describe('seo endpoints', () => {
  const endpoints = [
    ['/robots.txt', 'text/plain', /User-agent:\s*\*/],
    ['/sitemap.xml', 'application/xml', /<sitemapindex\b/],
    ['/sitemap-products.xml', 'application/xml', /<urlset\b/],
    ['/sitemap-categories.xml', 'application/xml', /<urlset\b/],
    ['/sitemap-pages.xml', 'application/xml', /<urlset\b/],
    ['/sitemap-images.xml', 'application/xml', /<urlset\b/],
  ];

  for (const [path, contentType, expectedBody] of endpoints) {
    test(`${path} returns expected content`, async ({ request }) => {
      const response = await request.get(path);
      const body = await response.text();

      expect(response.status(), `${path} HTTP status`).toBe(200);
      expect(response.headers()['content-type']).toContain(contentType);
      expect(body).toMatch(expectedBody);

      if (path.endsWith('.xml')) {
        expect(body.trim()).toMatch(/^<\?xml/);
      }
    });
  }
});

test.describe('mobile public layout', () => {
  test.use({ viewport: { width: 390, height: 844 }, isMobile: true });

  test('shop and cart are usable at phone width', async ({ page }) => {
    await expectHealthyPage(page, '/shop', 'Shop');
    await expect(page.locator('.shop-filter-toolbar')).toBeVisible();
    await expect(page.locator('.shop-product-card, .shop-empty-state').first()).toBeVisible();

    await expectHealthyPage(page, '/cart', /Shopping Cart|Your cart is empty/i);
    await expect(page.getByRole('link', { name: /Start Shopping|Continue Shopping/i }).first()).toBeVisible();
  });
});
