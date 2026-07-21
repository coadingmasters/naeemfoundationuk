<?php

namespace App\Support;

use App\Models\Product;

/**
 * Lightweight session-based shopping cart for the shop (separate from the
 * donation basket). Stored as [product_id => qty].
 */
class ProductCart
{
    public const SESSION_KEY = 'product_cart';
    public const MAX_QTY = 20;

    /** Hydrated line items: id, product, qty, line total. */
    public static function items(): array
    {
        $cart = session(self::SESSION_KEY, []);
        if (empty($cart)) {
            return [];
        }

        $products = Product::whereIn('id', array_keys($cart))->get()->keyBy('id');
        $items = [];

        foreach ($cart as $id => $qty) {
            if (! isset($products[$id])) {
                continue;
            }
            $product = $products[$id];
            $items[] = [
                'id' => (int) $id,
                'product' => $product,
                'qty' => (int) $qty,
                'line' => round((float) $product->price * (int) $qty, 2),
            ];
        }

        return $items;
    }

    public static function add(int $id, int $qty = 1): void
    {
        $cart = session(self::SESSION_KEY, []);
        $cart[$id] = min(self::MAX_QTY, ($cart[$id] ?? 0) + max(1, $qty));
        session([self::SESSION_KEY => $cart]);
    }

    public static function setQty(int $id, int $qty): void
    {
        $cart = session(self::SESSION_KEY, []);
        if ($qty < 1) {
            unset($cart[$id]);
        } else {
            $cart[$id] = min(self::MAX_QTY, $qty);
        }
        session([self::SESSION_KEY => $cart]);
    }

    public static function remove(int $id): void
    {
        $cart = session(self::SESSION_KEY, []);
        unset($cart[$id]);
        session([self::SESSION_KEY => $cart]);
    }

    public static function count(): int
    {
        return (int) array_sum(session(self::SESSION_KEY, []));
    }

    public static function subtotal(): float
    {
        return round(array_sum(array_map(fn ($i) => $i['line'], self::items())), 2);
    }

    public static function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }
}
