@extends('layouts.app')

@section('title', 'Payment — ' . config('app.name'))

@section('content')

    <section class="bg-cream py-14 sm:py-20">
        <div class="nf-container">
            <div class="mx-auto max-w-xl rounded-2xl bg-navy px-6 py-10 text-center shadow-xl sm:px-10">

                <span class="mx-auto grid h-14 w-14 place-items-center rounded-full bg-white/10 text-white ring-1 ring-white/20">
                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>

                <h1 class="mt-5 text-2xl font-extrabold text-white sm:text-3xl">Contribution Received</h1>
                <p class="mt-3 text-sm leading-relaxed text-white/80">
                    JazakAllah khair. Your details have been saved and your contribution is reserved.
                </p>

                <div class="mt-6 space-y-2 rounded-xl bg-white px-5 py-4 text-left">
                    <p class="flex justify-between text-sm">
                        <span class="text-gray-500">Reference</span>
                        <span class="font-bold text-navy-dark">{{ $reference }}</span>
                    </p>
                    <p class="flex justify-between text-sm">
                        <span class="text-gray-500">Amount</span>
                        <span class="font-bold text-navy-dark">£{{ number_format($total, 2) }}</span>
                    </p>
                </div>

                <p class="mt-6 rounded-lg bg-white/10 px-4 py-3 text-xs leading-relaxed text-white/85 ring-1 ring-white/20">
                    Our secure payment gateway is being connected. A member of our team will contact you shortly to
                    complete your payment, or you may use the bank details in the footer.
                </p>

                <a href="{{ route('home') }}" class="btn-brand mt-7">
                    Back to Home
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
            </div>
        </div>
    </section>

@endsection
