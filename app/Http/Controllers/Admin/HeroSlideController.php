<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class HeroSlideController extends Controller
{
    /** Directory (relative to public/) where hero images are stored. */
    private const UPLOAD_DIR = 'images/hero';

    public function index()
    {
        $slides = HeroSlide::ordered()->paginate(10);

        return view('admin.hero-slides.index', compact('slides'));
    }

    public function create()
    {
        $slide = new HeroSlide(['is_active' => true, 'sort_order' => 0]);

        return view('admin.hero-slides.create', compact('slide'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request, true);
        $data['image'] = $this->storeImage($request->file('image'));
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

        if ($request->hasFile('image')) {
            $this->deleteImage($heroSlide->image);
            $data['image'] = $this->storeImage($request->file('image'));
        }

        $heroSlide->update($data);

        return redirect()->route('admin.hero-slides.index')
            ->with('success', 'Hero slide updated successfully.');
    }

    public function destroy(HeroSlide $heroSlide): RedirectResponse
    {
        $this->deleteImage($heroSlide->image);
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
            'sort_order' => ['required', 'integer', 'min:0', 'max:9999'],
        ]);
    }

    /** Move an uploaded file into public/images/hero and return its relative path. */
    private function storeImage(UploadedFile $file): string
    {
        $name = 'hero-'.now()->format('YmdHis').'-'.substr(md5(uniqid('', true)), 0, 8).'.'.$file->getClientOriginalExtension();
        $file->move(public_path(self::UPLOAD_DIR), $name);

        return self::UPLOAD_DIR.'/'.$name;
    }

    /** Remove a previously uploaded image (never touches seeded/shared images). */
    private function deleteImage(?string $path): void
    {
        if ($path && str_starts_with($path, self::UPLOAD_DIR.'/')) {
            $full = public_path($path);
            if (is_file($full)) {
                @unlink($full);
            }
        }
    }
}
