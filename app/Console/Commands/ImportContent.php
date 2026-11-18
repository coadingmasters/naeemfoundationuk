<?php

namespace App\Console\Commands;

use App\Models\Appeal;
use App\Models\Cause;
use App\Models\HeroSlide;
use App\Models\NewsPost;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

/**
 * Loads the home-page cards (hero slides, appeals, causes, news) from another
 * install of this site, e.g. on the Canada install:
 *
 *   php artisan content:import https://naeemfoundation.co.uk
 *
 * Safe to run again — rows are matched and updated, never duplicated. Images
 * that exist in this install's public folder are used as they are; images that
 * were uploaded on the source site keep pointing at the source site.
 */
class ImportContent extends Command
{
    protected $signature = 'content:import {source : Base URL of the site to copy from}';

    protected $description = 'Copy hero slides, appeals, causes and news from another install of this site';

    public function handle(): int
    {
        $source = rtrim((string) $this->argument('source'), '/');
        $own = config('countries.content_region', 'GB');

        $response = Http::timeout(30)->acceptJson()->get($source.'/content-feed.json');
        if (! $response->successful()) {
            $this->error('Could not read '.$source.'/content-feed.json (HTTP '.$response->status().').');

            return self::FAILURE;
        }
        $feed = $response->json();

        $image = function (?string $path) use ($source): ?string {
            if (blank($path) || preg_match('#^(https?:)?//#', $path)) {
                return $path;
            }

            return file_exists(public_path(ltrim($path, '/'))) ? $path : $source.'/'.ltrim($path, '/');
        };

        foreach ($feed['hero_slides'] ?? [] as $row) {
            $row['image'] = $image($row['image']);
            $row['mobile_image'] = $image($row['mobile_image'] ?? null);
            HeroSlide::withoutGlobalScope('region')->updateOrCreate(
                ['region' => $own, 'title' => $row['title'], 'subtitle' => $row['subtitle'], 'image' => $row['image']],
                $row + ['is_active' => true],
            );
        }

        foreach ($feed['appeals'] ?? [] as $row) {
            $row['image'] = $image($row['image']);
            Appeal::withoutGlobalScope('region')->updateOrCreate(
                ['region' => $own, 'slug' => $row['slug']],
                $row + ['is_active' => true],
            );
        }

        foreach ($feed['causes'] ?? [] as $row) {
            $row['image'] = $image($row['image']);
            Cause::withoutGlobalScope('region')->updateOrCreate(
                ['region' => $own, 'title' => $row['title']],
                $row + ['is_active' => true],
            );
        }

        foreach ($feed['news_posts'] ?? [] as $row) {
            $row['image'] = $image($row['image']);
            NewsPost::updateOrCreate(['slug' => $row['slug']], $row + ['is_active' => true]);
        }

        $this->info(sprintf(
            'Imported %d hero slides, %d appeals, %d causes, %d news posts into region %s.',
            count($feed['hero_slides'] ?? []), count($feed['appeals'] ?? []),
            count($feed['causes'] ?? []), count($feed['news_posts'] ?? []), $own,
        ));

        return self::SUCCESS;
    }
}
