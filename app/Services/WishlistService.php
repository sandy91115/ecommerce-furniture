<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Session;

class WishlistService
{
    public function add($productId)
    {
        Product::findOrFail($productId);

        $wishlist = $this->get();
        $wishlist[$productId] = true;
        $this->set($wishlist);

        return $wishlist;
    }

    public function remove($productId)
    {
        $wishlist = $this->get();
        unset($wishlist[$productId]);
        $this->set($wishlist);

        return $wishlist;
    }

    public function toggle($productId)
    {
        Product::findOrFail($productId);

        $wishlist = $this->get();
        $added = ! isset($wishlist[$productId]);

        if ($added) {
            $wishlist[$productId] = true;
        } else {
            unset($wishlist[$productId]);
        }

        $this->set($wishlist);

        return $added;
    }

    public function count()
    {
        return count($this->get());
    }

    public function get()
    {
        $wishlist = Session::get('wishlist', []);
        $normalized = $this->normalize($wishlist);

        if ($wishlist !== $normalized) {
            $this->set($normalized);
        }

        return $normalized;
    }

    public function ids()
    {
        return array_map('intval', array_keys($this->get()));
    }

    public function has($productId)
    {
        return isset($this->get()[$productId]);
    }

    public function getItems()
    {
        $ids = $this->ids();

        if (empty($ids)) {
            return collect();
        }

        $products = Product::whereIn('id', $ids)
            ->with(['images', 'category'])
            ->get()
            ->keyBy('id');

        return collect($ids)
            ->map(fn ($id) => $products->get($id))
            ->filter()
            ->values();
    }

    protected function set($wishlist)
    {
        Session::put('wishlist', $wishlist);
        Session::save();
    }

    protected function normalize(array $wishlist): array
    {
        $normalized = [];

        foreach ($wishlist as $key => $value) {
            $productId = null;

            if (is_numeric($key) && ! is_bool($value) && is_numeric($value)) {
                $productId = (int) $value;
            } elseif (is_numeric($key)) {
                $productId = (int) $key;
            } elseif (is_numeric($value)) {
                $productId = (int) $value;
            }

            if ($productId) {
                $normalized[$productId] = true;
            }
        }

        return $normalized;
    }
}

