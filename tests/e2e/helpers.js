import { expect } from '@playwright/test';

export const criticalErrorPattern =
  /Whoops|Server Error|ErrorException|Fatal error|SQLSTATE|Undefined variable|Undefined array key/i;

export async function expectHealthyPage(page, path, expectedText) {
  const response = await page.goto(path, { waitUntil: 'domcontentloaded' });

  expect(response, `Expected ${path} to return a response`).not.toBeNull();
  expect(response.status(), `${path} HTTP status`).toBeLessThan(400);
  await expect(page.locator('body')).toBeVisible();
  await expect(page.locator('body')).not.toContainText(criticalErrorPattern);

  if (expectedText) {
    await expect(page.locator('body')).toContainText(expectedText);
  }

  return response;
}

export async function findFirstShopProduct(page, { quotation } = {}) {
  await page.goto('/shop', { waitUntil: 'domcontentloaded' });

  const cards = page.locator('.shop-product-card');
  const count = await cards.count();

  for (let index = 0; index < count; index += 1) {
    const card = cards.nth(index);
    const priceText = ((await card.locator('.shop-product-card__price').textContent()) || '').trim();
    const isQuotationProduct =
      priceText.includes('Quote Now') || (await card.locator('.shop-product-card__badge').count()) > 0;

    if (quotation !== undefined && quotation !== isQuotationProduct) {
      continue;
    }

    const titleLink = card.locator('.shop-product-card__title a');
    const href = await titleLink.getAttribute('href');

    return {
      href,
      isQuotationProduct,
      title: ((await titleLink.textContent()) || '').trim(),
      priceText,
    };
  }

  return null;
}

export function sortedCopy(values) {
  return [...values].sort((left, right) => left.localeCompare(right, undefined, { sensitivity: 'base' }));
}
