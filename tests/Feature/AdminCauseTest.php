<?php

namespace Tests\Feature;

use App\Models\Cause;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AdminCauseTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['is_admin' => true]);
    }

    public function test_home_page_renders_active_causes(): void
    {
        Cause::create([
            'title' => 'VISIBLE CAUSE',
            'description' => 'Shown on the homepage carousel.',
            'image' => 'images/givezakat.png',
            'is_active' => true,
        ]);
        Cause::create([
            'title' => 'HIDDEN CAUSE',
            'description' => 'Not shown.',
            'image' => 'images/givesadqa.jpg',
            'is_active' => false,
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('VISIBLE CAUSE');
        $response->assertDontSee('HIDDEN CAUSE');
    }

    public function test_admin_pages_render(): void
    {
        $admin = $this->admin();
        $cause = Cause::create([
            'title' => 'RENDER CAUSE',
            'description' => 'desc',
            'image' => 'images/givezakat.png',
            'is_active' => true,
        ]);

        $this->actingAs($admin)->get(route('admin.causes.index'))->assertOk()->assertSee('RENDER CAUSE');
        $this->actingAs($admin)->get(route('admin.causes.create'))->assertOk()->assertSee('New Cause');
        $this->actingAs($admin)->get(route('admin.causes.edit', $cause))->assertOk()->assertSee('Edit Cause');
    }

    public function test_guest_cannot_manage_causes(): void
    {
        $this->get(route('admin.causes.index'))->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_create_a_cause_with_image(): void
    {
        $response = $this->actingAs($this->admin())->post(route('admin.causes.store'), [
            'title' => 'Give Zakat',
            'description' => 'Purify your wealth and help those in need.',
            'image' => UploadedFile::fake()->create('cause.jpg', 200, 'image/jpeg'),
            'link' => '#',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.causes.index'));
        $this->assertDatabaseHas('causes', ['title' => 'Give Zakat', 'is_active' => true]);

        $cause = Cause::first();
        $this->assertStringStartsWith('images/causes/', $cause->image);
        $this->assertSame(1, $cause->sort_order); // auto-assigned
        $this->assertFileExists(public_path($cause->image));

        @unlink(public_path($cause->image));
    }

    public function test_admin_can_update_and_delete_a_cause(): void
    {
        $cause = Cause::create([
            'title' => 'OLD',
            'description' => 'old desc',
            'image' => 'images/givezakat.png',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $this->actingAs($this->admin())
            ->put(route('admin.causes.update', $cause), [
                'title' => 'UPDATED',
                'description' => 'new desc',
                'is_active' => '0',
            ])
            ->assertRedirect(route('admin.causes.index'));

        $this->assertDatabaseHas('causes', ['id' => $cause->id, 'title' => 'UPDATED', 'is_active' => false, 'sort_order' => 1]);

        $this->actingAs($this->admin())
            ->delete(route('admin.causes.destroy', $cause))
            ->assertRedirect(route('admin.causes.index'));

        $this->assertDatabaseMissing('causes', ['id' => $cause->id]);
    }

    public function test_create_requires_title_description_and_image(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.causes.store'), [])
            ->assertSessionHasErrors(['title', 'description', 'image']);
    }
}
