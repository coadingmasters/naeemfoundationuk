<?php

namespace Tests\Feature;

use App\Models\Appeal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AdminAppealTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['is_admin' => true]);
    }

    public function test_home_page_renders_active_appeals(): void
    {
        Appeal::create([
            'title' => 'VISIBLE APPEAL',
            'description' => 'Shown on the homepage.',
            'image' => 'images/changinslives1.jpg',
            'is_active' => true,
        ]);
        Appeal::create([
            'title' => 'HIDDEN APPEAL',
            'description' => 'Not shown.',
            'image' => 'images/changinslives2.jpg',
            'is_active' => false,
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('VISIBLE APPEAL');
        $response->assertDontSee('HIDDEN APPEAL');
    }

    public function test_admin_pages_render(): void
    {
        $admin = $this->admin();
        $appeal = Appeal::create([
            'title' => 'RENDER APPEAL',
            'description' => 'desc',
            'image' => 'images/changinslives1.jpg',
            'is_active' => true,
        ]);

        $this->actingAs($admin)->get(route('admin.appeals.index'))->assertOk()->assertSee('RENDER APPEAL');
        $this->actingAs($admin)->get(route('admin.appeals.create'))->assertOk()->assertSee('New Appeal');
        $this->actingAs($admin)->get(route('admin.appeals.edit', $appeal))->assertOk()->assertSee('Edit Appeal');
    }

    public function test_guest_cannot_manage_appeals(): void
    {
        $this->get(route('admin.appeals.index'))->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_create_an_appeal_with_image(): void
    {
        $response = $this->actingAs($this->admin())->post(route('admin.appeals.store'), [
            'title' => 'New Appeal',
            'description' => 'A helpful cause that needs support.',
            'image' => UploadedFile::fake()->create('appeal.jpg', 200, 'image/jpeg'),
            'link' => '#',
            'sort_order' => 2,
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.appeals.index'));
        $this->assertDatabaseHas('appeals', ['title' => 'New Appeal', 'is_active' => true]);

        $appeal = Appeal::first();
        $this->assertStringStartsWith('images/appeals/', $appeal->image);
        $this->assertFileExists(public_path($appeal->image));

        @unlink(public_path($appeal->image));
    }

    public function test_admin_can_update_and_delete_an_appeal(): void
    {
        $appeal = Appeal::create([
            'title' => 'OLD',
            'description' => 'old desc',
            'image' => 'images/changinslives1.jpg',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $this->actingAs($this->admin())
            ->put(route('admin.appeals.update', $appeal), [
                'title' => 'UPDATED',
                'description' => 'new desc',
                'sort_order' => 4,
                'is_active' => '0',
            ])
            ->assertRedirect(route('admin.appeals.index'));

        $this->assertDatabaseHas('appeals', ['id' => $appeal->id, 'title' => 'UPDATED', 'is_active' => false]);

        $this->actingAs($this->admin())
            ->delete(route('admin.appeals.destroy', $appeal))
            ->assertRedirect(route('admin.appeals.index'));

        $this->assertDatabaseMissing('appeals', ['id' => $appeal->id]);
    }

    public function test_create_requires_title_description_and_image(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.appeals.store'), [])
            ->assertSessionHasErrors(['title', 'description', 'image']);
    }

    public function test_sort_order_auto_assigns_filling_gaps_then_appends(): void
    {
        // Existing orders 1, 2, 4 — leaving a gap at 3.
        foreach ([1, 2, 4] as $o) {
            Appeal::create(['title' => "A{$o}", 'description' => 'd', 'image' => 'images/changinslives1.jpg', 'is_active' => true, 'sort_order' => $o]);
        }

        // No order given → fills the gap (3).
        $this->actingAs($this->admin())->post(route('admin.appeals.store'), [
            'title' => 'Gap Filler',
            'description' => 'fills the gap',
            'image' => UploadedFile::fake()->create('a.jpg', 100, 'image/jpeg'),
        ]);
        $gap = Appeal::where('title', 'Gap Filler')->first();
        $this->assertSame(3, $gap->sort_order);
        @unlink(public_path($gap->image));

        // Orders are now 1,2,3,4 (contiguous) → next goes after the last (5).
        $this->actingAs($this->admin())->post(route('admin.appeals.store'), [
            'title' => 'After Last',
            'description' => 'appended',
            'image' => UploadedFile::fake()->create('b.jpg', 100, 'image/jpeg'),
        ]);
        $last = Appeal::where('title', 'After Last')->first();
        $this->assertSame(5, $last->sort_order);
        @unlink(public_path($last->image));
    }

    public function test_sort_order_starts_at_one_when_empty(): void
    {
        $this->actingAs($this->admin())->post(route('admin.appeals.store'), [
            'title' => 'First',
            'description' => 'd',
            'image' => UploadedFile::fake()->create('a.jpg', 100, 'image/jpeg'),
        ]);

        $first = Appeal::where('title', 'First')->first();
        $this->assertSame(1, $first->sort_order);
        @unlink(public_path($first->image));
    }
}
