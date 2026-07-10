<?php

namespace Tests\Feature;

use App\Models\CommunityEnquiry;
use App\Models\CommunityVideo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommunityCentreTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_page_loads_with_its_key_sections(): void
    {
        $this->get(route('community-centre'))
            ->assertOk()
            ->assertSee('Community Centre')
            ->assertSee('Get in touch')
            ->assertSee('Serving Our Community', false)
            ->assertSee('Opening Hours')
            ->assertSee('Watch Our Community in Action');
    }

    public function test_dummy_videos_are_shown_when_none_have_been_uploaded(): void
    {
        $this->assertSame(0, CommunityVideo::count());

        $this->get(route('community-centre'))
            ->assertOk()
            ->assertSee('Inside Our Community Centre')
            ->assertSee('Youth Programmes in Action');
    }

    public function test_uploaded_videos_replace_the_dummy_ones(): void
    {
        CommunityVideo::create([
            'title' => 'Real Centre Tour',
            'video_url' => 'https://www.youtube.com/watch?v=abc123XYZ',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $html = $this->get(route('community-centre'))->assertOk()->getContent();

        $this->assertStringContainsString('Real Centre Tour', $html);
        $this->assertStringNotContainsString('Inside Our Community Centre', $html);
        // YouTube links are rendered as an embed.
        $this->assertStringContainsString('youtube.com/embed/abc123XYZ', $html);
    }

    public function test_inactive_videos_are_not_shown(): void
    {
        CommunityVideo::create([
            'title' => 'Hidden Clip',
            'video_url' => 'https://example.com/a.mp4',
            'is_active' => false,
        ]);

        $this->get(route('community-centre'))
            ->assertOk()
            ->assertDontSee('Hidden Clip');
    }

    public function test_an_enquiry_can_be_submitted(): void
    {
        $this->post(route('community-centre.enquire'), [
            'name' => 'Ahsan Nawaz',
            'email' => 'ahsan@example.com',
            'phone' => '+441234567890',
            'subject' => 'Hall & event booking',
            'message' => 'I would like to book the hall for a nikah ceremony.',
        ])->assertRedirect(route('community-centre').'#enquire');

        $enquiry = CommunityEnquiry::sole();

        $this->assertSame('Ahsan Nawaz', $enquiry->name);
        $this->assertSame('ahsan@example.com', $enquiry->email);
        $this->assertSame('Hall & event booking', $enquiry->subject);
    }

    public function test_the_enquiry_form_validates_required_fields(): void
    {
        $this->post(route('community-centre.enquire'), [])
            ->assertSessionHasErrors(['name', 'email', 'subject', 'message']);

        $this->assertSame(0, CommunityEnquiry::count());
    }

    public function test_the_enquiry_form_rejects_an_invalid_email(): void
    {
        $this->post(route('community-centre.enquire'), [
            'name' => 'Ahsan',
            'email' => 'not-an-email',
            'subject' => 'General enquiry',
            'message' => 'Hello',
        ])->assertSessionHasErrors('email');
    }

    public function test_the_header_links_to_the_community_centre(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee(route('community-centre'), false);
    }
}
