<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\HandlesImageUploads;
use App\Http\Controllers\Controller;
use App\Models\NewsPost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class NewsPostController extends Controller
{
    use HandlesImageUploads;

    private const IMAGE_DIR = 'images/news';

    public function index()
    {
        $posts = NewsPost::ordered()->paginate(10);

        return view('admin.news.index', compact('posts'));
    }

    public function create()
    {
        $post = new NewsPost(['is_active' => true, 'category' => 'News', 'published_at' => now()]);

        return view('admin.news.create', compact('post'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);

        $data['slug'] = NewsPost::uniqueSlug($data['title']);
        $data['is_active'] = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');

        if ($request->hasFile('image_file')) {
            $data['image'] = $this->storeUploadedImage($request->file('image_file'), self::IMAGE_DIR, 'news');
        }

        $post = NewsPost::create($data);
        $this->ensureSingleFeatured($post);

        return redirect()->route('admin.news.index')->with('success', 'Story published successfully.');
    }

    public function edit(NewsPost $news)
    {
        return view('admin.news.edit', ['post' => $news]);
    }

    public function update(Request $request, NewsPost $news): RedirectResponse
    {
        $data = $this->validateData($request);

        // Keep the URL stable unless the title actually changed.
        if ($data['title'] !== $news->title) {
            $data['slug'] = NewsPost::uniqueSlug($data['title'], $news->id);
        }

        $data['is_active'] = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');

        if ($request->hasFile('image_file')) {
            $this->deleteUploadedImage($news->image, self::IMAGE_DIR);
            $data['image'] = $this->storeUploadedImage($request->file('image_file'), self::IMAGE_DIR, 'news');
        }

        $news->update($data);
        $this->ensureSingleFeatured($news);

        return redirect()->route('admin.news.index')->with('success', 'Story updated successfully.');
    }

    public function destroy(NewsPost $news): RedirectResponse
    {
        $this->deleteUploadedImage($news->image, self::IMAGE_DIR);
        $news->delete();

        return redirect()->route('admin.news.index')->with('success', 'Story deleted successfully.');
    }

    /** Only one story can be the featured lead at a time. */
    private function ensureSingleFeatured(NewsPost $post): void
    {
        if ($post->is_featured) {
            NewsPost::whereKeyNot($post->getKey())->update(['is_featured' => false]);
        }
    }

    /** @return array<string, mixed> */
    private function validateData(Request $request): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', Rule::in(NewsPost::CATEGORIES)],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'body' => ['nullable', 'string', 'max:20000'],
            'published_at' => ['nullable', 'date'],
            'image_file' => ['nullable', 'image', 'max:6144'],
        ]);

        return array_intersect_key($validated, array_flip(['title', 'category', 'excerpt', 'body', 'published_at']));
    }
}
