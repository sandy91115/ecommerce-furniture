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

it('returns quotation product data for ajax form requests', function () {
    $product = createQuotationProduct();

    $this->getJson(route('quotation.form', $product))
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('product.id', $product->id)
        ->assertJsonPath('product.slug', $product->slug);
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
        'city' => 'Delhi',
        'country' => 'India',
        'pincode' => '110092',
        'message' => '',
    ])
        ->assertRedirect(route('product-details', $product->slug))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('quotations', [
        'product_id' => $product->id,
        'customer_name' => 'Amit Kumar',
        'email' => 'amit@example.com',
        'phone' => '9876543210',
        'city' => 'Delhi',
        'country' => 'India',
        'pincode' => '110092',
        'message' => '',
        'status' => 'pending',
    ]);
});

it('stores quotation requests through ajax and returns a success response', function () {
    $product = createQuotationProduct([
        'name' => 'Custom Wardrobe',
        'sku' => 'WARDROBE-QUOTE-001',
    ]);

    $this->postJson(route('quotation.store', $product), [
        'customer_name' => 'Neha Sharma',
        'email' => 'neha@example.com',
        'phone' => '9999999999',
        'city' => 'Jaipur',
        'country' => 'India',
        'pincode' => '302001',
        'desired_price' => 25000,
        'message' => 'Need delivery in Jaipur.',
    ])
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('quotation.product_id', $product->id)
        ->assertJsonPath('quotation.customer_name', 'Neha Sharma');

    $this->assertDatabaseHas('quotations', [
        'product_id' => $product->id,
        'customer_name' => 'Neha Sharma',
        'email' => 'neha@example.com',
        'phone' => '9999999999',
        'city' => 'Jaipur',
        'country' => 'India',
        'pincode' => '302001',
        'desired_price' => '25000.00',
        'message' => 'Need delivery in Jaipur.',
        'status' => 'pending',
    ]);
});

it('renders quote modal triggers on product details pages', function () {
    $product = createQuotationProduct([
        'name' => 'Custom Bed',
        'sku' => 'BED-QUOTE-001',
    ]);

    $this->get(route('product-details', $product->slug))
        ->assertOk()
        ->assertSee('data-quote-trigger', false)
        ->assertSee('id="quotationModal"', false)
        ->assertSee(route('quotation.store', $product), false);
});
