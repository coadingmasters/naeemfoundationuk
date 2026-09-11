<?php

namespace App\Support;

/**
 * The 8 stages of Hajj shown in the "Steps of Hajj" grid on the Hajj page.
 * Single source of truth for both the public section and the admin step
 * video picker (Admin -> Hajj Steps), keyed so each step can carry its own
 * admin-uploaded video (see App\Models\HajjStepVideo).
 */
class HajjSteps
{
    /**
     * @return array<string, array{title: string, text: string}>
     */
    public static function all(): array
    {
        return [
            'ihram' => [
                'title' => 'Ihram',
                'text' => 'Pilgrims enter a state of purity and intention, wearing simple garments and committing to spiritual discipline.',
            ],
            'tawaf' => [
                'title' => 'Tawaf',
                'text' => 'Circling the Kaaba seven times in devotion, symbolising unity and submission to Allah.',
            ],
            'sai' => [
                'title' => 'Sa’i',
                'text' => 'Walking between Safa and Marwah, honouring the perseverance of Hajar (AS).',
            ],
            'mina' => [
                'title' => 'Mina',
                'text' => 'A place of reflection and preparation, where pilgrims stay and engage in worship.',
            ],
            'arafat' => [
                'title' => 'Arafat',
                'text' => 'The heart of Hajj — a day of intense duʿāʾ, repentance and forgiveness.',
            ],
            'muzdalifah' => [
                'title' => 'Muzdalifah',
                'text' => 'A night under the open sky, gathering pebbles and remembering simplicity.',
            ],
            'rami' => [
                'title' => 'Rami (Stoning of Jamarat)',
                'text' => 'Rejecting temptation and reaffirming obedience to Allah.',
            ],
            'qurbani-tawaf' => [
                'title' => 'Qurbani & Final Tawaf',
                'text' => 'Completing the sacrifice and returning to Makkah to conclude Hajj.',
            ],
        ];
    }

    /** True when $key is one of the 8 fixed steps. */
    public static function isStep(string $key): bool
    {
        return array_key_exists($key, static::all());
    }
}
