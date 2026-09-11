{{-- Renders a single Hajj video. Expects $video with: is_embed, embed_url,
     playable_url, title, video_url.
     Facebook videos are almost always Reels (portrait, 9:16) — cropping them
     into a landscape box looks wrong, so they get a narrower, taller "phone"
     frame instead. Everything else (YouTube/Vimeo/uploaded files) keeps the
     standard 16:9 frame. --}}
@php
    $isFacebook = \App\Support\VideoSource::isFacebook($video->video_url ?? null);
    $frameClass = $isFacebook
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
