<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\HandlesImageUploads;
use App\Http\Controllers\Controller;
use App\Models\PageHero;
use App\Support\PageHeroes;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Lets an admin upload a hero background photo for any Giving page, replacing
 * the page's hardcoded default with zero code changes. Rows are keyed by page
 * route name (see App\Support\PageHeroes::pages()), not by id, so the edit
 * screen works whether or not a custom row exists yet.
 */
class PageHeroController extends Controller
{
    use HandlesImageUploads;

    /** Directory (relative to the web root) where uploaded hero photos are stored. */
    private const UPLOAD_DIR = 'images/heroes';

    public function index()
    {
        $overrides = PageHero::all()->keyBy('page_key');

        $rows = collect(PageHeroes::pages())->map(fn ($label, $key) => [
            'key' => $key,
            'label' => $label,
            'hero' => $overrides->get($key),
        ])->values();

        return view('admin.page-heroes.index', compact('rows'));
    }

    public function create()
    {
        return view('admin.page-heroes.create', [
            'hero' => new PageHero(['is_active' => true]),
            'pageKey' => old('page_key'),
            'pages' => PageHeroes::pages(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $pageKey = (string) $request->input('page_key');
        $request->merge(['page_key' => $pageKey]);

        $request->validate([
            'page_key' => ['required', Rule::in(array_keys(PageHeroes::pages()))],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
            'mobile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
        ]);

        // Replacing an existing custom banner for this page — drop the old files.
        $existing = PageHero::where('page_key', $pageKey)->first();
        if ($existing) {
            $this->deleteUploadedImage($existing->image, self::UPLOAD_DIR);
            $this->deleteUploadedImage($existing->mobile_image, self::UPLOAD_DIR);
        }

        $attributes = [
            'image' => $this->storeResizedImage($request->file('image'), self::UPLOAD_DIR, 'hero'),
            'is_active' => $request->boolean('is_active', true),
        ];

        if ($request->hasFile('mobile_image')) {
            $attributes['mobile_image'] = $this->storeResizedImage($request->file('mobile_image'), self::UPLOAD_DIR, 'hero-mobile', 960);
        }

        PageHero::updateOrCreate(['page_key' => $pageKey], $attributes);

        return redirect()->route('admin.page-heroes.index')
            ->with('success', 'Hero banner set for '.PageHeroes::pages()[$pageKey].'.');
    }

    public function edit(string $pageKey)
    {
        abort_unless(PageHeroes::isPage($pageKey), 404);

        $hero = PageHero::firstOrNew(
            ['page_key' => $pageKey],
            ['is_active' => true],
        );

        return view('admin.page-heroes.edit', [
            'hero' => $hero,
            'pageKey' => $pageKey,
            'pageLabel' => PageHeroes::pages()[$pageKey],
        ]);
    }

    public function update(Request $request, string $pageKey): RedirectResponse
    {
        abort_unless(PageHeroes::isPage($pageKey), 404);

        $existing = PageHero::where('page_key', $pageKey)->first();

        $request->validate([
            'image' => [$existing ? 'nullable' : 'required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
            'mobile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
        ]);

        $attributes = ['is_active' => $request->boolean('is_active', true)];

        if ($request->hasFile('image')) {
            if ($existing) {
                $this->deleteUploadedImage($existing->image, self::UPLOAD_DIR);
            }
            $attributes['image'] = $this->storeResizedImage($request->file('image'), self::UPLOAD_DIR, 'hero');
        }

        if ($request->hasFile('mobile_image')) {
            if ($existing) {
                $this->deleteUploadedImage($existing->mobile_image, self::UPLOAD_DIR);
            }
            $attributes['mobile_image'] = $this->storeResizedImage($request->file('mobile_image'), self::UPLOAD_DIR, 'hero-mobile', 960);
        } elseif ($request->boolean('remove_mobile_image') && $existing) {
            $this->deleteUploadedImage($existing->mobile_image, self::UPLOAD_DIR);
            $attributes['mobile_image'] = null;
        }

        PageHero::updateOrCreate(['page_key' => $pageKey], $attributes);

        return redirect()->route('admin.page-heroes.index')
            ->with('success', 'Hero banner updated for '.PageHeroes::pages()[$pageKey].'.');
    }

    public function destroy(string $pageKey): RedirectResponse
    {
        abort_unless(PageHeroes::isPage($pageKey), 404);

        $hero = PageHero::where('page_key', $pageKey)->first();

        if ($hero) {
            $this->deleteUploadedImage($hero->image, self::UPLOAD_DIR);
            $this->deleteUploadedImage($hero->mobile_image, self::UPLOAD_DIR);
            $hero->delete();
        }

        return redirect()->route('admin.page-heroes.index')
            ->with('success', PageHeroes::pages()[$pageKey].' is back to the default banner.');
    }
}
