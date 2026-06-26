<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AdminProjectTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['is_admin' => true]);
    }

    public function test_fidya_page_renders_active_projects(): void
    {
        Project::create([
            'title' => 'VISIBLE PROJECT',
            'description' => 'Shown on the Fidya page.',
            'image' => 'images/changinslives2.jpg',
            'is_active' => true,
        ]);
        Project::create([
            'title' => 'HIDDEN PROJECT',
            'description' => 'Not shown.',
            'image' => 'images/changinslives3.jpg',
            'is_active' => false,
        ]);

        $response = $this->get(route('fidya'));

        $response->assertOk();
        $response->assertSee('Our Projects');
        $response->assertSee('VISIBLE PROJECT');
        $response->assertDontSee('HIDDEN PROJECT');
    }

    public function test_admin_pages_render(): void
    {
        $admin = $this->admin();
        $project = Project::create([
            'title' => 'RENDER PROJECT',
            'description' => 'desc',
            'image' => 'images/changinslives2.jpg',
            'is_active' => true,
        ]);

        $this->actingAs($admin)->get(route('admin.projects.index'))->assertOk()->assertSee('RENDER PROJECT');
        $this->actingAs($admin)->get(route('admin.projects.create'))->assertOk()->assertSee('New Project');
        $this->actingAs($admin)->get(route('admin.projects.edit', $project))->assertOk()->assertSee('Edit Project');
    }

    public function test_guest_cannot_manage_projects(): void
    {
        $this->get(route('admin.projects.index'))->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_create_a_project_with_image(): void
    {
        $response = $this->actingAs($this->admin())->post(route('admin.projects.store'), [
            'title' => 'Binoria Water Campaign',
            'description' => 'Clean water for students.',
            'image' => UploadedFile::fake()->create('project.jpg', 200, 'image/jpeg'),
            'link' => '#',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.projects.index'));
        $this->assertDatabaseHas('projects', ['title' => 'Binoria Water Campaign', 'is_active' => true]);

        $project = Project::first();
        $this->assertStringStartsWith('images/projects/', $project->image);
        $this->assertSame(1, $project->sort_order);
        $this->assertFileExists(public_path($project->image));

        @unlink(public_path($project->image));
    }

    public function test_admin_can_update_and_delete_a_project(): void
    {
        $project = Project::create([
            'title' => 'OLD',
            'description' => 'old desc',
            'image' => 'images/changinslives2.jpg',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $this->actingAs($this->admin())
            ->put(route('admin.projects.update', $project), [
                'title' => 'UPDATED',
                'description' => 'new desc',
                'is_active' => '0',
            ])
            ->assertRedirect(route('admin.projects.index'));

        $this->assertDatabaseHas('projects', ['id' => $project->id, 'title' => 'UPDATED', 'is_active' => false]);

        $this->actingAs($this->admin())
            ->delete(route('admin.projects.destroy', $project))
            ->assertRedirect(route('admin.projects.index'));

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }

    public function test_create_requires_title_description_and_image(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.projects.store'), [])
            ->assertSessionHasErrors(['title', 'description', 'image']);
    }
}
