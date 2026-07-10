<?php

namespace App\Support;

use Illuminate\Support\Str;

/**
 * Session-backed donation basket.
 *
 * Each line item: ['id', 'cause', 'amount', 'qty', 'frequency', 'image']
 */
class DonationCart
{
    public const SESSION_KEY = 'donation_cart';

    /** Payment provider fee applied to the subtotal (1.4%). */
    public const FEE_RATE = 0.014;

    /** @return array<int, array<string, mixed>> */
    public static function items(): array
    {
        return array_values(session(self::SESSION_KEY, []));
    }

    public static function add(array $item): void
    {
        $items = session(self::SESSION_KEY, []);

        // Merge duplicates (same cause + amount + frequency) by bumping the quantity.
        foreach ($items as $key => $existing) {
            if (
                $existing['cause'] === $item['cause']
                && (float) $existing['amount'] === (float) $item['amount']
                && ($existing['frequency'] ?? 'one-off') === ($item['frequency'] ?? 'one-off')
            ) {
                $items[$key]['qty'] = (int) $existing['qty'] + (int) ($item['qty'] ?? 1);
                session([self::SESSION_KEY => $items]);

                return;
            }
        }

        $item['id'] = (string) Str::uuid();
        $item['qty'] = (int) ($item['qty'] ?? 1);
        $items[] = $item;

        session([self::SESSION_KEY => $items]);
    }

    /** Maximum quantity allowed per basket line. */
    public const MAX_QTY = 99;

    /** Set a line's quantity. A quantity below 1 removes the line entirely. */
    public static function setQty(string $id, int $qty): void
    {
        $items = session(self::SESSION_KEY, []);

        foreach ($items as $key => $item) {
            if (($item['id'] ?? null) !== $id) {
                continue;
            }

            if ($qty < 1) {
                unset($items[$key]);
            } else {
                $items[$key]['qty'] = min($qty, self::MAX_QTY);
            }

            break;
        }

        session([self::SESSION_KEY => array_values($items)]);
    }

    public static function remove(string $id): void
    {
        $items = array_filter(
            session(self::SESSION_KEY, []),
            fn ($item) => ($item['id'] ?? null) !== $id
        );

        session([self::SESSION_KEY => array_values($items)]);
    }

    public static function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    public static function count(): int
    {
        return array_sum(array_map(fn ($i) => (int) $i['qty'], self::items()));
    }

    public static function subtotal(): float
    {
        return round(
            array_sum(array_map(fn ($i) => (float) $i['amount'] * (int) $i['qty'], self::items())),
            2
        );
    }

    public static function fee(): float
    {
        return round(self::subtotal() * self::FEE_RATE, 2);
    }

    /** Total including the transaction fee only when the donor opts to cover it. */
    public static function total(bool $coverFee = false): float
    {
        return round(self::subtotal() + ($coverFee ? self::fee() : 0), 2);
    }

    public static function isEmpty(): bool
    {
        return self::items() === [];
    }
}
