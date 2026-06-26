<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\AssignsSortOrder;
use App\Http\Controllers\Concerns\HandlesImageUploads;
use App\Http\Controllers\Controller;
use App\Models\Cause;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CauseController extends Controller
{
    use AssignsSortOrder, HandlesImageUploads;

    /** Directory (relative to the web root) where cause images are stored. */
    private const UPLOAD_DIR = 'images/causes';

    public function index()
    {
        $causes = Cause::ordered()->paginate(10);

        return view('admin.causes.index', compact('causes'));
    }

    public function create()
    {
        $cause = new Cause(['is_active' => true]);

        return view('admin.causes.create', compact('cause'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request, true);
        $data['sort_order'] = $request->filled('sort_order')
            ? (int) $request->input('sort_order')
            : $this->nextSortOrder(Cause::class);
        $data['image'] = $this->storeUploadedImage($request->file('image'), self::UPLOAD_DIR, 'cause');
        $data['is_active'] = $request->boolean('is_active');

        Cause::create($data);

        return redirect()->route('admin.causes.index')
            ->with('success', 'Cause created successfully.');
    }

    public function edit(Cause $cause)
    {
        return view('admin.causes.edit', compact('cause'));
    }

    public function update(Request $request, Cause $cause): RedirectResponse
    {
        $data = $this->validateData($request, false);
        $data['is_active'] = $request->boolean('is_active');

        if (! $request->filled('sort_order')) {
            unset($data['sort_order']); // keep the existing order
        }

        if ($request->hasFile('image')) {
            $this->deleteUploadedImage($cause->image, self::UPLOAD_DIR);
            $data['image'] = $this->storeUploadedImage($request->file('image'), self::UPLOAD_DIR, 'cause');
        }

        $cause->update($data);

        return redirect()->route('admin.causes.index')
            ->with('success', 'Cause updated successfully.');
    }

    public function destroy(Cause $cause): RedirectResponse
    {
        $this->deleteUploadedImage($cause->image, self::UPLOAD_DIR);
        $cause->delete();

        return redirect()->route('admin.causes.index')
            ->with('success', 'Cause deleted successfully.');
    }

    /** Validate the request, optionally requiring an image (create vs update). */
    private function validateData(Request $request, bool $imageRequired): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'image' => [$imageRequired ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'link' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);
    }
}
