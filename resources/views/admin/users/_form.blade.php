@php $inputClass = 'h-11 w-full rounded-lg border border-gray-300 bg-white px-3.5 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30'; @endphp

<div class="mx-auto max-w-2xl space-y-6">
    <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label for="name" class="mb-1.5 block text-sm font-semibold text-navy-dark">Name <span class="text-red-500">*</span></label>
                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required class="{{ $inputClass }}">
            </div>
            <div>
                <label for="email" class="mb-1.5 block text-sm font-semibold text-navy-dark">Email <span class="text-red-500">*</span></label>
                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required class="{{ $inputClass }}">
            </div>
        </div>

        <div class="mt-5">
            <label for="password" class="mb-1.5 block text-sm font-semibold text-navy-dark">
                Password @unless ($user->exists)<span class="text-red-500">*</span>@else<span class="text-gray-400">(leave blank to keep current)</span>@endunless
            </label>
            <input id="password" name="password" type="password" autocomplete="new-password" {{ $user->exists ? '' : 'required' }} placeholder="Minimum 8 characters" class="{{ $inputClass }}">
        </div>

        <div class="mt-5 grid gap-5 sm:grid-cols-2">
            <div>
                <label for="role" class="mb-1.5 block text-sm font-semibold text-navy-dark">Role <span class="text-red-500">*</span></label>
                <select id="role" name="role" data-role-select required class="{{ $inputClass }}">
                    <option value="admin" @selected(old('role', $user->role ?? 'admin') === 'admin')>Region admin (one region)</option>
                    <option value="super_admin" @selected(old('role', $user->role ?? 'admin') === 'super_admin')>Super admin (all regions)</option>
                </select>
            </div>
            <div data-region-field>
                <label for="region" class="mb-1.5 block text-sm font-semibold text-navy-dark">Region <span class="text-red-500">*</span></label>
                <select id="region" name="region" class="{{ $inputClass }}">
                    @foreach (config('countries.list') as $code => $r)
                        <option value="{{ $code }}" @selected(old('region', $user->region) === $code)>{{ $r['flag'] }} {{ $r['name'] }}</option>
                    @endforeach
                </select>
                <p class="mt-1 text-xs text-gray-400">This admin will only see and manage this region.</p>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('admin.users.index') }}" class="rounded-lg border border-gray-200 px-5 py-2.5 text-sm font-semibold text-navy transition hover:bg-gray-50">Cancel</a>
        <button type="submit" class="rounded-lg bg-brand px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-dark">Save admin</button>
    </div>
</div>

@push('scripts')
<script>
(function () {
    const role = document.querySelector('[data-role-select]');
    const regionField = document.querySelector('[data-region-field]');
    if (!role || !regionField) return;
    const sync = () => { regionField.style.display = role.value === 'admin' ? '' : 'none'; };
    role.addEventListener('change', sync);
    sync();
})();
</script>
@endpush
