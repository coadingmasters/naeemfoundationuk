@extends('layouts.app')

@section('title', 'Order Confirmed — ' . config('app.name'))
@section('header-solid', 'yes')

@section('content')

    <section class="bg-cream/40 py-16 sm:py-24">
        <div class="nf-container">
            <div class="nf-pop mx-auto max-w-lg rounded-3xl border border-navy/10 bg-white p-8 text-center shadow-xl sm:p-10">
                <span class="mx-auto grid h-16 w-16 place-items-center rounded-full bg-green-100 text-green-600">
                    <svg class="h-9 w-9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </span>
                <h1 class="mt-5 text-2xl font-extrabold text-navy-dark sm:text-3xl">Thank you, {{ $order['name'] }}!</h1>
                <p class="mt-3 text-sm leading-relaxed text-gray-600 sm:text-base">
                    Your order has been received. Our team will be in touch shortly to confirm delivery and take secure payment.
                </p>

                <div class="mt-6 inline-flex items-center gap-2 rounded-full bg-cream px-5 py-2 text-sm font-semibold text-navy-dark">
                    Order reference
                    <span class="text-brand">{{ $order['reference'] }}</span>
                </div>

                <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row">
                    <a href="{{ route('shop') }}" class="btn-brand justify-center px-6 py-3">Continue shopping</a>
                    <a href="{{ route('home') }}" class="inline-flex items-center justify-center rounded-md border border-gray-200 px-6 py-3 text-sm font-semibold text-navy transition hover:bg-gray-50">Back to home</a>
                </div>
            </div>
        </div>
    </section>

@endsection
