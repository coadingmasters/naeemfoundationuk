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
            ->post(route('admin.appeals.store'), ['sort_order' => 0])
            ->assertSessionHasErrors(['title', 'description', 'image']);
    }
}
