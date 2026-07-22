<?php

namespace App\Http\Controllers;

use App\Support\ProductCart;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProductCartController extends Controller
{
    public function index()
    {
        return view('shop.cart', [
            'items' => ProductCart::items(),
            'subtotal' => ProductCart::subtotal(),
        ]);
    }

    public function add(Request $request): RedirectResponse|JsonResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'qty' => ['nullable', 'integer', 'min:1', 'max:20'],
        ]);

        ProductCart::add((int) $data['product_id'], (int) ($data['qty'] ?? 1));

        if ($request->expectsJson()) {
            return $this->bagJson('Added to your basket.');
        }

        return back()->with('success', 'Added to your basket.');
    }

    public function update(Request $request, int $id): RedirectResponse|JsonResponse
    {
        $data = $request->validate([
            'qty' => ['required', 'integer', 'min:0', 'max:20'],
        ]);

        ProductCart::setQty($id, (int) $data['qty']);

        if ($request->expectsJson()) {
            return $this->bagJson('Basket updated.');
        }

        return back();
    }

    public function remove(Request $request, int $id): RedirectResponse|JsonResponse
    {
        ProductCart::remove($id);

        if ($request->expectsJson()) {
            return $this->bagJson('Item removed from your basket.');
        }

        return back()->with('success', 'Item removed from your basket.');
    }

    /** The unified basket state the header cart needs after each change. */
    private function bagJson(string $message): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'count' => \App\Support\DonationCart::count() + ProductCart::count(),
            'product_count' => ProductCart::count(),
            'html' => view('partials.cart-body')->render(),
        ]);
    }
}
