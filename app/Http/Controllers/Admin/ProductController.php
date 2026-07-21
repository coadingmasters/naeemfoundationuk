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
        $data = $this->validateData($request, true);
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
        $data = $this->validateData($request, false);
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

    private function validateData(Request $request, bool $imageRequired): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'category' => ['required', Rule::in(Product::CATEGORIES)],
            'price' => ['required', 'numeric', 'min:0', 'max:100000'],
            'badge' => ['nullable', 'string', 'max:40'],
            'image' => [$imageRequired ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);
    }
}
