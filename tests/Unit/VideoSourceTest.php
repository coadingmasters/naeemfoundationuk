<?php

namespace Tests\Unit;

use App\Support\VideoSource;
use PHPUnit\Framework\TestCase;

class VideoSourceTest extends TestCase
{
    public function test_youtube_links_resolve_to_an_embed_url(): void
    {
        foreach ([
            'https://www.youtube.com/watch?v=i6PE9GlHhb4',
            'https://youtu.be/i6PE9GlHhb4',
            'https://www.youtube.com/embed/i6PE9GlHhb4',
            'https://www.youtube.com/shorts/i6PE9GlHhb4',
        ] as $url) {
            $this->assertTrue(VideoSource::isEmbed($url));
            $this->assertSame('https://www.youtube.com/embed/i6PE9GlHhb4', VideoSource::embedUrl($url));
            $this->assertSame('https://www.youtube.com/embed/i6PE9GlHhb4?autoplay=1&rel=0', VideoSource::embedUrl($url, true));
        }
    }

    public function test_vimeo_links_resolve_to_a_player_url(): void
    {
        $this->assertTrue(VideoSource::isEmbed('https://vimeo.com/123456789'));
        $this->assertSame('https://player.vimeo.com/video/123456789', VideoSource::embedUrl('https://vimeo.com/123456789'));
    }

    public function test_facebook_links_resolve_to_the_plugin_embed(): void
    {
        foreach ([
            'https://www.facebook.com/reel/1368501788717734',
            'https://www.facebook.com/watch/?v=1368501788717734',
            'https://fb.watch/abcdEFGH/',
        ] as $url) {
            $this->assertTrue(VideoSource::isEmbed($url));
            $this->assertTrue(VideoSource::isFacebook($url));

            $embed = VideoSource::embedUrl($url);
            $this->assertStringStartsWith('https://www.facebook.com/plugins/video.php?href=', $embed);
            $this->assertStringContainsString(rawurlencode($url), $embed);

            $this->assertStringContainsString('autoplay=1', VideoSource::embedUrl($url, true));
        }
    }

    public function test_non_facebook_links_are_not_flagged_as_facebook(): void
    {
        $this->assertFalse(VideoSource::isFacebook('https://www.youtube.com/watch?v=i6PE9GlHhb4'));
        $this->assertFalse(VideoSource::isFacebook('https://example.com/video.mp4'));
    }

    public function test_plain_file_paths_are_left_untouched_by_embed_url(): void
    {
        $this->assertFalse(VideoSource::isEmbed('videos/pages/clip.mp4'));
        $this->assertSame('videos/pages/clip.mp4', VideoSource::embedUrl('videos/pages/clip.mp4'));
    }
}
