<?php

use App\Models\Category;
use App\Models\Product;

function createQuotationProduct(array $overrides = []): Product
{
    $category = Category::create([
        'name' => 'Quotation Category',
        'slug' => 'quotation-category',
        'status' => 'active',
    ]);

    return Product::create(array_merge([
        'category_id' => $category->id,
        'name' => 'Custom Sofa',
        'slug' => 'custom-sofa',
        'sku' => 'SOFA-QUOTE-001',
        'price' => 10000,
        'stock' => 5,
        'short_description' => 'Custom quotation product',
        'description' => 'Custom quotation product description',
        'status' => 'active',
        'product_type' => 'quotation',
    ], $overrides));
}

it('redirects quotation form requests back to product details', function () {
    $product = createQuotationProduct();

    $this->get(route('quotation.form', $product))
        ->assertRedirect(route('product-details', $product->slug));
});

it('stores quotation requests and redirects back to the product page', function () {
    $product = createQuotationProduct([
        'name' => 'Custom Dining Table',
        'sku' => 'TABLE-QUOTE-001',
    ]);

    $this->post(route('quotation.store', $product), [
        'customer_name' => 'Amit Kumar',
        'email' => 'amit@example.com',
        'phone' => '9876543210',
        'message' => 'Need 25 units with teak finish.',
    ])
        ->assertRedirect(route('product-details', $product->slug))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('quotations', [
        'product_id' => $product->id,
        'customer_name' => 'Amit Kumar',
        'email' => 'amit@example.com',
        'phone' => '9876543210',
        'message' => 'Need 25 units with teak finish.',
        'status' => 'pending',
    ]);
});

it('does not render quote modal triggers on product details pages', function () {
    $product = createQuotationProduct([
        'name' => 'Custom Bed',
        'sku' => 'BED-QUOTE-001',
    ]);

    $this->get(route('product-details', $product->slug))
        ->assertOk()
        ->assertDontSee('window.openQuotationModal', false)
        ->assertDontSee(route('quotation.form', $product), false)
        ->assertDontSee('data-quote-open', false);
});
