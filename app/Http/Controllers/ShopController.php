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

        // Price expression for the visitor's active region (falls back to GBP base).
        $priceCol = match (\App\Support\Country::code()) {
            'US' => 'price_usd',
            'CA' => 'price_cad',
            default => 'price',
        };
        $priceExpr = $priceCol === 'price' ? 'price' : "COALESCE({$priceCol}, price)";

        // Price range (in the active currency)
        if ($request->filled('min')) {
            $query->whereRaw("{$priceExpr} >= ?", [(float) $request->query('min')]);
        }
        if ($request->filled('max')) {
            $query->whereRaw("{$priceExpr} <= ?", [(float) $request->query('max')]);
        }

        // In-stock only
        if ($request->query('stock') === 'in') {
            $query->where('in_stock', true);
        }

        // Sort
        switch ($request->query('sort')) {
            case 'price_asc': $query->orderByRaw("{$priceExpr} asc"); break;
            case 'price_desc': $query->orderByRaw("{$priceExpr} desc"); break;
            case 'name': $query->orderBy('name'); break;
            case 'newest': $query->orderByDesc('id'); break;
            default: $query->ordered();
        }

        $products = $query->paginate(9)->withQueryString();

        $counts = Product::active()
            ->selectRaw('category, COUNT(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category');

        $maxPrice = (int) ceil((float) (Product::active()->selectRaw("MAX({$priceExpr}) as m")->value('m') ?: 100));

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
            'postcode' => ['required', 'string', 'max:20'],
        ]);

        $region = \App\Support\Country::current();
        $symbol = $region['symbol'];
        $currency = $region['currency'];

        $reference = 'NF-'.strtoupper(Str::random(8));
        $subtotal = ProductCart::subtotal();
        $orderItems = array_map(fn ($i) => [
            'name' => $i['product']->name,
            'price' => (float) $i['unit'],
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
                    'postcode' => $data['postcode'],
                    'items' => $orderItems,
                    'subtotal' => $subtotal,
                    'currency' => $currency,
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
                symbol: $symbol,
            ));
        } catch (Throwable $e) {
            // Never block the confirmation on a mail failure.
        }

        ProductCart::clear();

        return redirect()->route('shop.order-complete')->with('order', [
            'reference' => $reference,
            'name' => $data['name'],
            'total' => $subtotal,
            'symbol' => $symbol,
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
