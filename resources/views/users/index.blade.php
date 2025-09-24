{{-- resources/views/users/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                {{ __('User Management') }}
            </h2>

            <a href="{{ route('users.create') }}"
                class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M11 5a1 1 0 112 0v6h6a1 1 0 110 2h-6v6a1 1 0 11-2 0v-6H5a1 1 0 110-2h6V5z" />
                </svg>
                {{ __('Tambah User') }}
            </a>
        </div>
    </x-slot>

    <div class="p-6 space-y-4">

        {{-- Filter/Search Bar --}}
        <form method="GET" class="grid gap-3 md:grid-cols-6">
            <div class="md:col-span-3">
                <label class="text-sm text-gray-600 dark:text-gray-300">{{ __('Cari nama/email') }}</label>
                <input type="text" name="q" value="{{ request('q', '') }}" placeholder="Ketik nama atau email…"
                    class="mt-1 w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700" />
            </div>

            @isset($roles)
                <div>
                    <label class="text-sm text-gray-600 dark:text-gray-300">{{ __('Role') }}</label>
                    <select name="role"
                        class="mt-1 w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700">
                        <option value="">{{ __('Semua') }}</option>
                        @foreach ($roles as $r)
                            <option value="{{ $r }}" @selected(request('role') === $r)>{{ ucfirst($r) }}</option>
                        @endforeach
                    </select>
                </div>
            @endisset

            @isset($statuses)
                <div>
                    <label class="text-sm text-gray-600 dark:text-gray-300">{{ __('Status Profil') }}</label>
                    <select name="status"
                        class="mt-1 w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700">
                        <option value="">{{ __('Semua') }}</option>
                        @foreach ($statuses as $key => $label)
                            <option value="{{ $key }}" @selected(request('status') === (string) $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            @endisset

            <div class="flex items-end gap-2">
                <button
                    class="inline-flex w-full justify-center rounded-xl bg-gray-900 px-4 py-2 text-white hover:bg-black">
                    {{ __('Terapkan') }}
                </button>
                <a href="{{ route('users.index') }}"
                    class="inline-flex justify-center rounded-xl border px-4 py-2 text-gray-700 dark:text-gray-200">
                    {{ __('Reset') }}
                </a>
            </div>
        </form>

        {{-- Summary --}}
        <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-4 py-3 text-sm">
            <span class="text-gray-600 dark:text-gray-300">
                {{ __('Menampilkan') }}
                <b>{{ $users->firstItem() ?? 0 }}</b>–<b>{{ $users->lastItem() ?? 0 }}</b>
                {{ __('dari') }} <b>{{ $users->total() }}</b> {{ __('user') }}.
            </span>
        </div>

        {{-- Table (desktop) --}}
        <div
            class="hidden md:block rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-800/70 text-gray-600 dark:text-gray-300">
                        <tr class="text-left">
                            <th class="px-4 py-3">{{ __('Nama') }}</th>
                            <th class="px-4 py-3">{{ __('Email') }}</th>
                            <th class="px-4 py-3">{{ __('Agency') }}</th>
                            <th class="px-4 py-3">{{ __('Kode Agent') }}</th>
                            <th class="px-4 py-3">{{ __('Status Profil') }}</th>
                            <th class="px-4 py-3 text-center">{{ __('Aksi') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50/70 dark:hover:bg-gray-800/40">
                                <td class="px-4 py-3">
                                    <div class="font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</div>
                                    <div class="text-xs text-gray-500">ID: {{ $user->id }}</div>
                                </td>
                                <td class="px-4 py-3">{{ $user->email }}</td>
                                <td class="px-4 py-3">{{ $user->agency_name ?? '—' }}</td>
                                <td class="px-4 py-3">
                                    @if ($user->kode_agent)
                                        <span
                                            class="inline-flex rounded-full px-3 py-1 text-xs font-medium bg-blue-100 text-blue-700">
                                            {{ $user->kode_agent }}
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @php
                                        // Jika pakai konstanta seperti sebelumnya
                                        $color = $user->profile_status_color ?? 'bg-gray-100 text-gray-700';
                                        $label = $user->profile_status_label ?? __('—');
                                    @endphp
                                    <span
                                        class="inline-flex rounded-full px-3 py-1 text-xs font-medium {{ $color }}">
                                        {{ $label }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('users.edit', $user) }}"
                                        class="inline-flex items-center rounded-lg px-3 py-1 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20">
                                        {{ __('Edit') }}
                                    </a>

                                    <form action="{{ route('users.destroy', $user) }}" method="POST"
                                        class="inline js-delete-form">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center rounded-lg px-3 py-1 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/20">
                                            {{ __('Hapus') }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-10 text-center text-gray-500 dark:text-gray-400">
                                    {{ __('Belum ada user.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($users->hasPages())
                <div class="border-t border-gray-100 dark:border-gray-800 px-4 py-3">
                    {{ $users->onEachSide(1)->links() }}
                </div>
            @endif
        </div>

        {{-- Cards (mobile) --}}
        <div class="md:hidden space-y-3">
            @forelse($users as $user)
                <div
                    class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-4 shadow-sm">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="font-semibold text-gray-900 dark:text-gray-100">{{ $user->name }}</div>
                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                        </div>
                        <div>
                            @php
                                $color = $user->profile_status_color ?? 'bg-gray-100 text-gray-700';
                                $label = $user->profile_status_label ?? __('—');
                            @endphp
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium {{ $color }}">
                                {{ $label }}
                            </span>
                        </div>
                    </div>
                    <div class="mt-3 text-sm text-gray-600 dark:text-gray-300">
                        <div><span class="text-gray-500">{{ __('Agency') }}:</span> {{ $user->agency_name ?? '—' }}
                        </div>
                        <div><span class="text-gray-500">{{ __('Kode Agent') }}:</span> {{ $user->kode_agent ?? '—' }}
                        </div>
                    </div>
                    <div class="mt-4 flex gap-2">
                        <a href="{{ route('users.edit', $user) }}"
                            class="flex-1 inline-flex justify-center rounded-lg px-3 py-2 text-blue-600 border border-blue-200 hover:bg-blue-50">
                            {{ __('Edit') }}
                        </a>
                        <form action="{{ route('users.destroy', $user) }}" method="POST"
                            class="flex-1 js-delete-form">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-lg px-3 py-2 text-rose-600 border border-rose-200 hover:bg-rose-50">
                                {{ __('Hapus') }}
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-gray-500">{{ __('Belum ada user.') }}</p>
            @endforelse

            @if ($users->hasPages())
                <div class="pt-2">
                    {{ $users->onEachSide(1)->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- SweetAlert2 untuk konfirmasi hapus --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('click', function(e) {
            const form = e.target.closest('.js-delete-form');
            if (form && (e.target.tagName === 'BUTTON' || e.target.closest('button'))) {
                e.preventDefault();
                Swal.fire({
                    title: '{{ __('Hapus user ini?') }}',
                    text: '{{ __('Tindakan ini tidak dapat dibatalkan.') }}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '{{ __('Ya, hapus') }}',
                    cancelButtonText: '{{ __('Batal') }}'
                }).then((r) => {
                    if (r.isConfirmed) form.submit();
                });
            }
        });
    </script>
</x-app-layout>
