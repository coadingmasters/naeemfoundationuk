<?php

namespace App\Http\Controllers;

use App\Models\Orphan;
use App\Services\SponsorStudents;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class OrphanController extends Controller
{
    public function __construct(private SponsorStudents $students) {}

    /**
     * The "Sponsor an Orphan" grid, with AJAX pagination. Children come from
     * Jamia Binoria's sponsorship API; the orphans added in the admin are only
     * a fallback for when the API can't be reached.
     */
    public function index(Request $request)
    {
        $students = $this->students->all();

        if ($students !== null && $students->isNotEmpty()) {
            $page = LengthAwarePaginator::resolveCurrentPage();
            $orphans = new LengthAwarePaginator(
                $this->asCards($students->forPage($page, 12)),
                $students->count(),
                12,
                $page,
                ['path' => route('orphans-sponsorships')],
            );
        } else {
            $orphans = Orphan::active()->ordered()->paginate(12);
        }

        // AJAX page changes only need the swapped-in grid, not the whole page.
        if ($request->ajax() || $request->wantsJson()) {
            return view('partials.orphan-list', compact('orphans'));
        }

        return view('orphans-sponsorships', compact('orphans'));
    }

    /** A single orphan's profile + a donation widget scoped to them. */
    public function show(Orphan $orphan)
    {
        abort_unless($orphan->is_active, 404);

        return view('orphans.show', compact('orphan'));
    }

    /** A sponsorship student's profile (from the Binoria API). */
    public function student(string $id)
    {
        $student = $this->students->find($id);
        abort_unless($student, 404);

        return view('orphans.show', ['orphan' => $this->asCards(collect([$student]))->first()]);
    }

    /**
     * Shape API students like Orphan models so the existing cards, carousel
     * and profile page render them unchanged.
     *
     * @param  Collection<int, object>  $students
     * @return Collection<int, object>
     */
    public static function asCards(Collection $students): Collection
    {
        return $students->map(fn ($s) => (object) [
            'id' => null,
            'student_id' => $s->id,
            'name' => $s->name,
            'photo' => null,
            'grade' => $s->status,
            'location' => trim($s->flag.' '.$s->country),
            'age' => $s->age,
            'dob' => null,
            'story' => null,
            'monthly_amount' => null,
            'url' => route('sponsor-students.show', $s->id),
        ])->values();
    }
}
