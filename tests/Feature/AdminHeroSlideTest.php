<?php

namespace Tests\Feature;

use App\Models\HeroSlide;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminHeroSlideTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['is_admin' => true]);
    }

    public function test_home_page_renders_active_slides(): void
    {
        HeroSlide::create([
            'title' => "VISIBLE SLIDE",
            'image' => 'images/homepagehero.png',
            'is_active' => true,
        ]);
        HeroSlide::create([
            'title' => "HIDDEN SLIDE",
            'image' => 'images/homepagehero.png',
            'is_active' => false,
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('VISIBLE SLIDE');
        $response->assertDontSee('HIDDEN SLIDE');
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/admin')->assertRedirect(route('admin.login'));
    }

    public function test_non_admin_cannot_access_dashboard(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)->get('/admin')->assertForbidden();
    }

    public function test_admin_can_login_and_view_dashboard(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'password' => bcrypt('secret123'),
        ]);

        $response = $this->post(route('admin.login.attempt'), [
            'email' => $admin->email,
            'password' => 'secret123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);

        $this->get('/admin')->assertOk()->assertSee('Dashboard');
    }

    public function test_admin_can_create_a_hero_slide_with_image(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->admin())->post(route('admin.hero-slides.store'), [
            'title' => "NEW SLIDE\nLINE TWO",
            'subtitle' => 'A subtitle',
            'image' => UploadedFile::fake()->create('slide.jpg', 200, 'image/jpeg'),
            'button_text' => 'Donate',
            'button_url' => '#',
            'sort_order' => 1,
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.hero-slides.index'));
        $this->assertDatabaseHas('hero_slides', ['title' => "NEW SLIDE\nLINE TWO", 'is_active' => true]);

        $slide = HeroSlide::first();
        $this->assertStringStartsWith('images/hero/', $slide->image);
        $this->assertFileExists(public_path($slide->image));

        @unlink(public_path($slide->image));
    }

    public function test_admin_can_update_and_delete_a_slide(): void
    {
        $slide = HeroSlide::create([
            'title' => 'OLD TITLE',
            'image' => 'images/homepagehero.png',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $this->actingAs($this->admin())
            ->put(route('admin.hero-slides.update', $slide), [
                'title' => 'UPDATED TITLE',
                'sort_order' => 5,
                'is_active' => '0',
            ])
            ->assertRedirect(route('admin.hero-slides.index'));

        $this->assertDatabaseHas('hero_slides', ['id' => $slide->id, 'title' => 'UPDATED TITLE', 'is_active' => false]);

        $this->actingAs($this->admin())
            ->delete(route('admin.hero-slides.destroy', $slide))
            ->assertRedirect(route('admin.hero-slides.index'));

        $this->assertDatabaseMissing('hero_slides', ['id' => $slide->id]);
    }

    public function test_all_admin_pages_render(): void
    {
        $this->get(route('admin.login'))->assertOk()->assertSee('Welcome back');

        $admin = $this->admin();
        $slide = HeroSlide::create([
            'title' => 'RENDER TEST',
            'image' => 'images/homepagehero.png',
            'is_active' => true,
        ]);

        $this->actingAs($admin)->get(route('admin.hero-slides.index'))->assertOk()->assertSee('RENDER TEST');
        $this->actingAs($admin)->get(route('admin.hero-slides.create'))->assertOk()->assertSee('New Hero Slide');
        $this->actingAs($admin)->get(route('admin.hero-slides.edit', $slide))->assertOk()->assertSee('Edit Hero Slide');
    }

    public function test_create_requires_title_and_image(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.hero-slides.store'), ['sort_order' => 0])
            ->assertSessionHasErrors(['title', 'image']);
    }

    public function test_sort_order_auto_assigns_filling_gaps(): void
    {
        foreach ([1, 2, 4] as $o) {
            HeroSlide::create(['title' => "S{$o}", 'image' => 'images/homepagehero.png', 'is_active' => true, 'sort_order' => $o]);
        }

        $this->actingAs($this->admin())->post(route('admin.hero-slides.store'), [
            'title' => 'Gap Filler',
            'image' => UploadedFile::fake()->create('s.jpg', 100, 'image/jpeg'),
        ]);

        $gap = HeroSlide::where('title', 'Gap Filler')->first();
        $this->assertSame(3, $gap->sort_order);
        @unlink(public_path($gap->image));
    }

    public function test_seeder_is_idempotent_and_non_destructive(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseHas('users', ['email' => 'admin@naeemfoundation.org', 'is_admin' => true]);
        $this->assertSame(3, HeroSlide::count());

        // Simulate a real admin: change the password and remove a slide.
        $admin = User::where('email', 'admin@naeemfoundation.org')->first();
        $admin->update(['password' => Hash::make('changed-password')]);
        HeroSlide::first()->delete();

        // Re-seeding (as deploy does) must not clobber anything.
        $this->seed(DatabaseSeeder::class);

        $this->assertTrue(Hash::check('changed-password', $admin->fresh()->password));
        $this->assertSame(2, HeroSlide::count());
    }
}
