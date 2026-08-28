<?php

namespace Tests\Feature;

use App\Models\PageVideo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AdminPageVideoTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['is_admin' => true]);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('admin.page-videos.index'))->assertRedirect(route('admin.login'));
    }

    public function test_non_admin_cannot_access(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)->get(route('admin.page-videos.index'))->assertForbidden();
    }

    public function test_index_lists_every_giving_page(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.page-videos.index'))
            ->assertOk()
            ->assertSee('Education Sponsorships')
            ->assertSee('Clean Water')
            ->assertSee('Default');
    }

    public function test_create_and_edit_screens_render(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->get(route('admin.page-videos.create'))
            ->assertOk()->assertSee('Choose a page');

        $this->actingAs($admin)->get(route('admin.page-videos.edit', 'clean-water'))
            ->assertOk()->assertSee('Clean Water');
    }

    public function test_edit_screen_404s_for_an_unknown_page(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.page-videos.edit', 'not-a-real-page'))
            ->assertNotFound();
    }

    public function test_admin_can_set_a_custom_video_and_it_shows_on_the_page(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.page-videos.store'), [
                'page_key' => 'clean-water',
                'video_url' => 'https://www.youtube.com/watch?v=NEWVIDEO123',
                'is_active' => '1',
            ])
            ->assertRedirect(route('admin.page-videos.index'));

        $this->assertDatabaseHas('page_videos', [
            'page_key' => 'clean-water',
            'video_url' => 'https://www.youtube.com/watch?v=NEWVIDEO123',
            'is_active' => true,
        ]);

        $this->get('/clean-water')->assertOk()->assertSee('/embed/NEWVIDEO123', false);
    }

    public function test_store_rejects_an_unknown_page_or_a_missing_video(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->post(route('admin.page-videos.store'), [
            'page_key' => 'made-up-page',
            'video_url' => 'https://youtu.be/abc',
        ])->assertSessionHasErrors('page_key');

        $this->actingAs($admin)->post(route('admin.page-videos.store'), [
            'page_key' => 'clean-water',
        ])->assertSessionHasErrors('video_url');
    }

    public function test_admin_can_upload_a_video_file(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.page-videos.store'), [
                'page_key' => 'water-well',
                'video_file' => UploadedFile::fake()->create('clip.mp4', 800, 'video/mp4'),
                'is_active' => '1',
            ])
            ->assertRedirect(route('admin.page-videos.index'));

        $video = PageVideo::where('page_key', 'water-well')->firstOrFail();
        $this->assertStringStartsWith('videos/pages/', $video->video_url);
        $this->assertFileExists(public_path($video->video_url));

        @unlink(public_path($video->video_url));
    }

    public function test_hidden_override_falls_back_to_the_default_video(): void
    {
        PageVideo::create([
            'page_key' => 'clean-water',
            'video_url' => 'https://www.youtube.com/watch?v=HIDDENVIDEO',
            'is_active' => false,
        ]);

        $this->get('/clean-water')
            ->assertOk()
            ->assertDontSee('HIDDENVIDEO')
            ->assertSee('qJMuAUdxpm0'); // the config default for clean-water
    }

    public function test_reset_to_default_removes_the_override(): void
    {
        PageVideo::create([
            'page_key' => 'clean-water',
            'video_url' => 'https://www.youtube.com/watch?v=NEWVIDEO123',
            'is_active' => true,
        ]);

        $this->actingAs($this->admin())
            ->delete(route('admin.page-videos.destroy', 'clean-water'))
            ->assertRedirect(route('admin.page-videos.index'));

        $this->assertDatabaseMissing('page_videos', ['page_key' => 'clean-water']);
        $this->get('/clean-water')->assertOk()->assertSee('qJMuAUdxpm0');
    }

    public function test_facebook_link_is_embedded_on_the_prosthetic_limb_page(): void
    {
        // config default for prosthetic-limb is a Facebook reel.
        $this->get('/prosthetic-limb')
            ->assertOk()
            ->assertSee('facebook.com/plugins/video.php', false);
    }
}
