const { chromium } = require('playwright');

const TARGET_URL = 'http://localhost:8000';
const VIEWPORT = { width: 375, height: 812 };

(async () => {
  const browser = await chromium.launch({ headless: true });
  const page = await browser.newPage({ viewport: VIEWPORT });

  await page.goto(TARGET_URL, { waitUntil: 'domcontentloaded', timeout: 30000 });
  await page.waitForTimeout(1200);

  const productCategoryHeading = page.getByText('Product Category', { exact: true }).first();
  await productCategoryHeading.scrollIntoViewIfNeeded();
  await page.waitForTimeout(700);
  await page.screenshot({ path: 'category-mobile-check.png', fullPage: false });

  const footer = page.locator('.site-footer-light').first();
  await footer.scrollIntoViewIfNeeded();
  await page.waitForTimeout(700);
  await page.screenshot({ path: 'footer-mobile-check.png', fullPage: false });

  console.log(JSON.stringify({
    productCategoryVisible: await productCategoryHeading.isVisible(),
    footerVisible: await footer.isVisible(),
  }, null, 2));

  await browser.close();
})().catch((error) => {
  console.error(error);
  process.exit(1);
});
