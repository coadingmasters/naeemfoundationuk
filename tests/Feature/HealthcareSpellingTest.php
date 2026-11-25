<?php

namespace Tests\Feature;

use App\Models\Cause;
use Tests\TestCase;

class HealthcareSpellingTest extends TestCase
{
    public function test_health_care_is_joined_when_a_card_is_saved(): void
    {
        $cause = new Cause(['title' => 'Health Care']);
        $cause->setAttribute('region', 'UK');

        // Fire only the saving listeners (no database needed).
        Cause::getEventDispatcher()->dispatch('eloquent.saving: '.Cause::class, $cause);

        $this->assertSame('Healthcare', $cause->title);
    }
}
