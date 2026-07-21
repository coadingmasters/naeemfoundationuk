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
            return response()->json([
                'count' => ProductCart::count(),
                'message' => 'Added to your bag.',
            ]);
        }

        return back()->with('success', 'Added to your bag.');
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $data = $request->validate([
            'qty' => ['required', 'integer', 'min:0', 'max:20'],
        ]);

        ProductCart::setQty($id, (int) $data['qty']);

        return back();
    }

    public function remove(int $id): RedirectResponse
    {
        ProductCart::remove($id);

        return back()->with('success', 'Item removed from your bag.');
    }
}
