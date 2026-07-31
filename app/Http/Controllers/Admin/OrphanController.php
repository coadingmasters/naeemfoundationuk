<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\AssignsSortOrder;
use App\Http\Controllers\Concerns\HandlesImageUploads;
use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Orphan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Throwable;

class OrphanController extends Controller
{
    use AssignsSortOrder, HandlesImageUploads;

    /** Directory (relative to the web root) where orphan photos are stored. */
    private const UPLOAD_DIR = 'images/orphans';

    public function index()
    {
        $orphans = Orphan::ordered()->paginate(12);

        // Per-orphan sponsorship totals, so admins can see who has received money.
        $stats = $this->sponsorshipTotals();

        return view('admin.orphans.index', compact('orphans', 'stats'));
    }

    public function create()
    {
        $orphan = new Orphan(['is_active' => true]);

        return view('admin.orphans.create', compact('orphan'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        $data['sort_order'] = $request->filled('sort_order')
            ? (int) $request->input('sort_order')
            : $this->nextSortOrder(Orphan::class);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('photo')) {
            $data['photo'] = $this->storeUploadedImage($request->file('photo'), self::UPLOAD_DIR, 'orphan');
        } else {
            unset($data['photo']);
        }

        Orphan::create($data);

        return redirect()->route('admin.orphans.index')
            ->with('success', 'Orphan added successfully.');
    }

    public function edit(Orphan $orphan)
    {
        return view('admin.orphans.edit', compact('orphan'));
    }

    public function update(Request $request, Orphan $orphan): RedirectResponse
    {
        $data = $this->validateData($request);
        $data['is_active'] = $request->boolean('is_active');

        if (! $request->filled('sort_order')) {
            unset($data['sort_order']); // keep the existing order
        }

        if ($request->hasFile('photo')) {
            $this->deleteUploadedImage($orphan->photo, self::UPLOAD_DIR);
            $data['photo'] = $this->storeUploadedImage($request->file('photo'), self::UPLOAD_DIR, 'orphan');
        } else {
            unset($data['photo']); // don't overwrite the existing photo with null
        }

        $orphan->update($data);

        return redirect()->route('admin.orphans.index')
            ->with('success', 'Orphan updated successfully.');
    }

    public function destroy(Orphan $orphan): RedirectResponse
    {
        $this->deleteUploadedImage($orphan->photo, self::UPLOAD_DIR);
        $orphan->delete();

        return redirect()->route('admin.orphans.index')
            ->with('success', 'Orphan deleted successfully.');
    }

    /** Validate the request. Photo is always optional (a placeholder is shown). */
    private function validateData(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'grade' => ['nullable', 'string', 'max:255'],
            'dob' => ['nullable', 'string', 'max:255'],
            'story' => ['nullable', 'string', 'max:2000'],
            'monthly_amount' => ['nullable', 'integer', 'min:1', 'max:1000000'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);
    }

    /**
     * Tally how much each orphan has raised, by scanning the line items of paid
     * / active donations for their orphan_id. Region-scoped automatically through
     * the Donation model, so admins only see totals for their own region.
     *
     * @return array<int, array{count:int, raised:float}>
     */
    private function sponsorshipTotals(): array
    {
        $totals = [];

        try {
            if (! Schema::hasTable('donations')) {
                return $totals;
            }

            Donation::query()
                ->whereIn('status', ['paid', 'active'])
                ->get(['items'])
                ->each(function (Donation $donation) use (&$totals) {
                    foreach ((array) $donation->items as $item) {
                        $orphanId = $item['orphan_id'] ?? null;
                        if (! $orphanId) {
                            continue;
                        }

                        $totals[$orphanId]['count'] = ($totals[$orphanId]['count'] ?? 0) + 1;
                        $totals[$orphanId]['raised'] = ($totals[$orphanId]['raised'] ?? 0)
                            + ((float) ($item['amount'] ?? 0) * (int) ($item['qty'] ?? 1));
                    }
                });
        } catch (Throwable $e) {
            // If donations can't be read, just show no totals rather than error.
        }

        return $totals;
    }
}
