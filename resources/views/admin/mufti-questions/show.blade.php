@extends('admin.layouts.app')

@section('title', 'Question')
@section('heading', 'Ask a Mufti')
@section('subheading', 'Read the question and send your reply.')

@section('actions')
    <a href="{{ route('admin.mufti-questions.index') }}"
       class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-navy transition hover:border-brand hover:text-brand">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M11 18l-6-6 6-6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        All questions
    </a>
@endsection

@section('content')
    @php
        $initials = \Illuminate\Support\Str::of($question->name)->explode(' ')->filter()->take(2)
            ->map(fn ($w) => \Illuminate\Support\Str::substr($w, 0, 1))->implode('');
    @endphp

    <div class="grid gap-6 lg:grid-cols-3 lg:items-start">

        {{-- Main: question + reply --}}
        <div class="space-y-6 lg:col-span-2">
            {{-- Question --}}
            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm sm:p-7">
                <div class="flex items-center justify-between">
                    <p class="text-[11px] font-bold uppercase tracking-wide text-brand">The question</p>
                    @if ($question->answered_at)
                        <span class="rounded-full bg-green-100 px-2.5 py-1 text-[11px] font-semibold text-green-700">Replied {{ $question->answered_at->format('d M Y') }}</span>
                    @else
                        <span class="rounded-full bg-amber-100 px-2.5 py-1 text-[11px] font-semibold text-amber-700">New — awaiting reply</span>
                    @endif
                </div>
                <div class="mt-3 rounded-xl border-l-2 border-brand bg-cream/60 p-5 text-[15px] leading-relaxed text-navy-dark">
                    {!! nl2br(e($question->message)) !!}
                </div>
            </div>

            {{-- Reply --}}
            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm sm:p-7">
                <h3 class="text-base font-bold text-navy-dark">Your answer</h3>
                <p class="mt-1 text-sm text-gray-500">
                    This is emailed straight to <span class="font-semibold text-navy-dark">{{ $question->email }}</span> using a professional template.
                </p>

                <form method="POST" action="{{ route('admin.mufti-questions.reply', $question) }}" class="mt-4">
                    @csrf
                    <textarea name="answer" rows="12" required placeholder="Write the scholar’s answer here…"
                              class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-[15px] leading-relaxed text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30">{{ old('answer', $question->answer) }}</textarea>
                    @error('answer') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror

                    <div class="mt-4 flex items-center justify-end gap-3">
                        <a href="{{ route('admin.mufti-questions.index') }}" class="rounded-lg border border-gray-200 px-5 py-2.5 text-sm font-semibold text-navy transition hover:bg-gray-50">Cancel</a>
                        <button type="submit"
                                class="inline-flex items-center gap-2 rounded-lg bg-brand px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-dark">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 2 11 13M22 2l-7 20-4-9-9-4 20-7z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            {{ $question->answered_at ? 'Send again' : 'Send reply' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Aside: who asked --}}
        <div class="lg:sticky lg:top-24">
            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                <div class="flex items-center gap-3">
                    <span class="grid h-12 w-12 shrink-0 place-items-center rounded-full bg-brand/10 text-sm font-bold uppercase text-brand">{{ $initials ?: '?' }}</span>
                    <div class="min-w-0">
                        <p class="font-bold text-navy-dark">{{ $question->name }}</p>
                        <p class="truncate text-xs text-gray-400">Submitted {{ $question->created_at?->format('d M Y · g:i a') }}</p>
                    </div>
                </div>

                <dl class="mt-5 space-y-3 border-t border-gray-100 pt-4 text-sm">
                    <div>
                        <dt class="text-xs font-medium text-gray-400">Email</dt>
                        <dd><a href="mailto:{{ $question->email }}" class="font-semibold text-brand hover:underline">{{ $question->email }}</a></dd>
                    </div>
                    @if ($question->phone)
                        <div>
                            <dt class="text-xs font-medium text-gray-400">Phone</dt>
                            <dd><a href="tel:{{ $question->phone }}" class="font-semibold text-navy-dark hover:text-brand">{{ $question->phone }}</a></dd>
                        </div>
                    @endif
                    <div>
                        <dt class="text-xs font-medium text-gray-400">Region</dt>
                        <dd class="font-semibold text-navy-dark">{{ config('countries.list.'.$question->region.'.flag', '') }} {{ config('countries.list.'.$question->region.'.name', $question->region) }}</dd>
                    </div>
                </dl>

                <form method="POST" action="{{ route('admin.mufti-questions.destroy', $question) }}" class="mt-5 border-t border-gray-100 pt-4">
                    @csrf
                    @method('DELETE')
                    <button type="button" data-admin-delete data-label="{{ \Illuminate\Support\Str::limit($question->name.'’s question', 40) }}"
                            class="inline-flex w-full items-center justify-center gap-1.5 rounded-lg border border-gray-200 px-4 py-2.5 text-sm font-semibold text-red-600 transition hover:border-red-300 hover:bg-red-50">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 7h16M9 7V5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2m2 0v12a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Delete question
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
