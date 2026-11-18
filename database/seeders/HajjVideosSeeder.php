<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Adds the Hajj page videos (same ones the UK site shows) to this install's
 * database. Safe to run again: a video that is already there (same link) is
 * updated, never duplicated.
 *
 *   php artisan db:seed --class=HajjVideosSeeder --force
 *
 * The three customer-review videos play straight from the UK site's files.
 */
class HajjVideosSeeder extends Seeder
{
    public function run(): void
    {
        $region = config('countries.content_region', 'GB');
        $now = now();

        // "Steps of Hajj" gallery (YouTube).
        $steps = [
            ['Ihram', 'EVivFqxJ-4Y'],
            ['Tawaf', 'F5y5jgHZOr0'],
            ['Sai', 'CjpqqD6PDeY'],
            ['Mina', 'R1HXViLKWxc'],
            ['Arafat', '5KlogLWVs5g'],
            ['Muzdalifah', 'Ru-z2U5J87Q'],
            ['Rami', 'zaDCmNojbp8'],
            ['Qurbani Tawaf', 'ZUROAWcaUUs'],
            ['Episode 9', '23V8znyaRCs'],
            ['Episode 10', 'NiDrHg2nTKY'],
            ['Episode 11', '8S1ek4grlr0'],
        ];

        foreach ($steps as $i => [$title, $id]) {
            $url = 'https://www.youtube.com/watch?v='.$id;
            $row = ['title' => $title, 'sort_order' => $i + 1, 'is_active' => true, 'updated_at' => $now];

            DB::table('hajj_step_videos')->where('video_url', $url)->exists()
                ? DB::table('hajj_step_videos')->where('video_url', $url)->update($row)
                : DB::table('hajj_step_videos')->insert($row + ['video_url' => $url, 'created_at' => $now]);
        }

        // Hajj e Badal clip + "Recent Hajj Reviews" (customer videos).
        $base = 'https://naeemfoundation.co.uk/videos/hajj/';
        $reviews = [
            ['What Our Customers Say', 'hajj-video-20260911104143-0f37683f.mp4'],
            ['Customer Review', 'hajj-video-20260911104437-6888be77.mp4'],
            ['Customer Review', 'hajj-video-20260911104604-5c09a3c5.mp4'],
        ];

        foreach ($reviews as $i => [$title, $file]) {
            $url = $base.$file;
            $row = ['region' => $region, 'title' => $title, 'sort_order' => $i + 1, 'is_active' => true, 'updated_at' => $now];

            DB::table('hajj_videos')->where('video_url', $url)->exists()
                ? DB::table('hajj_videos')->where('video_url', $url)->update($row)
                : DB::table('hajj_videos')->insert($row + ['video_url' => $url, 'created_at' => $now]);
        }
    }
}
