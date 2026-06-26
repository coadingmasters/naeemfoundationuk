<?php

namespace Database\Seeders;

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
    }
}
