@extends('layouts.app')

@section('title', 'About Us — ' . config('app.name'))

{{-- Light hero → keep the header solid. --}}
@section('header-solid', 'yes')

@php
    // Tabs — each has its own content shown on click
    $tabs = [
        [
            'key' => 'about',
            'label' => 'About & History',
            'text' => 'Naeem Foundation is a dedicated charity committed to empowering individuals and communities. With a strong focus on addressing pressing social challenges, we strive to create a positive and lasting impact. Our mission is to uplift those in need, nurturing a society built on compassion and equality.',
        ],
        [
            'key' => 'vision',
            'label' => 'Vision',
            'text' => 'Our vision is a world where every individual has access to the essentials of a dignified life — free from poverty, hunger and injustice. We envision thriving, self-reliant communities empowered to shape their own future, united by compassion, equality and hope for generations to come.',
        ],
        [
            'key' => 'mission',
            'label' => 'Mission',
            'text' => 'Our mission is to uplift those in need through compassionate aid, sustainable development and community empowerment. By providing education, healthcare, clean water and emergency relief, we work to break the cycle of poverty and build a society founded on dignity, justice and shared responsibility.',
        ],
    ];

    // Our Value pills — two columns, now expandable. color: 'navy' | 'brand'
    $valuesLeft = [
        ['label' => 'Sustainable Development Goals', 'color' => 'navy', 'answer' => 'We align every programme with the United Nations’ 17 Sustainable Development Goals, using them as a shared framework to plan our work, measure our impact and report transparently to the communities and donors we serve.'],
        ['label' => 'No Poverty', 'color' => 'brand', 'answer' => 'Through emergency relief, livelihood grants and long-term development projects, we help families break the cycle of poverty and build a stable, dignified future for themselves and their children.'],
        ['label' => 'Zero Hunger', 'color' => 'navy', 'answer' => 'Our food distributions, Ramadan food packs and nutrition programmes ensure that vulnerable families and children have access to regular, nourishing meals — especially in times of crisis.'],
        ['label' => 'Good Health & Well-being', 'color' => 'brand', 'answer' => 'We fund medical camps, essential treatment and clean-water initiatives so that underserved communities can access the healthcare they need to live healthier lives.'],
        ['label' => 'Quality Education', 'color' => 'navy', 'answer' => 'We support schools, scholarships and learning resources so that children — particularly orphans and the underprivileged — can access the education that transforms their futures.'],
        ['label' => 'Gender Equality', 'color' => 'brand', 'answer' => 'We invest in women through skills training, safe spaces and income opportunities, empowering them to lead, earn and support their households with dignity.'],
        ['label' => 'Clean Water and Sanitation', 'color' => 'navy', 'answer' => 'Our water wells and hand pumps bring safe, clean drinking water to communities who would otherwise walk miles each day for it, reducing disease and hardship.'],
        ['label' => 'Affordable and Sustainable Energy', 'color' => 'brand', 'answer' => 'Wherever possible we introduce solar and energy-efficient solutions, giving communities reliable power without adding to the strain on our environment.'],
        ['label' => 'Decent Work & Economic Growth', 'color' => 'navy', 'answer' => 'Our vocational and livelihood programmes equip people with the skills and tools they need to earn a fair, sustainable income and stand on their own feet.'],
    ];
    $valuesRight = [
        ['label' => 'Industry, Innovation, and Infrastructure', 'color' => 'brand', 'answer' => 'We help rebuild essential infrastructure and adopt practical innovations that make our aid faster, fairer and more effective for the people who rely on it.'],
        ['label' => 'Reduced Inequalities', 'color' => 'navy', 'answer' => 'We reach the most marginalised — regardless of background, gender or location — ensuring that our aid is distributed on the basis of need alone.'],
        ['label' => 'Sustainable Cities & Communities', 'color' => 'brand', 'answer' => 'We strengthen communities with resilient, locally-led projects designed to keep serving people long after our teams have moved on.'],
        ['label' => 'Responsible Consumption & Production', 'color' => 'navy', 'answer' => 'We manage every donation responsibly — minimising waste and maximising the impact of every pound entrusted to us.'],
        ['label' => 'Climate Action', 'color' => 'navy', 'answer' => 'We build climate resilience into our projects, helping vulnerable communities prepare for, withstand and recover from environmental shocks.'],
        ['label' => 'Life Below Water', 'color' => 'navy', 'answer' => 'We promote responsible practices that protect water sources and the fishing livelihoods that so many families depend upon.'],
        ['label' => 'Life on Land', 'color' => 'navy', 'answer' => 'We support sustainable land use and greening initiatives that protect the natural environment the communities we serve rely on.'],
        ['label' => 'Peace, Justice, and Strong Institutions', 'color' => 'navy', 'answer' => 'We operate with full transparency and accountability, working only with trusted partners so that aid reaches people safely, fairly and without exploitation.'],
        ['label' => 'Partnerships for the Goals', 'color' => 'brand', 'answer' => 'We believe lasting change is only possible together, so we collaborate closely with donors, local partners and institutions to multiply our impact.'],
    ];

    // What We Do — our live programme areas. Each links to its real page, so
    // this section doubles as a route into the giving pages (see config/giving.php).
    $programmes = [
        [
            'route' => 'education-sponsorships',
            'title' => 'Education Sponsorships',
            'text' => 'Scholarships, school resources and learning support so children can stay in education.',
            'icon' => 'M12 5.5 2.5 10 12 14.5 21.5 10 12 5.5Z M6 11.6V16c0 1.4 2.7 2.6 6 2.6s6-1.2 6-2.6v-4.4',
        ],
        [
            'route' => 'food-sustenance',
            'title' => 'Food & Sustenance',
            'text' => 'Regular food distributions and nutrition support for families facing hunger.',
            'icon' => 'M7 3v6a2 2 0 0 0 4 0V3 M9 9v12 M17.5 3c-1.3 1.6-2 3.5-2 5.4V13h4V8.4c0-1.9-.7-3.8-2-5.4Z M17.5 13v8',
        ],
        [
            'route' => 'clean-water',
            'title' => 'Clean Water',
            'text' => 'Wells and hand pumps bringing safe drinking water within reach of whole villages.',
            'icon' => 'M12 3s6 6.4 6 10.5a6 6 0 0 1-12 0C6 9.4 12 3 12 3Z',
        ],
        [
            'route' => 'healthcare',
            'title' => 'Healthcare',
            'text' => 'Medical camps and essential treatment for communities with no access to care.',
            'icon' => 'M4 8.5h16a1 1 0 0 1 1 1V19a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V9.5a1 1 0 0 1 1-1Z M9 8.5V6a1.5 1.5 0 0 1 1.5-1.5h3A1.5 1.5 0 0 1 15 6v2.5 M12 11.5v5 M9.5 14h5',
        ],
        [
            'route' => 'orphans-sponsorships',
            'title' => 'Orphans Sponsorships',
            'text' => 'Monthly sponsorship covering an orphaned child’s food, schooling and healthcare.',
            'icon' => 'M20.8 5.6a5 5 0 0 0-7.1 0L12 7.3l-1.7-1.7a5 5 0 1 0-7.1 7.1l1.7 1.7L12 21.5l7.1-7.1 1.7-1.7a5 5 0 0 0 0-7.1Z',
        ],
        [
            'route' => 'hostel-for-students-orphans',
            'title' => 'Hostel for Students',
            'text' => 'Safe, supervised accommodation so students and orphans can study without worry.',
            'icon' => 'M4 10.5 12 4l8 6.5V20a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-9.5Z M9.5 21v-6h5v6',
        ],
        [
            'route' => 'prosthetic-limb',
            'title' => 'Prosthetic Limb',
            'text' => 'Fitted limbs and rehabilitation that restore independence, mobility and dignity.',
            'icon' => 'M12 4.6a1.6 1.6 0 1 0 0-3.2 1.6 1.6 0 0 0 0 3.2Z M12 6.4v6.6 M8.2 8.4 12 9.4l3.8-1 M9.3 21.5 12 13l2.7 8.5',
        ],
        [
            'route' => 'community-centre',
            'title' => 'Community Centre',
            'text' => 'Shared spaces for learning, worship and support at the heart of the community.',
            'icon' => 'M16.5 20v-1.6a3.2 3.2 0 0 0-3.2-3.2H6.7a3.2 3.2 0 0 0-3.2 3.2V20 M10 12a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Z M20.5 20v-1.6a3.2 3.2 0 0 0-2.4-3.1 M15.5 5.2a3.2 3.2 0 0 1 0 6.2',
        ],
    ];

    // Team
    $team = [
        ['image' => 'images/burhan ahmad.png', 'name' => 'Burhan Ahmed', 'role' => 'Chairman'],
        ['image' => 'images/farhan naeem.png', 'name' => 'Farhan Naeem', 'role' => 'General Secretary'],
        ['image' => 'images/furqan ahmad.png', 'name' => 'Furqan Ahmed', 'role' => 'Treasurer'],
        ['image' => 'images/sheikhamir.png', 'name' => 'Shaikh Amir', 'role' => 'Trustee'],
    ];

    // Organogram timeline
    $organogram = [
        [
            'no' => '1',
            'title' => 'Structure And Departments',
            'text' => 'Learn about the Naeem Foundation’s organizational structure and numerous teams that are working together to accomplish our common objectives. Our organizational structure is designed to facilitate collaboration among different departments, ensuring seamless coordination and efficient operations. Each department plays a vital role in advancing our mission and serving our community with excellence. Through teamwork and shared commitment, we strive to maximize our impact and achieve sustainable outcomes. Explore our diverse departments and discover how each contributes to our collective success.',
        ],
        [
            'no' => '2',
            'title' => 'Financial Accountability',
            'text' => 'Discover our dedication to financial openness, which makes sure that your contributions are being used in a sensible way. Examine our methods for ensuring accountability in financial reporting. We adhere to rigorous fiscal management practices and undergo regular audits to maintain transparency and integrity in our financial operations. Our commitment to accountability extends to every aspect of our financial stewardship, from budgeting to expenditure tracking. By providing detailed financial reports and engaging in open communication with our donors, we strive to build trust and confidence in how we manage and allocate funds.',
        ],
        [
            'no' => '3',
            'title' => 'Responsible Resource Utilization',
            'text' => 'Learn about our strategies by which we manage the resources entrusted to us honestly, ensuring they are used properly and efficiently to maximize our impact. Our organization prioritizes transparency and accountability in all aspects of resource management, from fundraising to program implementation. We carefully monitor expenditure and regularly assess the effectiveness of our initiatives to ensure that resources are allocated where they are most needed. Through responsible stewardship, we strive to optimize the value of every contribution, making a meaningful difference in the lives of those we serve. Explore our commitment to responsible resource utilization and discover how you can support our mission.',
        ],
        [
            'no' => '4',
            'title' => 'Monitoring and Evaluation',
            'text' => 'Learn more about our thorough systems for monitoring and evaluating our programs’ success and impact. Discover how we evaluate and enhance our activities regularly. Our organization employees robust monitoring and evaluation frameworks to assess the effectiveness of our programs and interventions. Through data-driven analysis and stakeholder feedback, we continuously refine our strategies to ensure maximum impact and relevance. Explore our monitoring and evaluation processes to see how we strive for continuous improvement and accountability in achieving our goals.',
        ],
    ];
