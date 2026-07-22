{{-- success/error flashes now render as animated toasts (admin.partials.toast). --}}

@if ($errors->any())
    <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">
        <p class="mb-1 flex items-center gap-2 font-semibold">
            <svg class="h-5 w-5 text-red-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 8v5m0 3h.01M10.3 3.86l-7.6 13A1.5 1.5 0 0 0 4 19h16a1.5 1.5 0 0 0 1.3-2.14l-7.6-13a1.5 1.5 0 0 0-2.6 0z" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Please fix the following:
        </p>
        <ul class="ml-7 list-disc space-y-0.5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
