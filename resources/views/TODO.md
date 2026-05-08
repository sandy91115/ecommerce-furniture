# Shop V3 Dynamic Upgrade TODO

- [x] 1. Create backups: shop-v1.blade.php (old sidebar shop) and shop-v3-static.blade.php (old static v3)
- [x] 2. Enhance app/Http/Controllers/ShopController.php: add sort_by (latest,price-asc,price-desc,name-asc,name-desc), min_price, max_price filters
- [x] 3. Create resources/views/includes/Shop/shops-v3.blade.php: dynamic @foreach product cards v3 style (5 col grid)
- [x] 4. Update resources/views/shop-v3.blade.php: dynamic $products @forelse grid (use shops-v3 partial or direct), filter form GET with category/sort/price, pagination, category filter list
- [x] 5. Replace resources/views/shop.blade.php with dynamic v3 content from step 4
- [x] 6. Test /shop: products load, filters/category change query params, pagination works
- [ ] 7. Optional: Add price slider JS, AJAX filtering