@endphp

@section('content')

    {{-- ===================== HERO ===================== --}}
    <section class="bg-cream">
        <div class="nf-container">
            <div class="grid items-center gap-10 py-12 lg:grid-cols-2 lg:gap-16 lg:py-20">
                {{-- Text --}}
                <div>
                    <h1 class="text-4xl font-extrabold leading-[1.1] text-brand sm:text-5xl lg:text-6xl">
                        We are a nonprofit<br>Organization
                    </h1>
                    <p class="mt-6 max-w-md text-base leading-relaxed text-gray-600">
                        Lighting up lives with compassionate aid and community empowerment, striving to tackle
                        pressing social issues for lasting change.
                    </p>
                </div>

                {{-- Image --}}
                <div>
                    <img src="{{ asset('images/about us hero banner.png') }}" alt="Naeem Foundation team"
                         class="h-72 w-full rounded-xl object-cover shadow-md sm:h-96 lg:h-[440px]">
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== TABS ===================== --}}
    <section class="pt-12" data-tabs>
        <div class="nf-container">
            <div class="mx-auto grid max-w-3xl grid-cols-1 gap-3 rounded-xl bg-navy p-2 shadow-sm sm:grid-cols-3">
                @foreach ($tabs as $i => $tab)
                    <button type="button" data-tab-btn="{{ $tab['key'] }}"
                            class="nf-tab rounded-lg px-6 py-3 text-center text-sm font-semibold transition-colors {{ $i === 0 ? 'is-active' : '' }}">
                        {{ $tab['label'] }}
                    </button>
                @endforeach
            </div>

            {{-- Panels (one per tab) --}}
            <div class="pt-8">
                @foreach ($tabs as $i => $tab)
                    <p data-tab-panel="{{ $tab['key'] }}"
                       class="max-w-4xl text-sm leading-relaxed text-gray-600 {{ $i === 0 ? '' : 'hidden' }}">
                        {{ $tab['text'] }}
                    </p>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===================== OUR VALUE ===================== --}}
    <section class="pt-12">
        <div class="nf-container" data-faq>
            <h2 class="mb-6 text-2xl font-bold text-navy">Our Value</h2>
            <div class="grid gap-x-8 gap-y-4 md:grid-cols-2">
                {{-- Left column --}}
                <div class="space-y-4">
                    @foreach ($valuesLeft as $value)
                        <div data-faq-item>
                            {{-- Colour strictly alternates per row (navy, brand, navy…). --}}
                            <button type="button" data-faq-toggle aria-expanded="false"
                                    class="flex w-full items-center justify-between gap-3 rounded-md px-5 py-3 text-left text-sm font-semibold text-white
                                    {{ $loop->index % 2 === 0 ? 'bg-navy' : 'bg-brand' }}">
                                <span class="underline underline-offset-2">{{ $value['label'] }}</span>
                                <svg class="nf-vfaq__icon h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </button>
                            <div class="nf-vfaq__panel">
                                <div>
                                    <p class="mt-2 rounded-md bg-cream px-5 py-3.5 text-sm leading-relaxed text-gray-700">{{ $value['answer'] }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                {{-- Right column --}}
                <div class="space-y-4">
                    @foreach ($valuesRight as $value)
                        <div data-faq-item>
                            {{-- Offset from the left column so the two form a red/blue checkerboard. --}}
                            <button type="button" data-faq-toggle aria-expanded="false"
                                    class="flex w-full items-center justify-between gap-3 rounded-md px-5 py-3 text-left text-sm font-semibold text-white
                                    {{ $loop->index % 2 === 0 ? 'bg-brand' : 'bg-navy' }}">
                                <span class="underline underline-offset-2">{{ $value['label'] }}</span>
                                <svg class="nf-vfaq__icon h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </button>
                            <div class="nf-vfaq__panel">
                                <div>
                                    <p class="mt-2 rounded-md bg-cream px-5 py-3.5 text-sm leading-relaxed text-gray-700">{{ $value['answer'] }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== WHAT WE DO ===================== --}}
    <section class="mt-14 bg-cream py-16">
        <div class="nf-container">
            <div class="nf-reveal max-w-3xl">
                <p class="text-sm font-semibold uppercase tracking-wider text-brand">What we do</p>
                <h2 class="mt-2 text-3xl font-bold text-navy-dark sm:text-4xl">
                    Our work, from relief today to change that lasts
                </h2>
                <p class="mt-4 text-sm leading-relaxed text-gray-600 sm:text-base">
                    Our values only mean something once they reach a family. These are the programmes
                    they turn into — each one run with local partners, monitored on the ground and
                    reported back to the donors who make it possible.
                </p>
            </div>

            <div class="mt-10 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($programmes as $i => $programme)
                    <a href="{{ route($programme['route']) }}"
                       class="nf-reveal group flex h-full flex-col rounded-xl bg-white p-6 shadow-sm ring-1 ring-black/5 transition-all duration-300 hover:-translate-y-2 hover:shadow-xl hover:ring-brand/20"
                       data-reveal-delay="{{ $i * 90 }}">
                        <span class="grid h-12 w-12 shrink-0 place-items-center rounded-lg bg-brand/10 text-brand transition-colors duration-300 group-hover:bg-brand group-hover:text-white">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="{{ $programme['icon'] }}"/>
                            </svg>
                        </span>

                        <h3 class="mt-5 text-base font-bold text-navy-dark transition-colors duration-300 group-hover:text-brand">
                            {{ $programme['title'] }}
                        </h3>
                        <p class="mt-2 flex-1 text-sm leading-relaxed text-gray-600">{{ $programme['text'] }}</p>

                        <span class="mt-4 inline-flex items-center gap-1.5 text-sm font-semibold text-brand">
                            Learn more
                            <svg class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===================== TEAM ===================== --}}
    <section class="pt-14">
        <div class="nf-container">
            <h2 class="text-2xl font-bold text-brand">The Talented Team Behind Our Services</h2>
            <p class="mt-4 max-w-5xl text-sm leading-relaxed text-gray-600">
                Our board of directors is made up of passionate individuals with plenty of skilled expertise and a
                unified dedication to our purpose. They lead the foundation’s affairs and provide visionary guidance.
            </p>
            <p class="mt-4 max-w-5xl text-sm leading-relaxed text-gray-600">
                With over a decade of experience, our directors bring invaluable insights and strategic direction to our
                organization. Their unwavering commitment to our mission drives our initiatives forward and ensures
                impactful results. As resolute stewards of our charity’s resources, they uphold the highest standards of
                governance and accountability. Together, they inspire and empower our team to make a meaningful
                difference in the lives of those we serve.
            </p>

            <div class="mt-10 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($team as $i => $member)
                    <div class="nf-reveal group" data-reveal-delay="{{ $i * 120 }}">
                        <div class="overflow-hidden rounded-xl shadow-sm ring-1 ring-black/5 transition-all duration-300 group-hover:-translate-y-2 group-hover:shadow-xl">
                            {{-- Photo + name band. Portrait box matches the photo ratio so the
                                 whole face shows (object-top keeps heads from being cut). --}}
                            <div class="relative aspect-[5/7] overflow-hidden bg-cream">
                                <img src="{{ asset($member['image']) }}" alt="{{ $member['name'] }}"
                                     class="h-full w-full object-cover object-top transition-transform duration-500 ease-out group-hover:scale-110">

                                {{-- Darkening overlay on hover --}}
                                <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-navy-dark/50 via-transparent to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>

                                {{-- Navy name band overlaying the photo --}}
                                <div class="absolute inset-x-0 bottom-0 bg-navy/90 py-2 text-center backdrop-blur-sm">
                                    <span class="text-sm font-semibold tracking-wide text-white">{{ $member['name'] }}</span>
                                </div>
                            </div>

                            {{-- Maroon role bar --}}
                            <div class="bg-brand py-2 text-center transition-colors duration-300 group-hover:bg-brand-dark">
                                <span class="text-xs font-semibold uppercase tracking-wide text-white">{{ $member['role'] }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===================== ORGANOGRAM ===================== --}}
    <section class="py-16">
        <div class="nf-container">
            <p class="text-sm font-semibold uppercase tracking-wide text-amber-600">Organization Journey</p>
            <h2 class="mt-1 text-3xl font-bold text-navy-dark">Organogram</h2>
            <p class="mt-4 max-w-5xl text-sm leading-relaxed text-gray-600">
                With the goal of ensuring efficient operations, effective governance, and distinct lines of authority and
                responsibility, our non-profit has designed a clearly defined organogram. This Organizational Structure
                serves as a blueprint for our operations, outlining the Hierarchical Chart and Staff Hierarchy within our
                organization.
            </p>

            {{-- Timeline --}}
            <div class="relative mt-12">
                {{-- Vertical line running through the circle centers --}}
                <span class="absolute left-6 top-6 bottom-6 w-0.5 -translate-x-1/2 bg-brand" aria-hidden="true"></span>

                <div class="space-y-6 sm:space-y-8">
                    @foreach ($organogram as $item)
                        <div class="relative flex items-center">
                            {{-- Number circle (vertically centered) + horizontal connector --}}
                            <div class="z-10 flex shrink-0 items-center">
                                <span class="grid h-12 w-12 place-items-center rounded-full bg-brand text-base font-bold text-white">
                                    {{ $item['no'] }}
                                </span>
                                <span class="h-0.5 w-4 bg-brand sm:w-8" aria-hidden="true"></span>
                            </div>

                            {{-- Card --}}
                            <div class="flex-1 rounded-xl bg-navy p-5 text-white sm:p-7">
                                <h3 class="text-lg font-bold sm:text-xl">{{ $item['title'] }}</h3>
                                <p class="mt-3 text-sm leading-relaxed text-white/80">{{ $item['text'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== GET INVOLVED CTA ===================== --}}
    {{-- Navy band: the footer below is bg-brand, so navy keeps the two distinct. --}}
    <section class="relative overflow-hidden bg-navy">
        {{-- Soft decorative glows, purely visual. --}}
        <span class="pointer-events-none absolute -left-24 -top-24 h-72 w-72 rounded-full bg-brand/25 blur-3xl" aria-hidden="true"></span>
        <span class="pointer-events-none absolute -bottom-28 -right-20 h-80 w-80 rounded-full bg-brand/20 blur-3xl" aria-hidden="true"></span>

        <div class="nf-container relative py-16 text-center sm:py-20">
            <div class="nf-reveal">
                <p class="text-sm font-semibold uppercase tracking-wider text-[#e9b9c6]">Get involved</p>
                <h2 class="mx-auto mt-2 max-w-xl text-2xl font-bold leading-snug text-white sm:text-3xl">
                    Join thousands of others across the country
                </h2>
                <p class="mx-auto mt-4 max-w-2xl text-sm leading-relaxed text-white/75 sm:text-base">
                    Giving your time means helping to give those most vulnerable a chance at the future they deserve.
                </p>

                <div class="mt-8 flex flex-col items-center justify-center gap-3 sm:flex-row">
                    <a href="{{ route('donate.checkout') }}" class="btn-brand group w-full px-7 py-3 sm:w-auto">
                        Make a Donation
                        <svg class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                    <a href="{{ route('volunteer') }}" class="btn-white group w-full px-7 py-3 sm:w-auto">
                        Become a Volunteer
                        <svg class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

@push('scripts')
<script>
    // Our Values FAQ — animated open/close (each item toggles independently).
    document.querySelectorAll('[data-faq]').forEach((faq) => {
        faq.querySelectorAll('[data-faq-toggle]').forEach((btn) => {
            btn.addEventListener('click', () => {
                const item = btn.closest('[data-faq-item]');
                const open = item.classList.toggle('is-open');
                btn.setAttribute('aria-expanded', String(open));
            });
        });
    });
</script>
@endpush

@endsection
