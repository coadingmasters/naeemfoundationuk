<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

/**
 * Admin-user management — super admins only (guarded by the `super` middleware).
 * Region admins are scoped to one region; super admins see every region.
 */
class UserController extends Controller
{
    public function index()
    {
        $users = User::where('is_admin', true)->orderByDesc('id')->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create', ['user' => new User]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request, null);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_admin' => true,
            'role' => $data['role'],
            'region' => $data['role'] === 'super_admin' ? null : $data['region'],
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Admin created.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $this->validateData($request, $user);

        // Don't let the last super admin be demoted (locks everyone out).
        if ($user->isSuperAdmin() && $data['role'] !== 'super_admin' && $this->superAdminCount() <= 1) {
            return back()->with('error', 'You can’t demote the last super admin.');
        }

        $user->fill([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'region' => $data['role'] === 'super_admin' ? null : $data['region'],
        ]);

        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Admin updated.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You can’t delete your own account.');
        }
        if ($user->isSuperAdmin() && $this->superAdminCount() <= 1) {
            return back()->with('error', 'You can’t delete the last super admin.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Admin removed.');
    }

    private function validateData(Request $request, ?User $user): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user?->id)],
            'password' => [$user ? 'nullable' : 'required', 'nullable', 'string', 'min:8'],
            'role' => ['required', Rule::in(['super_admin', 'admin'])],
            'region' => ['nullable', 'required_if:role,admin', Rule::in(array_keys(config('countries.list', [])))],
        ]);
    }

    private function superAdminCount(): int
    {
        return User::where('role', 'super_admin')->count();
    }
}
