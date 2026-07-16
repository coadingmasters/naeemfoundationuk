<?php

namespace Database\Seeders;

use App\Models\AnnualReport;
use App\Models\Appeal;
use App\Models\Cause;
use App\Models\HeroSlide;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * Written to be idempotent and non-destructive: it is safe to run on
     * every deploy. It will NOT overwrite an existing admin's password and
     * will NOT duplicate or re-add hero slides once any exist.
     */
    public function run(): void
    {
        // Default administrator. firstOrCreate => never resets an existing
        // admin's password if you change it later.
        User::firstOrCreate(
            ['email' => 'admin@naeemfoundation.org'],
            [
                'name' => 'Naeem Foundation Admin',
                'password' => Hash::make('password'),
                'is_admin' => true,
            ],
        );

        // Sample hero slides — only seeded when the table is completely empty,
        // so your own slides are never touched.
        if (HeroSlide::count() === 0) {
            $slides = [
                [
                    'title' => "YOUR ZAKAT\nCAN SAVE THEM",
                    'subtitle' => 'Give with purpose this season',
                    'button_text' => 'Donate Now',
                ],
                [
                    'title' => "GIVE HOPE\nTHIS RAMADAN",
                    'subtitle' => 'Your generosity changes lives',
                    'button_text' => 'Donate Now',
                ],
                [
                    'title' => "TOGETHER WE\nCHANGE LIVES",
                    'subtitle' => 'Join thousands of supporters',
                    'button_text' => 'Get Involved',
                ],
            ];

            foreach ($slides as $i => $slide) {
                HeroSlide::create(array_merge($slide, [
                    'image' => 'images/homepagehero.png',
                    'button_url' => '#',
                    'sort_order' => $i,
                    'is_active' => true,
                ]));
            }
        }

        // Sample "Latest Appeals" cards — only when the table is empty.
        if (Appeal::count() === 0) {
            $appeals = [
                ['title' => 'Education', 'description' => 'Sadaqah: The Power of Giving. Have you ever felt the true joy of helping a child learn?', 'image' => 'images/changinslives1.jpg'],
                ['title' => 'Food & Sustenance', 'description' => 'Food Support. Our mission to provide for people in need. Donate today and feed a family.', 'image' => 'images/changinslives2.jpg'],
                ['title' => 'Binoria Water', 'description' => 'Water Crisis Hit Jamia Binoria Hard. Students struggle even for a single drop of clean water.', 'image' => 'images/changinslives3.jpg'],
                ['title' => 'Healthcare', 'description' => 'In rural areas, access to healthcare is often limited. At Naeem Foundation we bring care closer.', 'image' => 'images/changinslives4.jpg'],
                ['title' => 'Ramadan Food Packs', 'description' => 'Provide a month of meals for a family in need this Ramadan and earn endless rewards.', 'image' => 'images/changinslives2.jpg'],
                ['title' => 'Medical Camps', 'description' => 'Free check-ups and medicine for remote communities. Support a medical camp today.', 'image' => 'images/changinslives4.jpg'],
                ['title' => 'Orphan Sponsorship', 'description' => 'Give an orphan shelter, food and education every single month and change a life.', 'image' => 'images/changinslives1.jpg'],
                ['title' => 'Clean Water Wells', 'description' => 'Fund a well and bring clean water to an entire village for years to come.', 'image' => 'images/changinslives3.jpg'],
            ];

            foreach ($appeals as $i => $appeal) {
                Appeal::create(array_merge($appeal, [
                    'link' => '#',
                    'sort_order' => $i,
                    'is_active' => true,
                ]));
            }
        }

        // Sample "Causes" carousel cards — only when the table is empty.
        if (Cause::count() === 0) {
            $causes = [
                ['title' => 'Give Zakat', 'description' => 'Purify your wealth and support those most in need.', 'image' => 'images/givezakat.png'],
                ['title' => 'Give Sadaqah', 'description' => 'A voluntary act of charity that brings endless blessings.', 'image' => 'images/givesadqa.jpg'],
                ['title' => 'Support an Orphan', 'description' => 'Give an orphan shelter, food and a chance at education.', 'image' => 'images/supporton.png'],
                ['title' => 'Water Pump', 'description' => 'Provide clean, safe drinking water to a whole community.', 'image' => 'images/handpump.jpg'],
                ['title' => 'Emergency Relief', 'description' => 'Rapid help for families hit by disaster and crisis.', 'image' => 'images/changinslives4.jpg'],
                ['title' => 'Feed the Hungry', 'description' => 'Nutritious food parcels for struggling families.', 'image' => 'images/changinslives2.jpg'],
            ];

            foreach ($causes as $i => $cause) {
                Cause::create(array_merge($cause, [
                    'link' => '#',
                    'sort_order' => $i + 1,
                    'is_active' => true,
                ]));
            }
        }

        // Sample annual reports — only when the table is empty, so uploaded
        // reports are never touched.
        if (AnnualReport::count() === 0) {
            $reports = [
                ['title' => 'Annual Report 2024', 'year' => '2024', 'summary' => 'Our impact, programmes and audited accounts for 2024.', 'file_path' => 'reports/annual-report-2024.pdf'],
                ['title' => 'Annual Report 2023', 'year' => '2023', 'summary' => 'How your donations were spent across food, water, healthcare and education.', 'file_path' => 'reports/annual-report-2023.pdf'],
                ['title' => 'Annual Report 2022', 'year' => '2022', 'summary' => 'A year of growth — our reach, results and financial statements.', 'file_path' => 'reports/annual-report-2022.pdf'],
            ];

            foreach ($reports as $i => $report) {
                AnnualReport::create(array_merge($report, [
                    'sort_order' => $i + 1,
                    'is_active' => true,
                ]));
            }
        }

        // Sample "Our Projects" cards (Fidya page) — only when the table is empty.
        if (Project::count() === 0) {
            $projects = [
                ['title' => 'Food', 'description' => 'Food Support — our mission to provide for people in need.', 'image' => 'images/changinslives2.jpg'],
                ['title' => 'Binoria Water Campaign', 'description' => 'Water Crisis Hit Jamia Binoria Hard — students struggle for clean water.', 'image' => 'images/changinslives3.jpg'],
                ['title' => 'Education', 'description' => 'Helping children in rural areas access quality education.', 'image' => 'images/changinslives1.jpg'],
                ['title' => 'Healthcare', 'description' => 'Free medical care and medicine for remote communities.', 'image' => 'images/changinslives4.jpg'],
            ];

            foreach ($projects as $i => $project) {
                Project::create(array_merge($project, [
                    'link' => '#',
                    'sort_order' => $i + 1,
                    'is_active' => true,
                ]));
            }
        }
    }
}
