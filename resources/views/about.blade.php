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
        ['label' => 'Good Health & Well-being', 'color' => 'navy'],
        ['label' => 'Quality Education', 'color' => 'navy'],
        ['label' => 'Gender Equality', 'color' => 'navy'],
        ['label' => 'Clean Water and Sanitation', 'color' => 'navy'],
        ['label' => 'Affordable and Sustainable Energy', 'color' => 'brand'],
        ['label' => 'Decent Work & Economic Growth', 'color' => 'navy'],
    ];
    $valuesRight = [
        ['label' => 'Industry, Innovation, and Infrastructure', 'color' => 'brand'],
        ['label' => 'Reduced Inequalities', 'color' => 'navy'],
        ['label' => 'Sustainable Cities & Communities', 'color' => 'brand'],
        ['label' => 'Responsible Consumption & Production', 'color' => 'brand'],
        ['label' => 'Climate Action', 'color' => 'brand'],
        ['label' => 'Life Below Water', 'color' => 'navy'],
        ['label' => 'Life on Land', 'color' => 'brand'],
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
            <div class="grid items-center gap-8 py-10 lg:grid-cols-2 lg:gap-12 lg:py-0">
                {{-- Text --}}
                <div class="lg:py-16">
                    <h1 class="text-3xl font-extrabold leading-tight text-brand sm:text-4xl lg:text-5xl">
                        We are a nonprofit<br>Organization
                    </h1>
                    <p class="mt-4 max-w-md text-sm leading-relaxed text-gray-600">
                        Lighting up lives with compassionate aid and community empowerment, striving to tackle
                        pressing social issues for lasting change.
                    </p>
                </div>

                {{-- Image --}}
                <div class="lg:py-10">
                    <img src="{{ asset('images/about us hero banner.png') }}" alt="Naeem Foundation team"
                         class="h-64 w-full rounded-lg object-cover sm:h-80 lg:h-96">
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== TABS ===================== --}}
    <section class="pt-12" data-tabs>
        <div class="nf-container">
            <div class="mx-auto grid max-w-3xl grid-cols-1 gap-4 rounded-xl border border-gray-200 p-3 shadow-sm sm:grid-cols-3">
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
                        <div class="rounded-md border-2 px-5 py-3 text-sm font-semibold
                            {{ $value['color'] === 'brand' ? 'border-brand text-brand' : 'border-navy text-navy' }}">
                            {{ $value['label'] }}
                        </div>
                    @endforeach
                </div>
                {{-- Right column --}}
                <div class="space-y-4">
                    @foreach ($valuesRight as $value)
                        <div class="rounded-md border-2 px-5 py-3 text-sm font-semibold
                            {{ $value['color'] === 'brand' ? 'border-brand text-brand' : 'border-navy text-navy' }}">
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
                @foreach ($team as $member)
                    <div class="text-center">
                        <div class="relative overflow-hidden rounded-lg">
                            <img src="{{ asset($member['image']) }}" alt="{{ $member['name'] }}"
                                 class="h-72 w-full object-cover">
                            <div class="absolute inset-x-0 bottom-0 bg-brand py-2 text-center">
                                <span class="text-sm font-semibold text-white">{{ $member['name'] }}</span>
                            </div>
                        </div>
                        <p class="mt-3 text-sm font-semibold text-navy-dark">{{ $member['role'] }}</p>
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
            <div class="relative mt-12 pl-12 sm:pl-16">
                {{-- Vertical line --}}
                <span class="absolute left-[18px] top-2 bottom-2 w-px bg-brand/40 sm:left-[26px]" aria-hidden="true"></span>

                <div class="space-y-8">
                    @foreach ($organogram as $item)
                        <div class="relative">
                            {{-- Number circle --}}
                            <span class="absolute -left-12 top-4 grid h-9 w-9 place-items-center rounded-full bg-brand text-sm font-bold text-white ring-4 ring-white sm:-left-16 sm:h-12 sm:w-12 sm:text-base">
                                {{ $item['no'] }}
                            </span>

                            {{-- Card --}}
                            <div class="rounded-xl bg-navy p-6 text-white sm:p-8">
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
