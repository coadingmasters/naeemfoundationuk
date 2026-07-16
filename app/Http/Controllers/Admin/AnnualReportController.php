<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\AssignsSortOrder;
use App\Http\Controllers\Concerns\HandlesImageUploads;
use App\Http\Controllers\Controller;
use App\Models\AnnualReport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AnnualReportController extends Controller
{
    use AssignsSortOrder, HandlesImageUploads;

    /** Directories (relative to the web root) for uploads. */
    private const PDF_DIR = 'reports';
    private const COVER_DIR = 'images/reports';

    public function index()
    {
        $reports = AnnualReport::ordered()->paginate(10);

        return view('admin.annual-reports.index', compact('reports'));
    }

    public function create()
    {
        $report = new AnnualReport(['is_active' => true, 'year' => date('Y')]);

        return view('admin.annual-reports.create', compact('report'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request, creating: true);

        $data['sort_order'] = $request->filled('sort_order')
            ? (int) $request->input('sort_order')
            : $this->nextSortOrder(AnnualReport::class);
        $data['is_active'] = $request->boolean('is_active');
        $data['file_path'] = $this->storeUploadedImage($request->file('pdf_file'), self::PDF_DIR, 'annual-report');

        if ($request->hasFile('cover_file')) {
            $data['cover_path'] = $this->storeUploadedImage($request->file('cover_file'), self::COVER_DIR, 'report-cover');
        }

        AnnualReport::create($data);

        return redirect()->route('admin.annual-reports.index')
            ->with('success', 'Annual report added successfully.');
    }

    public function edit(AnnualReport $annualReport)
    {
        return view('admin.annual-reports.edit', ['report' => $annualReport]);
    }

    public function update(Request $request, AnnualReport $annualReport): RedirectResponse
    {
        $data = $this->validateData($request, creating: false);
        $data['is_active'] = $request->boolean('is_active');

        if (! $request->filled('sort_order')) {
            unset($data['sort_order']); // keep the existing order
        }

        // Only replace files when new ones are supplied.
        if ($request->hasFile('pdf_file')) {
            $this->deleteUploadedImage($annualReport->file_path, self::PDF_DIR);
            $data['file_path'] = $this->storeUploadedImage($request->file('pdf_file'), self::PDF_DIR, 'annual-report');
        }

        if ($request->hasFile('cover_file')) {
            $this->deleteUploadedImage($annualReport->cover_path, self::COVER_DIR);
            $data['cover_path'] = $this->storeUploadedImage($request->file('cover_file'), self::COVER_DIR, 'report-cover');
        }

        $annualReport->update($data);

        return redirect()->route('admin.annual-reports.index')
            ->with('success', 'Annual report updated successfully.');
    }

    public function destroy(AnnualReport $annualReport): RedirectResponse
    {
        $this->deleteUploadedImage($annualReport->file_path, self::PDF_DIR);
        $this->deleteUploadedImage($annualReport->cover_path, self::COVER_DIR);
        $annualReport->delete();

        return redirect()->route('admin.annual-reports.index')
            ->with('success', 'Annual report deleted successfully.');
    }

    /** @return array<string, mixed> */
    private function validateData(Request $request, bool $creating): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'year' => ['required', 'string', 'max:9'],
            'summary' => ['nullable', 'string', 'max:1000'],
            // The PDF is required when creating, optional when editing.
            'pdf_file' => [$creating ? 'required' : 'nullable', 'file', 'mimetypes:application/pdf', 'max:20480'],
            'cover_file' => ['nullable', 'image', 'max:4096'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);

        return array_intersect_key($validated, array_flip(['title', 'year', 'summary', 'sort_order']));
    }
}
