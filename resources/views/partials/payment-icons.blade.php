{{-- Accepted payment methods row (Fundraising Regulator + card schemes). --}}
<div class="mt-6 flex flex-wrap items-center justify-center gap-3 border-t border-gray-100 pt-5">
    {{-- Fundraising Regulator --}}
    <img src="{{ asset('images/firstpaymenticon.png') }}" alt="Registered with Fundraising Regulator" class="h-10 w-auto sm:h-11">

    {{-- Visa --}}
    <span class="grid h-9 w-14 place-items-center rounded-md bg-white shadow-sm ring-1 ring-gray-200" title="Visa">
        <span class="text-[15px] font-black italic leading-none tracking-tight text-[#1A1F71]">VISA</span>
    </span>

    {{-- Mastercard --}}
    <span class="grid h-9 w-14 place-items-center rounded-md bg-white shadow-sm ring-1 ring-gray-200" title="Mastercard">
        <svg class="h-5 w-auto" viewBox="0 0 48 30" fill="none" aria-hidden="true">
            <circle cx="19" cy="15" r="10" fill="#EB001B"/>
            <circle cx="29" cy="15" r="10" fill="#F79E1B"/>
            <path d="M24 7.2a10 10 0 0 1 0 15.6 10 10 0 0 1 0-15.6z" fill="#FF5F00"/>
        </svg>
    </span>

    {{-- PayPal --}}
    <span class="grid h-9 w-14 place-items-center rounded-md bg-white shadow-sm ring-1 ring-gray-200" title="PayPal">
        <span class="text-[13px] font-black italic leading-none">
            <span class="text-[#003087]">Pay</span><span class="text-[#009CDE]">Pal</span>
        </span>
    </span>
</div>
