<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;
use App\Models\Product;

class CartService
{
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
        $product = Product::findOrFail($productId);
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

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $quantity;
        } else {
            $cart[$key] = [
                'id' => $productId,
                'name' => $product->name,
                'price' => $product->sale_price ?: $product->price,
                'image' => $product->images->where('featured', true)->first()?->path ?? $product->images->first()?->path,
                'quantity' => $quantity,
                'variation_id' => $variationId,
                'attributes' => $selectedAttributes,
                'slug' => $product->slug,
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

    public function total()
    {
        $cart = $this->get();
        return array_sum(array_map(function ($item) {
            return $item['price'] * $item['quantity'];
        }, $cart));
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
