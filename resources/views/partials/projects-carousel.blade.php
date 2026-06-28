{{-- Continuous, auto-scrolling carousel of project cards. Expects $projects. --}}
@php
    $projects = $projects ?? collect();
    // Duplicate the list so the loop is seamless; scale speed to the count.
    $loopProjects = $projects->concat($projects);
    $duration = max(20, $projects->count() * 6);
@endphp

@if ($projects->isNotEmpty())
    <div class="nf-xscroll -mx-3" aria-label="Our projects">
        <div class="nf-xscroll__track" style="animation-duration: {{ $duration }}s">
            @foreach ($loopProjects as $i => $project)
                <div class="nf-xscroll__item w-[270px] px-3 sm:w-[300px]" @if ($i >= $projects->count()) aria-hidden="true" @endif>
                    <div class="group flex h-full flex-col overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition hover:shadow-lg">
                        <div class="overflow-hidden">
                            <img src="{{ asset($project->image) }}" alt="{{ $project->title }}"
                                 class="h-48 w-full object-cover transition duration-500 group-hover:scale-105">
                        </div>
                        <div class="flex flex-1 flex-col p-5">
                            <h3 class="font-bold text-brand">{{ $project->title }}</h3>
                            <p class="mt-1.5 line-clamp-2 flex-1 text-sm text-gray-500">{{ $project->description }}</p>
                            <a href="{{ $project->link ?: '#' }}" class="btn-brand mt-4 w-full py-2.5">Donate Now</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
