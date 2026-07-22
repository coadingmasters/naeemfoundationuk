{{-- Admin success/error flashes as animated toasts (bottom-right, auto-dismiss). --}}
@if (session('success') || session('error'))
    <div class="nf-atoast-wrap" data-atoast-wrap>
        @foreach (['success' => 'Success', 'error' => 'Heads up'] as $type => $title)
            @if (session($type))
                <div class="nf-atoast nf-atoast--{{ $type }}" data-atoast>
                    <span class="nf-atoast__icon">
                        @if ($type === 'success')
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        @else
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M12 8v5m0 3h.01M10.3 3.86l-7.6 13A1.5 1.5 0 0 0 4 19h16a1.5 1.5 0 0 0 1.3-2.14l-7.6-13a1.5 1.5 0 0 0-2.6 0z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        @endif
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="nf-atoast__title">{{ $title }}</p>
                        <p class="nf-atoast__msg">{{ session($type) }}</p>
                    </div>
                    <button type="button" data-atoast-close aria-label="Dismiss" class="nf-atoast__close">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 6l12 12M18 6L6 18" stroke-linecap="round"/></svg>
                    </button>
                    <span class="nf-atoast__bar"></span>
                </div>
            @endif
        @endforeach
    </div>

    <script>
        (function () {
            document.querySelectorAll('[data-atoast]').forEach((t, i) => {
                const hide = () => { t.classList.add('is-out'); setTimeout(() => t.remove(), 450); };
                t.querySelector('[data-atoast-close]')?.addEventListener('click', hide);
                setTimeout(hide, 4300 + i * 130);
            });
        })();
    </script>
@endif
