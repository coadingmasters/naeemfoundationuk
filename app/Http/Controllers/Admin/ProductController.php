<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\AssignsSortOrder;
use App\Http\Controllers\Concerns\HandlesImageUploads;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    use AssignsSortOrder, HandlesImageUploads;

    private const UPLOAD_DIR = 'images/products';

    public function index()
    {
        $products = Product::ordered()->paginate(12);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $product = new Product(['is_active' => true, 'in_stock' => true, 'category' => Product::CATEGORIES[0]]);

        return view('admin.products.create', compact('product'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request, true, \App\Support\RegionContext::region() ?? 'GB');
        $data['sort_order'] = $request->filled('sort_order')
            ? (int) $request->input('sort_order')
            : $this->nextSortOrder(Product::class);
        $data['image'] = $this->storeUploadedImage($request->file('image'), self::UPLOAD_DIR, 'product');
        $data['is_active'] = $request->boolean('is_active');
        $data['in_stock'] = $request->boolean('in_stock');

        Product::create($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $this->validateData($request, false, $product->region ?? 'GB');
        $data['is_active'] = $request->boolean('is_active');
        $data['in_stock'] = $request->boolean('in_stock');

        if (! $request->filled('sort_order')) {
            unset($data['sort_order']);
        }

        if ($request->hasFile('image')) {
            $this->deleteUploadedImage($product->image, self::UPLOAD_DIR);
            $data['image'] = $this->storeUploadedImage($request->file('image'), self::UPLOAD_DIR, 'product');
        }

        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->deleteUploadedImage($product->image, self::UPLOAD_DIR);
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    private function validateData(Request $request, bool $imageRequired, string $region): array
    {
        // Only the product's own region price is required; the other two are optional.
        $priceCols = ['GB' => 'price', 'US' => 'price_usd', 'CA' => 'price_cad'];
        $main = $priceCols[$region] ?? 'price';
        $priceRule = fn (string $col) => [$col === $main ? 'required' : 'nullable', 'numeric', 'min:0', 'max:100000'];

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'category' => ['required', Rule::in(Product::CATEGORIES)],
            'price' => $priceRule('price'),
            'price_usd' => $priceRule('price_usd'),
            'price_cad' => $priceRule('price_cad'),
            'badge' => ['nullable', 'string', 'max:40'],
            'image' => [$imageRequired ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);

        // The base `price` column is NOT NULL — default to 0 when it's not the required field.
        $data['price'] = $data['price'] ?? 0;

        return $data;
    }
}
