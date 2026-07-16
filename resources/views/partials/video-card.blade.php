{{-- Animated click-to-play video card. Param: $videoKey (see config/appeal-videos.php). --}}
@php
    use App\Support\VideoSource;

    $v = config('appeal-videos.'.($videoKey ?? 'default'), config('appeal-videos.default'));
    $isEmbed = VideoSource::isEmbed($v['url']);
    $src = $isEmbed ? VideoSource::embedUrl($v['url'], true) : VideoSource::playableUrl($v['url']);
@endphp

<div class="nf-videocard nf-reveal" data-video-card data-embed="{{ $isEmbed ? '1' : '0' }}" data-src="{{ $src }}">
    {{-- Poster --}}
    <img src="{{ asset($v['poster']) }}" alt="{{ $v['title'] }}" class="nf-videocard__poster">
    <span class="nf-videocard__veil" aria-hidden="true"></span>

    {{-- Play button --}}
    <button type="button" class="nf-videocard__play" data-video-play aria-label="Play video: {{ $v['title'] }}">
        <span class="nf-videocard__ring" aria-hidden="true"></span>
        <svg class="nf-videocard__icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
            <path d="M8 5.14v13.72a1 1 0 0 0 1.54.84l10.72-6.86a1 1 0 0 0 0-1.68L9.54 4.3A1 1 0 0 0 8 5.14z"/>
        </svg>
    </button>

    {{-- Caption --}}
    <span class="nf-videocard__caption">
        <span class="nf-videocard__eyebrow">Watch</span>
        <span class="nf-videocard__title">{{ $v['title'] }}</span>
    </span>
</div>
