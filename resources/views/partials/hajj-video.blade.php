{{-- Renders a single Hajj video. Expects $video with: is_embed, embed_url,
     playable_url, title, video_url.
     Testimonial-style clips — Facebook links (almost always Reels) and any
     directly uploaded file (these are phone-shot review videos, shot
     vertically) — are portrait, so cropping them into a 16:9 box looks
     wrong. They get a narrower, taller "phone" frame instead. YouTube/Vimeo
     embeds (standard landscape-hosted video) keep the normal 16:9 frame. --}}
@php
    $isPortrait = \App\Support\VideoSource::isFacebook($video->video_url ?? null) || ! $video->is_embed;
    $frameClass = $isPortrait
        ? 'mx-auto aspect-[9/16] w-full max-w-[280px]'
        : 'aspect-video w-full';
@endphp
<div class="{{ $frameClass }} overflow-hidden rounded-2xl bg-navy-dark shadow-lg ring-1 ring-black/5">
    @if ($video->is_embed)
        <iframe src="{{ $video->embed_url }}" title="{{ $video->title }}"
                class="h-full w-full" loading="lazy"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
    @else
        <video controls preload="metadata" class="h-full w-full object-cover">
            <source src="{{ $video->playable_url }}">
            Your browser does not support the video tag.
        </video>
    @endif
</div>
