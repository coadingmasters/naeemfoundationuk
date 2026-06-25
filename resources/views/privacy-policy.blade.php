@extends('layouts.app')

@section('title', 'Privacy Policy — ' . config('app.name'))

@section('content')

    {{-- ===================== TITLE BAND ===================== --}}
    <section class="bg-cream">
        <div class="nf-container py-12 text-center sm:py-16">
            <h1 class="text-3xl font-extrabold text-navy sm:text-4xl lg:text-5xl">Privacy Policy</h1>
            <p class="mt-3 text-sm text-gray-600 sm:text-base">Naeem Foundation Privacy Policy</p>
        </div>
    </section>

    {{-- ===================== CONTENT ===================== --}}
    <section class="py-12 sm:py-16">
        <div class="nf-container max-w-5xl space-y-12">

            {{-- Intro --}}
            <div>
                <h2 class="mb-4 text-xl font-bold text-navy sm:text-2xl">Naeem Foundation Privacy Policy</h2>
                <p class="text-sm leading-relaxed text-gray-700 sm:text-base">
                    Naeem Foundation is committed to protecting your privacy. This policy explains how we collect, use,
                    and safeguard any personal information you provide to us.
                </p>
            </div>

            {{-- Who we are --}}
            <div>
                <h2 class="mb-4 text-xl font-bold text-navy sm:text-2xl">Who we are</h2>
                <div class="space-y-2 text-sm leading-relaxed text-gray-700 sm:text-base">
                    <p>We may collect personal information from you in several ways, including:</p>
                    <p><span class="font-semibold">Contact details:</span> Your name, address, email, phone number, and social media handles.</p>
                    <p><span class="font-semibold">Donation information:</span> Details about your donations, including payment information.</p>
                    <p><span class="font-semibold">Event registration:</span> Information related to events you register for, such as dietary restrictions or emergency contact details.</p>
                    <p><span class="font-semibold">Job applications:</span> Information provided in job applications, including your resume, cover letter, and references. <span class="font-semibold">Website usage:</span> Your IP address, browsing history, and other information collected through cookies.</p>
                    <p><span class="font-semibold">Other information:</span> Any other information you voluntarily provide to us.</p>
                </div>
            </div>

            {{-- How do we use your information --}}
            <div>
                <h2 class="mb-4 text-xl font-bold text-navy sm:text-2xl">How do we use your information?</h2>
                <div class="text-sm leading-relaxed text-gray-700 sm:text-base">
                    <p>We use your information to:</p>
                    <ul class="mt-2 list-disc space-y-1 pl-6">
                        <li>Process your donations.</li>
                        <li>Provide you with requested services, like updates on our work.</li>
                        <li>Personalize your experience on our website.</li>
                        <li>Improve our services and projects.</li>
                        <li>Send you fundraising updates (if you consent).</li>
                    </ul>
                    <p class="mt-2">We will never sell or lease your data.</p>
                </div>
            </div>

            {{-- To exercise these rights --}}
            <div>
                <h2 class="mb-4 text-xl font-bold text-navy sm:text-2xl">To exercise these rights, please contact us:</h2>
                <div class="text-sm leading-relaxed text-gray-700 sm:text-base">
                    <p>Email: <a href="mailto:Contact@naeemfoundation.co.uk" class="font-medium text-navy underline hover:text-brand">Contact@naeemfoundation.co.uk</a></p>
                    <ul class="mt-2 list-disc space-y-1 pl-6">
                        <li><span class="font-semibold">Security:</span> We take all reasonable steps to secure your information and use industry-standard practices for data protection.</li>
                        <li><span class="font-semibold">Cookies:</span> Our website uses cookies to improve your experience. You can control cookies through your browser settings.</li>
                        <li><span class="font-semibold">Changes to this policy:</span> We may update this policy periodically. Please check back for any changes.</li>
                    </ul>
                </div>
            </div>

            {{-- Contact us --}}
            <div>
                <h2 class="mb-4 text-xl font-bold text-navy sm:text-2xl">Contact us</h2>
                <div class="text-sm leading-relaxed text-gray-700 sm:text-base">
                    <p>
                        If you have any questions about this policy, please contact us at
                        <a href="mailto:Contact@naeemfoundation.co.uk" class="font-medium text-navy underline hover:text-brand">Contact@naeemfoundation.co.uk</a>
                    </p>
                    <ul class="mt-2 list-disc space-y-1 pl-6">
                        <li>Naeem Foundation is a registered charity in the UK.</li>
                        <li>Donation Line: +44 20 7078 8118</li>
                        <li>Our registered address is: 2 Falcon Gate Shire Park Welwyn Garden City, AL7 1TW, United Kingdom.</li>
                        <li>Donation Line: +44 2070788118 or +44 7960185682.</li>
                    </ul>
                </div>
            </div>

            {{-- Bank details --}}
            <div>
                <h2 class="mb-4 text-xl font-bold text-navy sm:text-2xl">For donations, please use the following bank details:</h2>
                <ul class="list-disc space-y-1 pl-6 text-sm leading-relaxed text-gray-700 sm:text-base">
                    <li><span class="font-semibold">Bank:</span> Metro Bank</li>
                    <li><span class="font-semibold">Account name:</span> Naeem Foundation</li>
                    <li><span class="font-semibold">Sort code:</span> 230580</li>
                    <li><span class="font-semibold">Account number:</span> 46502817</li>
                    <li><span class="font-semibold">IBAN number:</span> GB80MYMB23058046502817</li>
                </ul>
            </div>

        </div>
    </section>

@endsection
