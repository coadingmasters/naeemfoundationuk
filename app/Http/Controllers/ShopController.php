<?php

namespace App\Http\Controllers;

use App\Mail\OrderReceipt;
use App\Models\Order;
use App\Models\Product;
use App\Support\ProductCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Throwable;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::active();

        // Category
        if ($category = $request->query('category')) {
            $query->where('category', $category);
        }

        // Search
        if ($search = trim((string) $request->query('q'))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Price range
        if ($request->filled('min')) {
            $query->where('price', '>=', (float) $request->query('min'));
        }
        if ($request->filled('max')) {
            $query->where('price', '<=', (float) $request->query('max'));
        }

        // In-stock only
        if ($request->query('stock') === 'in') {
            $query->where('in_stock', true);
        }

        // Sort
        switch ($request->query('sort')) {
            case 'price_asc': $query->orderBy('price'); break;
            case 'price_desc': $query->orderByDesc('price'); break;
            case 'name': $query->orderBy('name'); break;
            case 'newest': $query->orderByDesc('id'); break;
            default: $query->ordered();
        }

        $products = $query->paginate(9)->withQueryString();

        $counts = Product::active()
            ->selectRaw('category, COUNT(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category');

        $maxPrice = (int) ceil((float) (Product::active()->max('price') ?: 100));

        return view('shop.index', [
            'products' => $products,
            'categories' => Product::CATEGORIES,
            'counts' => $counts,
            'maxPrice' => $maxPrice,
        ]);
    }

    public function show(Product $product)
    {
        abort_unless($product->is_active, 404);

        $related = Product::active()
            ->where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->ordered()
            ->take(4)
            ->get();

        return view('shop.show', compact('product', 'related'));
    }

    public function checkout()
    {
        $items = ProductCart::items();
        if (empty($items)) {
            return redirect()->route('shop.cart');
        }

        return view('shop.checkout', [
            'items' => $items,
            'subtotal' => ProductCart::subtotal(),
        ]);
    }

    public function placeOrder(Request $request)
    {
        $items = ProductCart::items();
        if (empty($items)) {
            return redirect()->route('shop.cart');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:40'],
            'address' => ['required', 'string', 'max:1000'],
        ]);

        $reference = 'NF-'.strtoupper(Str::random(8));
        $subtotal = ProductCart::subtotal();
        $orderItems = array_map(fn ($i) => [
            'name' => $i['product']->name,
            'price' => (float) $i['product']->price,
            'qty' => $i['qty'],
            'line' => $i['line'],
        ], $items);

        try {
            if (Schema::hasTable('orders')) {
                Order::create([
                    'reference' => $reference,
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                    'address' => $data['address'],
                    'items' => $orderItems,
                    'subtotal' => $subtotal,
                ]);
            }
        } catch (Throwable $e) {
            // Never block the confirmation on a storage hiccup.
        }

        // Confirmation email (same system as the donation receipt).
        try {
            Mail::to($data['email'])->send(new OrderReceipt(
                reference: $reference,
                name: $data['name'],
                items: $orderItems,
                subtotal: $subtotal,
                address: $data['address'],
            ));
        } catch (Throwable $e) {
            // Never block the confirmation on a mail failure.
        }

        ProductCart::clear();

        return redirect()->route('shop.order-complete')->with('order', [
            'reference' => $reference,
            'name' => $data['name'],
            'total' => $subtotal,
        ]);
    }

    public function orderComplete()
    {
        $order = session('order');
        if (! $order) {
            return redirect()->route('shop');
        }

        return view('shop.thank-you', ['order' => $order]);
    }
}
