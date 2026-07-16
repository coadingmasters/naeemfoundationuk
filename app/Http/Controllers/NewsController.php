<?php

namespace App\Http\Controllers;

use App\Models\NewsPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Throwable;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->query('category');
        $posts = collect();
        $featured = null;

        try {
            if (Schema::hasTable('news_posts')) {
                $query = NewsPost::active()->ordered();

                if ($category && in_array($category, NewsPost::CATEGORIES, true)) {
                    $query->where('category', $category);
                }

                $posts = $query->get();

                // Lead story: the featured post (or simply the newest) — but only
                // on the unfiltered view, so filtering never hides a result.
                if (! $category) {
                    $featured = $posts->firstWhere('is_featured', true) ?? $posts->first();
                    $posts = $posts->reject(fn ($p) => $featured && $p->is($featured))->values();
                }
            }
        } catch (Throwable $e) {
            $posts = collect();
        }

        return view('news.index', [
            'posts' => $posts,
            'featured' => $featured,
            'categories' => NewsPost::CATEGORIES,
            'active' => $category,
        ]);
    }

    public function show(string $slug)
    {
        abort_unless(Schema::hasTable('news_posts'), 404);

        $post = NewsPost::active()->where('slug', $slug)->firstOrFail();

        $related = NewsPost::active()->ordered()
            ->whereKeyNot($post->getKey())
            ->where('category', $post->category)
            ->limit(3)
            ->get();

        if ($related->count() < 3) {
            $related = NewsPost::active()->ordered()->whereKeyNot($post->getKey())->limit(3)->get();
        }

        return view('news.show', compact('post', 'related'));
    }
}
