<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RamadanTimetableTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_page_loads_with_its_key_sections(): void
    {
        $this->get(route('schedule-ramadan-giving'))
            ->assertOk()
            ->assertSee('Ramadan Timetable')
            ->assertSee('Seher &amp; Iftar Schedule', false)
            ->assertSee('The Purpose of Fasting in Ramadan')
            ->assertSee('Rewards and Blessings of Fasting')
            ->assertSee('When Does Ramadan 2026 Begin?', false)
            ->assertSee('Our Projects');
    }

    public function test_every_fast_plus_eid_is_listed(): void
    {
        $html = $this->get(route('schedule-ramadan-giving'))->assertOk()->getContent();

        // 1st fast, 30th fast, and the Eid row.
        $this->assertStringContainsString('18/02/2026', $html);
        $this->assertStringContainsString('1st Ramadan', $html);
        $this->assertStringContainsString('19/03/2026', $html);
        $this->assertStringContainsString('20/03/2026', $html);
        $this->assertStringContainsString('Eid al-Fitr', $html);

        // 30 fasts + 1 Eid row = 31 date cells.
        $this->assertSame(31, substr_count($html, '/2026</td>'));
    }

    public function test_missing_prayer_times_are_shown_as_a_dash_with_a_notice(): void
    {
        config(['ramadan-timetable.times' => []]);

        $this->get(route('schedule-ramadan-giving'))
            ->assertOk()
            ->assertSee('being finalised')
            ->assertSee('—', false);
    }

    public function test_configured_prayer_times_are_rendered(): void
    {
        config(['ramadan-timetable.times' => [
            '2026-02-18' => ['5:33', '7:10', '12:20', '14:48', '17:24', '18:46'],
        ]]);

        $this->get(route('schedule-ramadan-giving'))
            ->assertOk()
            ->assertSee('5:33')
            ->assertSee('17:24')
            ->assertSee('18:46')
            ->assertDontSee('being finalised');
    }

    public function test_a_print_button_is_offered_when_no_pdf_exists(): void
    {
        $this->assertFileDoesNotExist(public_path(config('ramadan-timetable.pdf')));

        $this->get(route('schedule-ramadan-giving'))
            ->assertOk()
            ->assertSee('data-print-page', false)
            ->assertSee('Print / save timetable', false);
    }

    public function test_the_giving_menu_links_to_the_timetable(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee(route('schedule-ramadan-giving'), false);
    }
}
