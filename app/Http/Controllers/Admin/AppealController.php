<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\AssignsSortOrder;
use App\Http\Controllers\Concerns\HandlesImageUploads;
use App\Http\Controllers\Controller;
use App\Models\Appeal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AppealController extends Controller
{
    use AssignsSortOrder, HandlesImageUploads;

    /** Directory (relative to the web root) where appeal images are stored. */
    private const UPLOAD_DIR = 'images/appeals';

    public function index()
    {
        $appeals = Appeal::ordered()->paginate(10);

        return view('admin.appeals.index', compact('appeals'));
    }

    public function create()
    {
        $appeal = new Appeal(['is_active' => true]);

        return view('admin.appeals.create', compact('appeal'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request, true);
        $data['sort_order'] = $request->filled('sort_order')
            ? (int) $request->input('sort_order')
            : $this->nextSortOrder(Appeal::class);
        $data['image'] = $this->storeUploadedImage($request->file('image'), self::UPLOAD_DIR, 'appeal');
        $data['is_active'] = $request->boolean('is_active');

        Appeal::create($data);

        return redirect()->route('admin.appeals.index')
            ->with('success', 'Appeal created successfully.');
    }

    public function edit(Appeal $appeal)
    {
        return view('admin.appeals.edit', compact('appeal'));
    }

    public function update(Request $request, Appeal $appeal): RedirectResponse
    {
        $data = $this->validateData($request, false);
        $data['is_active'] = $request->boolean('is_active');

        if (! $request->filled('sort_order')) {
            unset($data['sort_order']); // keep the existing order
        }

        if ($request->hasFile('image')) {
            $this->deleteUploadedImage($appeal->image, self::UPLOAD_DIR);
            $data['image'] = $this->storeUploadedImage($request->file('image'), self::UPLOAD_DIR, 'appeal');
        }

        $appeal->update($data);

        return redirect()->route('admin.appeals.index')
            ->with('success', 'Appeal updated successfully.');
    }

    public function destroy(Appeal $appeal): RedirectResponse
    {
        $this->deleteUploadedImage($appeal->image, self::UPLOAD_DIR);
        $appeal->delete();

        return redirect()->route('admin.appeals.index')
            ->with('success', 'Appeal deleted successfully.');
    }

    /** Validate the request, optionally requiring an image (create vs update). */
    private function validateData(Request $request, bool $imageRequired): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:500'],
            'image' => [$imageRequired ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'link' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);
    }
}
