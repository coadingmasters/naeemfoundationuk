<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\AssignsSortOrder;
use App\Http\Controllers\Concerns\HandlesImageUploads;
use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class HeroSlideController extends Controller
{
    use AssignsSortOrder, HandlesImageUploads;

    /** Directory (relative to the web root) where hero images are stored. */
    private const UPLOAD_DIR = 'images/hero';

    public function index()
    {
        $slides = HeroSlide::ordered()->paginate(10);

        return view('admin.hero-slides.index', compact('slides'));
    }

    public function create()
    {
        $slide = new HeroSlide(['is_active' => true]);

        return view('admin.hero-slides.create', compact('slide'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request, true);
        $data['sort_order'] = $request->filled('sort_order')
            ? (int) $request->input('sort_order')
            : $this->nextSortOrder(HeroSlide::class);
        $data['image'] = $this->storeUploadedImage($request->file('image'), self::UPLOAD_DIR, 'hero');
        $data['is_active'] = $request->boolean('is_active');

        HeroSlide::create($data);

        return redirect()->route('admin.hero-slides.index')
            ->with('success', 'Hero slide created successfully.');
    }

    public function edit(HeroSlide $heroSlide)
    {
        return view('admin.hero-slides.edit', ['slide' => $heroSlide]);
    }

    public function update(Request $request, HeroSlide $heroSlide): RedirectResponse
    {
        $data = $this->validateData($request, false);
        $data['is_active'] = $request->boolean('is_active');

        if (! $request->filled('sort_order')) {
            unset($data['sort_order']); // keep the existing order
        }

        if ($request->hasFile('image')) {
            $this->deleteUploadedImage($heroSlide->image, self::UPLOAD_DIR);
            $data['image'] = $this->storeUploadedImage($request->file('image'), self::UPLOAD_DIR, 'hero');
        }

        $heroSlide->update($data);

        return redirect()->route('admin.hero-slides.index')
            ->with('success', 'Hero slide updated successfully.');
    }

    public function destroy(HeroSlide $heroSlide): RedirectResponse
    {
        $this->deleteUploadedImage($heroSlide->image, self::UPLOAD_DIR);
        $heroSlide->delete();

        return redirect()->route('admin.hero-slides.index')
            ->with('success', 'Hero slide deleted successfully.');
    }

    /** Validate the request, optionally requiring an image (create vs update). */
    private function validateData(Request $request, bool $imageRequired): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'image' => [$imageRequired ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'button_text' => ['nullable', 'string', 'max:60'],
            'button_url' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);
    }
}
