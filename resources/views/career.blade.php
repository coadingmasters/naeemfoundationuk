@extends('layouts.app')

@section('title', 'Careers — ' . config('app.name'))

@php
    $features = [
        ['no' => '01', 'title' => 'Meaningful Impact', 'text' => 'At Naeem Foundation, your work directly contributes to improving the lives of individuals and communities in need.'],
        ['no' => '02', 'title' => 'Collaborative Environment', 'text' => 'Join a supportive team of passionate individuals who are dedicated to making a difference.'],
        ['no' => '03', 'title' => 'Professional Development', 'text' => "We're committed to helping our employees grow and succeed through training opportunities and career advancement paths."],
        ['no' => '04', 'title' => 'Diversity and Inclusion', 'text' => 'We believe in the strength of diversity and inclusion and are committed to creating a welcoming and inclusive workplace.'],
    ];

    $responsibilities = [
        ['Strategic Development', 'Craft and implement strategic plans that align with our foundation\'s mission and objectives, driving progress and innovation within the charitable sector.'],
        ['Project Management', 'Lead the design and execution of community-focused projects, ensuring all initiatives resonate with diverse demographics and achieve desired outcomes.'],
        ['Leadership & Team Management', 'Provide inspirational leadership to a diverse team, fostering a collaborative and inclusive work environment with clear goals and high morale.'],
        ['Stakeholder Engagement', 'Represent the foundation in various capacities, engaging with communities, partners and stakeholders to enhance our reach and impact.'],
        ['Resource Management', 'Collaborate with the finance team to strategically allocate resources, ensuring optimal use for project success and sustainability.'],
        ['Reporting & Compliance', 'Prepare detailed reports on activities and outcomes, and ensure all operations comply with applicable laws and ethical standards.'],
    ];

    $qualifications = [
        'Proven experience in nonprofit management or a related field.',
        'Strong leadership skills and the ability to manage and motivate teams.',
        'Excellent project management skills with a track record of delivered initiatives.',
        'Ability to engage with and inspire a diverse range of stakeholders.',
        'Strong communication and interpersonal skills.',
        'Knowledge of regulatory and compliance requirements within the charitable sector.',
    ];

    $benefits = [
        'Competitive salary and comprehensive benefits package.',
        'Opportunities for professional development and career growth.',
        'A chance to make a significant impact in the community and improve lives.',
    ];
@endphp

