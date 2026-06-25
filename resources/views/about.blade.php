@extends('layouts.app')

@section('title', 'About Us — ' . config('app.name'))

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

    // Our Value pills — two columns. color: 'navy' | 'brand'
    $valuesLeft = [
        ['label' => 'Sustainable Development Goals', 'color' => 'navy'],
        ['label' => 'No Poverty', 'color' => 'brand'],
        ['label' => 'Zero Hunger', 'color' => 'navy'],
        ['label' => 'Good Health & Well-being', 'color' => 'brand'],
        ['label' => 'Quality Education', 'color' => 'navy'],
        ['label' => 'Gender Equality', 'color' => 'brand'],
        ['label' => 'Clean Water and Sanitation', 'color' => 'navy'],
        ['label' => 'Affordable and Sustainable Energy', 'color' => 'brand'],
        ['label' => 'Decent Work & Economic Growth', 'color' => 'navy'],
    ];
    $valuesRight = [
        ['label' => 'Industry, Innovation, and Infrastructure', 'color' => 'brand'],
        ['label' => 'Reduced Inequalities', 'color' => 'navy'],
        ['label' => 'Sustainable Cities & Communities', 'color' => 'brand'],
        ['label' => 'Responsible Consumption & Production', 'color' => 'navy'],
        ['label' => 'Climate Action', 'color' => 'navy'],
        ['label' => 'Life Below Water', 'color' => 'navy'],
        ['label' => 'Life on Land', 'color' => 'navy'],
        ['label' => 'Peace, Justice, and Strong Institutions', 'color' => 'navy'],
        ['label' => 'Partnerships for the Goals', 'color' => 'brand'],
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
        <div class="nf-container">
            <h2 class="mb-6 text-2xl font-bold text-navy">Our Value</h2>
            <div class="grid gap-x-8 gap-y-4 md:grid-cols-2">
                {{-- Left column --}}
                <div class="space-y-4">
                    @foreach ($valuesLeft as $value)
                        <div class="rounded-md px-5 py-3 text-sm font-semibold text-white underline underline-offset-2
                            {{ $value['color'] === 'brand' ? 'bg-brand' : 'bg-navy' }}">
                            {{ $value['label'] }}
                        </div>
                    @endforeach
                </div>
                {{-- Right column --}}
                <div class="space-y-4">
                    @foreach ($valuesRight as $value)
                        <div class="rounded-md px-5 py-3 text-sm font-semibold text-white underline underline-offset-2
                            {{ $value['color'] === 'brand' ? 'bg-brand' : 'bg-navy' }}">
                            {{ $value['label'] }}
                        </div>
                    @endforeach
                </div>
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
                    <div class="nf-reveal group" style="transition-delay: {{ $i * 120 }}ms">
                        <div class="overflow-hidden rounded-xl shadow-sm ring-1 ring-black/5 transition-all duration-300 group-hover:-translate-y-2 group-hover:shadow-xl">
                            {{-- Photo + name band --}}
                            <div class="relative overflow-hidden">
                                <img src="{{ asset($member['image']) }}" alt="{{ $member['name'] }}"
                                     class="h-64 w-full object-cover transition-transform duration-500 ease-out group-hover:scale-110">

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

@endsection
