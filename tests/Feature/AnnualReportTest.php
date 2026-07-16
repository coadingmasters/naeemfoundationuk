<?php

namespace Tests\Feature;

use App\Models\AnnualReport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AnnualReportTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['is_admin' => true]);
    }

    private function pdf(string $name = 'report.pdf'): UploadedFile
    {
        // A tiny but genuinely valid PDF so the mimetypes rule passes.
        $path = tempnam(sys_get_temp_dir(), 'pdf').'.pdf';
        file_put_contents($path, "%PDF-1.4\n1 0 obj\n<< /Type /Catalog >>\nendobj\ntrailer\n<< /Root 1 0 R >>\n%%EOF\n");

        return new UploadedFile($path, $name, 'application/pdf', null, true);
    }

    public function test_the_public_page_lists_active_reports(): void
    {
        AnnualReport::create([
            'title' => 'Annual Report 2024', 'year' => '2024',
            'summary' => 'Our impact for 2024.',
            'file_path' => 'reports/annual-report-2024.pdf', 'is_active' => true,
        ]);

        $this->get(route('annual-report'))
            ->assertOk()
            ->assertSee('Annual Reports')
            ->assertSee('Annual Report 2024')
            ->assertSee('2024')
            ->assertSee('Download PDF')
            ->assertSee('reports/annual-report-2024.pdf', false);
    }

    public function test_hidden_reports_are_not_listed(): void
    {
        AnnualReport::create([
            'title' => 'Draft Report', 'year' => '2025',
            'file_path' => 'reports/draft.pdf', 'is_active' => false,
        ]);

        $this->get(route('annual-report'))
            ->assertOk()
            ->assertDontSee('Draft Report');
    }

    public function test_the_page_shows_an_empty_state_with_no_reports(): void
    {
        $this->get(route('annual-report'))
            ->assertOk()
            ->assertSee('Reports coming soon');
    }

    public function test_admin_can_upload_a_report(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.annual-reports.store'), [
                'title' => 'Annual Report 2025',
                'year' => '2025',
                'summary' => 'Fresh off the press.',
                'pdf_file' => $this->pdf('ar-2025.pdf'),
                'is_active' => 1,
            ])
            ->assertRedirect(route('admin.annual-reports.index'));

        $report = AnnualReport::sole();

        $this->assertSame('Annual Report 2025', $report->title);
        $this->assertStringStartsWith('reports/', $report->file_path);
        $this->assertTrue($report->is_active);

        // The uploaded file really landed in the web root.
        $this->assertFileExists(public_path($report->file_path));
        @unlink(public_path($report->file_path));
    }

    public function test_uploading_requires_a_title_year_and_pdf(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.annual-reports.store'), [])
            ->assertSessionHasErrors(['title', 'year', 'pdf_file']);

        $this->assertSame(0, AnnualReport::count());
    }

    public function test_a_non_pdf_upload_is_rejected(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.annual-reports.store'), [
                'title' => 'Bad', 'year' => '2025',
                'pdf_file' => UploadedFile::fake()->create('notes.txt', 10, 'text/plain'),
            ])
            ->assertSessionHasErrors('pdf_file');
    }

    public function test_editing_keeps_the_existing_pdf_when_none_is_uploaded(): void
    {
        $report = AnnualReport::create([
            'title' => 'Annual Report 2024', 'year' => '2024',
            'file_path' => 'reports/annual-report-2024.pdf', 'is_active' => true,
        ]);

        $this->actingAs($this->admin())
            ->put(route('admin.annual-reports.update', $report), [
                'title' => 'Annual Report 2024 (revised)',
                'year' => '2024',
                'is_active' => 1,
            ])
            ->assertRedirect(route('admin.annual-reports.index'));

        $report->refresh();
        $this->assertSame('Annual Report 2024 (revised)', $report->title);
        $this->assertSame('reports/annual-report-2024.pdf', $report->file_path);
    }

    public function test_guests_cannot_manage_reports(): void
    {
        $this->get(route('admin.annual-reports.index'))->assertRedirect(route('admin.login'));
        $this->post(route('admin.annual-reports.store'), [])->assertRedirect(route('admin.login'));
    }
}
