<?php

namespace Tests\Feature;

use App\Models\NewsPost;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class NewsTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['is_admin' => true]);
    }

    /** A real 1x1 PNG — UploadedFile::fake()->image() needs the GD extension. */
    private function image(): UploadedFile
    {
        $png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==');
        $path = tempnam(sys_get_temp_dir(), 'img').'.png';
        file_put_contents($path, $png);

        return new UploadedFile($path, 'news.png', 'image/png', null, true);
    }

    private function story(array $attrs = []): NewsPost
    {
        return NewsPost::create(array_merge([
            'title' => 'A Story',
            'slug' => NewsPost::uniqueSlug($attrs['title'] ?? 'A Story'),
            'category' => 'News',
            'excerpt' => 'A short teaser.',
            'body' => 'First para.'."\n\n".'Second para.',
            'published_at' => now(),
            'is_active' => true,
        ], $attrs));
    }

    public function test_the_index_lists_published_stories(): void
    {
        $this->story(['title' => 'Water Well Opens', 'slug' => 'water-well-opens']);

        $this->get(route('news'))
            ->assertOk()
            ->assertSee('News &amp; Press', false)
            ->assertSee('Water Well Opens');
    }

    public function test_hidden_stories_are_not_listed(): void
    {
        $this->story(['title' => 'Secret Draft', 'slug' => 'secret-draft', 'is_active' => false]);

        $this->get(route('news'))->assertOk()->assertDontSee('Secret Draft');
    }

    public function test_the_index_shows_an_empty_state(): void
    {
        $this->get(route('news'))->assertOk()->assertSee('Stories coming soon');
    }

    public function test_the_featured_story_is_shown_as_the_lead(): void
    {
        $this->story(['title' => 'Ordinary Story', 'slug' => 'ordinary-story']);
        $this->story(['title' => 'Big Lead Story', 'slug' => 'big-lead-story', 'is_featured' => true]);

        $this->get(route('news'))
            ->assertOk()
            ->assertSee('Featured story')
            ->assertSee('Big Lead Story');
    }

    public function test_stories_can_be_filtered_by_category(): void
    {
        $this->story(['title' => 'A Press Item', 'slug' => 'a-press-item', 'category' => 'Press Release']);
        $this->story(['title' => 'A Blog Item', 'slug' => 'a-blog-item', 'category' => 'Blog']);

        $this->get(route('news', ['category' => 'Blog']))
            ->assertOk()
            ->assertSee('A Blog Item')
            ->assertDontSee('A Press Item');
    }

    public function test_a_story_can_be_read(): void
    {
        $post = $this->story(['title' => 'Read Me', 'slug' => 'read-me']);

        $this->get(route('news.show', $post))
            ->assertOk()
            ->assertSee('Read Me')
            ->assertSee('First para.')
            ->assertSee('Second para.')
            ->assertSee('min read');
    }

    public function test_a_hidden_story_returns_404(): void
    {
        $post = $this->story(['title' => 'Hidden', 'slug' => 'hidden', 'is_active' => false]);

        $this->get(route('news.show', $post))->assertNotFound();
    }

    public function test_admin_can_publish_a_story(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.news.store'), [
                'title' => 'Brand New Story',
                'category' => 'Press Release',
                'excerpt' => 'Teaser.',
                'body' => 'The body.',
                'published_at' => '2026-05-01',
                'image_file' => $this->image(),
                'is_active' => 1,
            ])
            ->assertRedirect(route('admin.news.index'));

        $post = NewsPost::sole();
        $this->assertSame('brand-new-story', $post->slug);
        $this->assertSame('Press Release', $post->category);
        $this->assertStringStartsWith('images/news/', $post->image);

        @unlink(public_path($post->image));
    }

    public function test_publishing_requires_a_title_and_valid_category(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.news.store'), ['category' => 'Nonsense'])
            ->assertSessionHasErrors(['title', 'category']);
    }

    public function test_only_one_story_stays_featured(): void
    {
        $old = $this->story(['title' => 'Old Lead', 'slug' => 'old-lead', 'is_featured' => true]);

        $this->actingAs($this->admin())
            ->post(route('admin.news.store'), [
                'title' => 'New Lead',
                'category' => 'News',
                'is_active' => 1,
                'is_featured' => 1,
            ]);

        $this->assertFalse($old->fresh()->is_featured);
        $this->assertTrue(NewsPost::where('slug', 'new-lead')->sole()->is_featured);
    }

    public function test_duplicate_titles_get_unique_slugs(): void
    {
        $this->story(['title' => 'Same Title', 'slug' => 'same-title']);

        $this->actingAs($this->admin())
            ->post(route('admin.news.store'), ['title' => 'Same Title', 'category' => 'News', 'is_active' => 1]);

        $this->assertSame(2, NewsPost::where('title', 'Same Title')->count());
        $this->assertNotNull(NewsPost::where('slug', 'same-title-2')->first());
    }

    public function test_guests_cannot_manage_stories(): void
    {
        $this->get(route('admin.news.index'))->assertRedirect(route('admin.login'));
        $this->post(route('admin.news.store'), [])->assertRedirect(route('admin.login'));
    }
}
