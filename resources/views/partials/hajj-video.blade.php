{{-- Renders a single Hajj video. Expects $video with: is_embed, embed_url, playable_url, title. --}}
<div class="aspect-video w-full overflow-hidden rounded-2xl bg-navy-dark shadow-lg ring-1 ring-black/5">
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
