@extends('layouts.app')

@section('title', 'Careers — ' . config('app.name'))

@php
    // "Our work creates lasting changes" feature cards
    $features = [
        ['no' => '01', 'title' => 'Meaningful Impact', 'text' => 'At Naeem Foundation, your work directly contributes to improving the lives of individuals and communities in need.'],
        ['no' => '02', 'title' => 'Collaborative Environment', 'text' => 'Join a supportive team of passionate individuals who are dedicated to making a difference.'],
        ['no' => '03', 'title' => 'Professional Development', 'text' => 'We’re committed to helping our employees grow and succeed through training opportunities and career advancement paths.'],
        ['no' => '04', 'title' => 'Diversity and Inclusion', 'text' => 'We believe in the strength of diversity and inclusion and are committed to creating a welcoming and inclusive workplace.'],
    ];

    // Job responsibilities (bold lead-in + description)
    $responsibilities = [
        ['Strategic Development', 'Craft and implement strategic plans that align with our foundation’s mission and objectives, driving progress and innovation within the charitable sector.'],
        ['Project Management', 'Lead the design and execution of community-focused projects. Ensure that all initiatives effectively resonate with diverse demographics and achieve desired outcomes.'],
        ['Leadership and Team Management', 'Provide inspirational leadership to a diverse team, fostering a collaborative and inclusive work environment. Set clear goals and maintain high team morale to ensure alignment with the foundation’s objectives.'],
        ['Stakeholder Engagement', 'Represent the foundation in various capacities, engaging with communities, partners, and stakeholders to enhance organizational reach and impact.'],
        ['Resource Management', 'Collaborate with the finance team to strategically allocate resources, ensuring optimal use for project success and sustainability.'],
        ['Reporting and Compliance', 'Prepare detailed reports on activities, achievements, and challenges. Maintain rigorous documentation and ensure all operations comply with applicable laws and ethical standards.'],
    ];

    $qualifications = [
        'Proven experience in nonprofit management or a related field.',
        'Strong leadership skills and the ability to manage and motivate teams.',
        'Excellent project management skills, with a track record of successfully delivered initiatives.',
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
    <section class="bg-cream">
        <div class="nf-container">
            <div class="grid items-center gap-10 py-12 lg:grid-cols-2 lg:gap-14 lg:py-16">
                {{-- Text --}}
                <div>
                    <h1 class="text-4xl font-extrabold leading-[1.1] text-navy sm:text-5xl">
                        This is What We Do
                    </h1>
                    <p class="mt-5 max-w-md text-sm leading-relaxed text-gray-600 sm:text-base">
                        Join a team dedicated to making a difference in the lives of individuals and communities in need.
                    </p>
                    <a href="#open-role" class="btn-navy mt-6">Apply As a Volunteer</a>
                </div>

                {{-- Image --}}
                <div>
                    <img src="{{ asset('images/about us hero banner.png') }}" alt="Naeem Foundation team"
                         class="h-64 w-full rounded-xl object-cover shadow-md sm:h-80 lg:h-[360px]">
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== NAVY BANNER CARD ===================== --}}
    <section class="py-12 sm:py-14">
        <div class="nf-container">
            <div class="overflow-hidden rounded-2xl bg-navy">
                <div class="grid items-center gap-6 lg:grid-cols-2">
                    {{-- Text --}}
                    <div class="p-8 lg:p-10">
                        <p class="text-sm font-semibold text-amber-400">Empowering Hope</p>
                        <h2 class="mt-2 text-2xl font-bold leading-snug text-white sm:text-3xl">
                            Our work creates lasting<br class="hidden sm:block"> changes
                        </h2>
                        <p class="mt-4 max-w-lg text-sm leading-relaxed text-white/80">
                            At Naeem Foundation, we believe in the power of compassion and community development to address
                            pressing social challenges and create lasting change. If you’re passionate about making a
                            positive impact, consider a career with us.
                        </p>
                        <a href="#open-role" class="btn-brand mt-6">Join Us Now</a>
                    </div>

                    {{-- Image --}}
                    <div class="p-5 lg:py-6 lg:pr-6">
                        <img src="{{ asset('images/zakatcenter.png') }}" alt="Naeem Foundation in the community"
                             class="h-52 w-full rounded-xl object-cover sm:h-60 lg:h-64">
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== FEATURE CARDS ===================== --}}
    <section class="pb-4">
        <div class="nf-container">
            <p class="text-sm font-semibold text-brand">Empowering Hope</p>
            <h2 class="mt-1 text-2xl font-bold leading-snug text-navy sm:text-3xl">Our work creates lasting changes</h2>

            <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($features as $i => $feature)
                    <div class="nf-reveal rounded-lg bg-cream p-6" style="transition-delay: {{ $i * 100 }}ms">
                        <span class="block text-4xl font-extrabold leading-none text-slate-300">{{ $feature['no'] }}</span>
                        <h3 class="mt-3 font-bold leading-snug text-navy">{{ $feature['title'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-gray-600">{{ $feature['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===================== OPEN ROLE ===================== --}}
    <section id="open-role" class="py-14">
        <div class="nf-container">
            <p class="text-sm font-semibold text-brand">Be an integral part of the team</p>
            <h2 class="mt-1 max-w-2xl text-2xl font-bold leading-snug text-navy sm:text-3xl">
                Charitable Organization Manager at Naeem Foundation UK
            </h2>

            <div class="mt-6 space-y-4 text-sm leading-relaxed text-gray-700 sm:text-[15px]">
                {{-- About --}}
                <div>
                    <p class="font-semibold text-navy-dark">About Naeem Foundation UK:</p>
                    <p>
                        Naeem Foundation UK is a vibrant nonprofit organization dedicated to enhancing community welfare
                        through innovative projects and initiatives. We aim to make a lasting impact by addressing key
                        societal issues and fostering a culture of inclusion and mutual respect. We are currently seeking an
                        experienced Charitable Organization Manager to join our dynamic team and lead our mission forward.
                    </p>
                </div>

                {{-- Responsibilities --}}
                <div>
                    <p class="font-semibold text-navy-dark">Key Responsibilities:</p>
                    @foreach ($responsibilities as $item)
                        <p><span class="font-semibold">{{ $item[0] }}:</span> {{ $item[1] }}</p>
                    @endforeach
                </div>

                {{-- Qualifications --}}
                <div>
                    <p class="font-semibold text-navy-dark">Preferred Qualifications:</p>
                    @foreach ($qualifications as $q)
                        <p>{{ $q }}</p>
                    @endforeach
                </div>

                {{-- Benefits --}}
                <div>
                    <p class="font-semibold text-navy-dark">Benefits:</p>
                    @foreach ($benefits as $b)
                        <p>{{ $b }}</p>
                    @endforeach
                </div>

                {{-- How to apply --}}
                <div>
                    <p class="font-semibold text-navy-dark">How to Apply:</p>
                    <p>
                        Please submit your resume and a cover letter outlining your suitability for the role and your passion
                        for nonprofit work to:
                        <a href="mailto:contact@naeemfoundation.co.uk" class="font-medium text-navy underline hover:text-brand">contact@naeemfoundation.co.uk</a>
                        . We are excited to hear how you can contribute to our team and mission.
                    </p>
                </div>

                {{-- Summary --}}
                <div>
                    <p>Joining Date: 15-May-24</p>
                    <p>Join Naeem Foundation UK in making a difference. We look forward to welcoming a driven and passionate leader to our team.</p>
                    <p>Positions: 3 Positions</p>
                    <p>Job Type: Full Time</p>
                    <p>Job Location: United Kingdom</p>
                </div>
            </div>
        </div>
    </section>

@endsection
