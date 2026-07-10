@extends('layouts.app')

@section('title', 'Complete Contribution — ' . config('app.name'))

@section('content')

    <section class="bg-cream py-10 sm:py-14">
        <div class="nf-container">
            <div class="mx-auto max-w-4xl overflow-hidden rounded-2xl bg-navy px-5 py-8 shadow-xl sm:px-8 sm:py-10 lg:px-12">

                <h1 class="text-center text-2xl font-extrabold text-white sm:text-3xl lg:text-4xl">
                    Complete Contribution
                </h1>

                {{-- ===== Flash / errors ===== --}}
                @if (session('success'))
                    <p class="mt-5 rounded-lg bg-white/10 px-4 py-3 text-center text-sm text-white ring-1 ring-white/20">
                        {{ session('success') }}
                    </p>
                @endif

                @if ($errors->any())
                    <div class="mt-5 rounded-lg bg-red-500/15 px-4 py-3 text-sm text-white ring-1 ring-red-300/40">
                        <ul class="list-inside list-disc space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- ===== Basket ===== --}}
                @if (empty($items))
                    <div class="mt-8 rounded-xl bg-white p-8 text-center">
                        <p class="text-sm text-gray-600">Your contribution is empty.</p>
                        <a href="{{ route('home') }}" class="btn-brand mt-4">Browse causes</a>
                    </div>
                @else
                    <div class="mt-8 grid gap-4 sm:grid-cols-2">
                        @foreach ($items as $item)
                            <div class="flex items-center gap-3 rounded-lg bg-white p-3">
                                <img src="{{ asset($item['image']) }}" alt="{{ $item['cause'] }}"
                                     class="h-16 w-16 shrink-0 rounded object-cover">

                                <div class="min-w-0 flex-1">
                                    <h3 class="truncate text-base font-bold text-navy-dark">{{ $item['cause'] }}</h3>
                                    <p class="text-xs text-gray-500">
                                        £{{ number_format($item['amount'], 2) }} each
                                        @if (($item['frequency'] ?? 'one-off') === 'monthly')
                                            <span class="font-semibold text-brand">/ monthly</span>
                                        @endif
                                    </p>

                                    <div class="mt-1.5 flex items-center gap-3">
                                        @include('partials.cart-stepper', ['item' => $item])
                                        <span class="text-sm font-bold text-navy-dark">
                                            £{{ number_format($item['amount'] * $item['qty'], 2) }}
                                        </span>
                                    </div>
                                </div>

                                <form method="POST" action="{{ route('donate.remove', $item['id']) }}" class="shrink-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="rounded bg-brand px-3 py-1.5 text-xs font-semibold text-white transition-colors hover:bg-brand-dark">
                                        remove
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>

                    {{-- ===== Total ===== --}}
                    <div class="mt-4 flex items-center justify-between rounded-lg bg-white px-5 py-4">
                        <span class="text-lg font-bold text-navy-dark">Total</span>
                        <span class="text-lg font-bold text-navy-dark">£{{ number_format($subtotal, 2) }}</span>
                    </div>
                @endif

                {{-- ===== Details form ===== --}}
                <form method="POST" action="{{ route('donate.store') }}" class="mt-10">
                    @csrf

                    <h2 class="text-xl font-bold text-white sm:text-2xl">Enter Your Details</h2>

                    {{-- Gift Aid --}}
                    <h3 class="mt-6 text-lg font-semibold italic text-white/90">Gift Aid</h3>
                    <label class="mt-2 flex cursor-pointer items-start gap-3">
                        <input type="checkbox" name="gift_aid" value="1" @checked(old('gift_aid', true))
                               class="mt-0.5 h-5 w-5 shrink-0 rounded border-white/40 bg-white/10 text-brand focus:ring-2 focus:ring-white/40">
                        <span class="text-xs leading-relaxed text-white/85 sm:text-sm">
                            I am a UK taxpayer, donating as an individual and would like Naeem Foundation to claim Gift Aid
                            on my donation <span class="text-brand">*</span>
                        </span>
                    </label>

                    {{-- Name --}}
                    <div class="mt-6 grid gap-5 sm:grid-cols-2">
                        <div>
                            <label for="first_name" class="mb-1.5 block text-xs font-semibold text-white sm:text-sm">*First Name</label>
                            <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}" required
                                   class="nf-dark-input">
                            @error('first_name') <p class="mt-1 text-xs text-red-300">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="last_name" class="mb-1.5 block text-xs font-semibold text-white sm:text-sm">*Last Name</label>
                            <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}" required
                                   class="nf-dark-input">
                            @error('last_name') <p class="mt-1 text-xs text-red-300">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Contact --}}
                    <div class="mt-5 grid gap-5 sm:grid-cols-2">
                        <div>
                            <label for="email" class="mb-1.5 block text-xs font-semibold text-white sm:text-sm">*Email</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                   class="nf-dark-input">
                            @error('email') <p class="mt-1 text-xs text-red-300">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="phone" class="mb-1.5 block text-xs font-semibold text-white sm:text-sm">*Contact Number</label>
                            <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" required
                                   class="nf-dark-input">
                            @error('phone') <p class="mt-1 text-xs text-red-300">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- On behalf of org --}}
                    <label class="mt-6 flex cursor-pointer items-center gap-3">
                        <input type="checkbox" name="on_behalf_of_organisation" value="1" @checked(old('on_behalf_of_organisation'))
                               class="h-5 w-5 shrink-0 rounded border-white/40 bg-white/10 text-brand focus:ring-2 focus:ring-white/40">
                        <span class="text-xs text-white/85 sm:text-sm">I am donating on behalf of an organization.</span>
                    </label>

                    {{-- Billing address --}}
                    <div class="mt-6">
                        <label for="billing_address" class="mb-1.5 block text-xs font-semibold text-white sm:text-sm">*Billing Address</label>
                        <input id="billing_address" type="text" name="billing_address" value="{{ old('billing_address') }}" required
                               class="nf-dark-input">
                        @error('billing_address') <p class="mt-1 text-xs text-red-300">{{ $message }}</p> @enderror
                    </div>

                    {{-- Transaction fee --}}
                    <label class="mt-8 flex cursor-pointer items-start gap-3">
                        <input type="checkbox" name="cover_fee" value="1" @checked(old('cover_fee', true))
                               class="mt-0.5 h-5 w-5 shrink-0 rounded border-white/40 bg-white/10 text-brand focus:ring-2 focus:ring-white/40">
                        <span class="text-xs leading-relaxed text-white/85 sm:text-sm">
                            We are charged a small fee of 1.4% on every transaction by our payment provider. Would you like
                            to cover the transaction fee of <span class="font-semibold">£{{ number_format($fee, 2) }}</span>
                            so that we receive your full donation?
                        </span>
                    </label>

                    <div class="mt-8 flex justify-end">
                        <button type="submit" @disabled(empty($items))
                                class="rounded-lg bg-white px-6 py-3 text-sm font-bold text-navy-dark transition-colors hover:bg-cream disabled:cursor-not-allowed disabled:opacity-50">
                            Add Payment Details
                        </button>
                    </div>
                </form>

                {{-- ===== Add-ons (separate forms so they don't submit the details form) ===== --}}
                <div class="mt-10">
                    <h2 class="text-xl font-bold text-white sm:text-2xl">Want to add these ?</h2>
                    <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-4">
                        @foreach ($addons as $addon)
                            <form method="POST" action="{{ route('donate.add') }}">
                                @csrf
                                <input type="hidden" name="cause" value="{{ $addon['cause'] }}">
                                <input type="hidden" name="amount" value="{{ $addon['amount'] }}">
                                <input type="hidden" name="frequency" value="one-off">
                                <button type="submit"
                                        class="w-full rounded-lg bg-white px-3 py-3 text-xs font-bold text-navy-dark transition-colors hover:bg-cream sm:text-sm">
                                    £{{ $addon['amount'] }} {{ $addon['cause'] }}
                                </button>
                            </form>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection
