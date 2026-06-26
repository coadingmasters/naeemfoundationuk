<?php

namespace Database\Seeders;

use App\Models\Appeal;
use App\Models\HeroSlide;
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
    }
}