@section('content')

    {{-- ===================== HERO ===================== --}}
    <section class="relative overflow-hidden">
        <img src="{{ asset('images/about us hero banner.png') }}" alt="" class="absolute inset-0 h-full w-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-navy-dark/90 via-brand/80 to-brand/55"></div>

        <div class="nf-container relative py-16 text-center sm:py-20 lg:py-24">
            <div class="mx-auto max-w-2xl text-white nf-reveal">
                <span class="inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-1.5 text-xs font-semibold uppercase tracking-wider ring-1 ring-white/20">
                    <span class="h-1.5 w-1.5 rounded-full bg-white"></span>
                    Careers
                </span>
                <h1 class="mt-5 text-4xl font-extrabold leading-[1.05] sm:text-5xl lg:text-6xl">
                    This is What <span class="text-cream">We Do</span>
                </h1>
                <p class="mx-auto mt-4 max-w-xl text-base leading-relaxed text-white/85 sm:text-lg">
                    Join a team dedicated to making a difference in the lives of individuals and communities in need.
                </p>
                <a href="#apply" class="btn-white mt-7 px-7 py-3">
                    Apply As a Volunteer
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>

                <nav class="mt-6 flex items-center justify-center gap-2 text-sm text-white/70">
                    <a href="{{ route('home') }}" class="hover:text-white">Home</a>
                    <span>/</span>
                    <span class="text-white">Careers</span>
                </nav>
            </div>
        </div>
    </section>

    {{-- ===================== LASTING CHANGE BANNER ===================== --}}
    <section class="py-12 sm:py-14">
        <div class="nf-container">
            <div class="overflow-hidden rounded-2xl bg-cream">
                <div class="grid items-center gap-6 lg:grid-cols-2">
                    <div class="p-8 lg:p-12">
                        <p class="text-sm font-semibold text-brand">Empowering Hope</p>
                        <h2 class="mt-2 text-2xl font-bold leading-snug text-navy-dark sm:text-3xl">Our work creates lasting change</h2>
                        <p class="mt-4 max-w-lg text-sm leading-relaxed text-gray-600 sm:text-base">
                            At Naeem Foundation, we believe in the power of compassion and community development to address
                            pressing social challenges and create lasting change. If you're passionate about making a
                            positive impact, consider a career with us.
                        </p>
                        <a href="#apply" class="btn-brand mt-6">Join Us Now</a>
                    </div>
                    <div class="p-5 lg:py-6 lg:pr-6">
                        <img src="{{ asset('images/zakatcenter.png') }}" alt="Naeem Foundation in the community"
                             class="h-56 w-full rounded-xl object-cover sm:h-64 lg:h-72">
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== WHY WORK WITH US (sticky image, scrolling text) ===================== --}}
    <section class="py-12 sm:py-16">
        <div class="nf-container">
            <div class="text-center">
                <p class="text-sm font-semibold uppercase tracking-wider text-brand">Join Us</p>
                <h2 class="mt-2 text-3xl font-bold text-navy-dark sm:text-4xl">Why work with us?</h2>
            </div>

            <div class="mt-10 grid gap-10 lg:grid-cols-2 lg:gap-14">
                {{-- Left: sticky image --}}
                <div>
                    <div class="lg:sticky lg:top-36">
                        <img src="{{ asset('images/changinslives1.jpg') }}" alt="Our team at work"
                             class="h-72 w-full rounded-2xl object-cover shadow-md sm:h-96 lg:h-[460px]">
                    </div>
                </div>

                {{-- Right: scrolling feature list --}}
                <div class="space-y-8 lg:space-y-12">
                    @foreach ($features as $feature)
                        <div class="nf-reveal border-l-2 border-gray-100 pl-6">
                            <span class="block text-3xl font-extrabold leading-none text-brand/30">{{ $feature['no'] }}</span>
                            <h3 class="mt-3 text-xl font-bold text-navy-dark">{{ $feature['title'] }}</h3>
                            <p class="mt-2 text-sm leading-relaxed text-gray-600 sm:text-base">{{ $feature['text'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== OPEN ROLE ===================== --}}
    <section class="py-12 sm:py-14">
        <div class="nf-container">
            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm sm:p-8 lg:p-10">
                <p class="text-sm font-semibold text-brand">Be an integral part of the team</p>
                <h2 class="mt-1 max-w-2xl text-2xl font-bold leading-snug text-navy-dark sm:text-3xl">
                    Charitable Organization Manager at Naeem Foundation UK
                </h2>

                {{-- Meta chips --}}
                <div class="mt-5 flex flex-wrap gap-2">
                    @foreach (['Full Time', 'United Kingdom', '3 Positions', 'Joining: 15 May 2024'] as $chip)
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-cream px-3.5 py-1.5 text-xs font-semibold text-brand">
                            <span class="h-1.5 w-1.5 rounded-full bg-brand"></span>{{ $chip }}
                        </span>
                    @endforeach
                </div>

                <div class="mt-8 grid gap-8 lg:grid-cols-2">
                    <div>
                        <h3 class="text-base font-bold text-navy-dark">About Naeem Foundation UK</h3>
                        <p class="mt-2 text-sm leading-relaxed text-gray-600">
                            Naeem Foundation UK is a vibrant nonprofit organisation dedicated to enhancing community welfare
                            through innovative projects and initiatives. We are seeking an experienced Charitable
                            Organization Manager to join our dynamic team and lead our mission forward.
                        </p>

                        <h3 class="mt-6 text-base font-bold text-navy-dark">Key Responsibilities</h3>
                        <ul class="mt-3 space-y-3">
                            @foreach ($responsibilities as $item)
                                <li class="flex gap-2.5 text-sm leading-relaxed text-gray-600">
                                    <span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-brand"></span>
                                    <span><span class="font-semibold text-navy-dark">{{ $item[0] }}:</span> {{ $item[1] }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-base font-bold text-navy-dark">Preferred Qualifications</h3>
                        <ul class="mt-3 space-y-2.5">
                            @foreach ($qualifications as $q)
                                <li class="flex gap-2.5 text-sm leading-relaxed text-gray-600">
                                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-brand" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    {{ $q }}
                                </li>
                            @endforeach
                        </ul>

                        <h3 class="mt-6 text-base font-bold text-navy-dark">Benefits</h3>
                        <ul class="mt-3 space-y-2.5">
                            @foreach ($benefits as $b)
                                <li class="flex gap-2.5 text-sm leading-relaxed text-gray-600">
                                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-brand" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    {{ $b }}
                                </li>
                            @endforeach
                        </ul>

                        <div class="mt-6 rounded-xl bg-cream p-4">
                            <h3 class="text-sm font-bold text-navy-dark">How to Apply</h3>
                            <p class="mt-1.5 text-sm leading-relaxed text-gray-600">
                                Submit your resume and a cover letter outlining your suitability for the role to
                                <a href="mailto:contact@naeemfoundation.co.uk" class="font-semibold text-brand underline">contact@naeemfoundation.co.uk</a>,
                                or fill out the form below.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== APPLICATION FORM ===================== --}}
    <section id="apply" class="py-12 sm:py-14">
        <div class="nf-container">
            <div class="mx-auto max-w-3xl text-center">
                <p class="text-sm font-semibold uppercase tracking-wider text-brand">Be a part of the team</p>
                <h2 class="mt-2 text-3xl font-bold text-navy-dark sm:text-4xl">Fill Out The Form</h2>
                <p class="mx-auto mt-3 max-w-xl text-sm text-gray-500">Tell us a little about yourself and we'll get back to you.</p>
            </div>

            <form data-career-form class="mx-auto mt-8 max-w-3xl rounded-2xl border border-gray-100 bg-white p-6 shadow-sm sm:p-8">
                <div class="grid gap-4 sm:grid-cols-2">
                    <input type="text" name="name" required placeholder="Name"
                           class="h-12 w-full rounded-lg border border-gray-300 bg-white px-4 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30">
                    <input type="tel" name="phone" required placeholder="Phone Number"
                           class="h-12 w-full rounded-lg border border-gray-300 bg-white px-4 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30">
                    <input type="email" name="email" required placeholder="Email"
                           class="h-12 w-full rounded-lg border border-gray-300 bg-white px-4 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30">
                    <input type="text" name="postcode" placeholder="Postcode"
                           class="h-12 w-full rounded-lg border border-gray-300 bg-white px-4 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30">
                    <textarea name="address" rows="4" placeholder="Enter your address"
                              class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30 sm:col-span-2"></textarea>
                </div>

                <div data-career-success class="mt-5 hidden items-center gap-2 rounded-lg border border-green-200 bg-green-50 p-3 text-sm text-green-800">
                    <svg class="h-5 w-5 shrink-0 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Thank you! We've received your application and will be in touch soon.
                </div>

                <button type="submit" class="btn-brand mt-6 w-full py-3">Submit</button>
            </form>
        </div>
    </section>

    {{-- ===================== CTA ===================== --}}
    <section class="bg-navy">
        <div class="nf-container py-14 text-center">
            <p class="text-sm font-semibold uppercase tracking-wider text-[#e9b9c6]">Get involved</p>
            <h2 class="mx-auto mt-2 max-w-xl text-2xl font-bold leading-snug text-white sm:text-3xl">
                Join thousands of others across the country
            </h2>
            <p class="mx-auto mt-4 max-w-2xl text-sm leading-relaxed text-white/75 sm:text-base">
                Giving your time means helping to give those most vulnerable a chance at the future they deserve.
            </p>
            <a href="#" class="btn-brand mt-7 px-7 py-3">
                Make a Donation
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </a>
        </div>
    </section>

@endsection

@push('scripts')
<script>
    document.querySelector('[data-career-form]')?.addEventListener('submit', (e) => {
        e.preventDefault();
        const msg = e.target.querySelector('[data-career-success]');
        if (msg) { msg.classList.remove('hidden'); msg.classList.add('flex'); }
        e.target.querySelectorAll('input, textarea').forEach((f) => (f.value = ''));
        msg?.scrollIntoView({ behavior: 'smooth', block: 'center' });
    });
</script>
@endpush
