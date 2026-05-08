<?php
namespace App\Services;

use Illuminate\Support\Facades\Session;
use App\Models\Product;
use Illuminate\Validation\ValidationException;

class CartService
{
    public function tax()
    {
        return $this->summary()['tax'];
    }

    public function count()
    {
        $cart = $this->get();
        return array_sum(array_column($cart, 'quantity'));
    }

    public function get()
    {
        return Session::get('cart', []);
    }

    public function add($productId, $quantity = 1, $variationId = null, $attributes = [])
    {
        $product = Product::query()
            ->whereKey($productId)
            ->where('status', 'active')
            ->where('product_type', 'sell')
            ->firstOrFail();
        $cart = $this->get();

        $attrKey = '';
        $selectedAttributes = [];
        if (!empty($attributes)) {
            ksort($attributes);
            $attrKey = '_' . implode('_', $attributes);
            
            // Get attribute labels for display
            foreach ($attributes as $attrId => $valueId) {
                $attrValue = \App\Models\AttributeValue::with('attribute')->find($valueId);
                if ($attrValue) {
                    $selectedAttributes[] = [
                        'attribute_id' => $attrId,
                        'attribute_name' => $attrValue->attribute->name,
                        'value_id' => $valueId,
                        'value' => $attrValue->value
                    ];
                }
            }
        }

        $key = $productId . ($variationId ? '_v' . $variationId : '') . $attrKey;
        $newQuantity = ($cart[$key]['quantity'] ?? 0) + $quantity;

        if ((int) $product->stock < $newQuantity) {
            throw ValidationException::withMessages([
                'quantity' => "Only {$product->stock} item(s) are available for {$product->name}.",
            ]);
        }

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $quantity;
        } else {
            $cart[$key] = [
                'id' => $productId,
                'name' => $product->name,
                'price' => $product->sale_price ?: $product->price,
                'image' => image_path($product->images->where('featured', true)->first() ?? $product->images->first()),
                'quantity' => $quantity,
                'variation_id' => $variationId,
                'attributes' => $selectedAttributes,
                'slug' => $product->slug,
                'tax_slab' => $product->tax_slab,
            ];
        }

        Session::put('cart', $cart);
        Session::save();

        return $cart;
    }

    public function update($key, $quantity)
    {
        $cart = $this->get();
        if (isset($cart[$key])) {
            $product = Product::query()
                ->whereKey($cart[$key]['id'])
                ->where('status', 'active')
                ->where('product_type', 'sell')
                ->first();

            if (! $product) {
                unset($cart[$key]);
                Session::put('cart', $cart);
                Session::save();

                throw ValidationException::withMessages([
                    'id' => 'This product is no longer available.',
                ]);
            }

            if ((int) $product->stock < (int) $quantity) {
                throw ValidationException::withMessages([
                    'quantity' => "Only {$product->stock} item(s) are available for {$product->name}.",
                ]);
            }

            $cart[$key]['quantity'] = $quantity;
            if ($quantity <= 0) {
                unset($cart[$key]);
            }
        }
        Session::put('cart', $cart);
        Session::save();
    }

    public function remove($key)
    {
        $cart = $this->get();
        unset($cart[$key]);
        Session::put('cart', $cart);
        Session::save();
    }

    public function clear()
    {
        Session::forget('cart');
    }

    public function validateForCheckout(): array
    {
        $cart = $this->get();

        foreach ($cart as $key => $item) {
            $product = Product::query()
                ->whereKey($item['id'] ?? null)
                ->where('status', 'active')
                ->where('product_type', 'sell')
                ->first();

            if (! $product) {
                unset($cart[$key]);

                continue;
            }

            if ((int) $product->stock < (int) ($item['quantity'] ?? 0)) {
                throw ValidationException::withMessages([
                    'cart' => "Only {$product->stock} item(s) are available for {$product->name}.",
                ]);
            }

            $cart[$key]['name'] = $product->name;
            $cart[$key]['price'] = $product->sale_price ?: $product->price;
            $cart[$key]['slug'] = $product->slug;
            $cart[$key]['tax_slab'] = $product->tax_slab;
        }

        Session::put('cart', $cart);
        Session::save();

        return $cart;
    }

    public function total()
    {
        return $this->summary()['subtotal'];
    }

    public function grandTotal()
    {
        return $this->summary()['total'];
    }

    public function taxLabel()
    {
        return $this->summary()['tax_label'];
    }

    public function summary(?array $cart = null): array
    {
        return cart_summary($cart ?? $this->get());
    }

    public function items()
    {
        return count($this->get());
    }

    // Wishlist
    public function wishlistCount()
    {
        return count(Session::get('wishlist', []));
    }

    public function addToWishlist($productId)
    {
        Product::findOrFail($productId);
        $wishlist = Session::get('wishlist', []);

        $wishlist[$productId] = true;

        Session::put('wishlist', $wishlist);
        Session::save();
    }

    public function removeFromWishlist($productId)
    {
        $wishlist = Session::get('wishlist', []);
        unset($wishlist[$productId]);
        Session::put('wishlist', $wishlist);
        Session::save();
    }

    public function getWishlist()
    {
        $wishlistIds = array_keys(Session::get('wishlist', []));
        if (empty($wishlistIds)) {
            return collect([]);
        }
        return Product::whereIn('id', $wishlistIds)
            ->with(['images', 'category'])
            ->get()
            ->keyBy('id');
    }

    public function getWishlistIds()
    {
        return Session::get('wishlist', []);
    }
}
