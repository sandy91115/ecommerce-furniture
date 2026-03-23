# Fix Dashboard Soft Deletes Error

## Steps:
- [x] 1. Create migration for adding soft deletes to orders table
- [x] 2. Run php artisan migrate
- [x] 3. Verify dashboard loads at /admin/dashboard
- [x] 4. Clean up any incomplete migrations if needed
- [x] 5. Test Order::count() and related queries
