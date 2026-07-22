@extends('admin.layouts.app')

@section('title', 'Admins')
@section('heading', 'Admins')
@section('subheading', 'Who can access the admin panel, and which region they manage.')

@section('actions')
    <a href="{{ route('admin.users.create') }}"
       class="inline-flex items-center gap-2 rounded-lg bg-brand px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-dark">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14" stroke-linecap="round"/></svg>
        New Admin
    </a>
@endsection

@section('content')
    <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-gray-100 text-xs uppercase tracking-wide text-gray-400">
                        <th class="px-5 py-3 font-semibold">Admin</th>
                        <th class="px-5 py-3 font-semibold">Role</th>
                        <th class="px-5 py-3 font-semibold">Region</th>
                        <th class="px-5 py-3 text-right font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($users as $u)
                        <tr>
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    <span class="grid h-9 w-9 place-items-center rounded-full bg-brand text-sm font-bold text-white">{{ strtoupper(substr($u->name, 0, 1)) }}</span>
                                    <div class="min-w-0">
                                        <p class="font-semibold text-navy-dark">{{ $u->name }} @if ($u->id === auth()->id())<span class="text-xs font-normal text-gray-400">(you)</span>@endif</p>
                                        <p class="truncate text-xs text-gray-400">{{ $u->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3">
                                @if ($u->isSuperAdmin())
                                    <span class="rounded-full bg-brand/10 px-2.5 py-1 text-xs font-semibold text-brand">Super admin</span>
                                @else
                                    <span class="rounded-full bg-navy/10 px-2.5 py-1 text-xs font-semibold text-navy">Region admin</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-gray-600">
                                @if ($u->isSuperAdmin())
                                    🌍 All regions
                                @else
                                    {{ config('countries.list.'.$u->region.'.flag', '') }} {{ config('countries.list.'.$u->region.'.name', $u->region ?? '—') }}
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.users.edit', $u) }}"
                                       class="inline-flex items-center gap-1.5 rounded-md border border-gray-200 px-3 py-1.5 text-xs font-semibold text-navy transition hover:border-brand hover:text-brand">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 20h4l10-10-4-4L4 16v4zM13.5 6.5l4 4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        Edit
                                    </a>
                                    @if ($u->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.users.destroy', $u) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" data-admin-delete data-label="{{ $u->name }}"
                                                    class="inline-flex items-center gap-1.5 rounded-md border border-gray-200 px-3 py-1.5 text-xs font-semibold text-red-600 transition hover:border-red-300 hover:bg-red-50">
                                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 7h16M9 7V5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2m2 0v12a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if ($users->hasPages())
        <div class="mt-5">{{ $users->links() }}</div>
    @endif
@endsection
