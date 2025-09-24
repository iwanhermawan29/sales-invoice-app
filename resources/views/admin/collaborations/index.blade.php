<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100">
                Kolaborasi (Logo & Partner)
            </h2>
            <a href="{{ route('collaborations.create') }}"
                class="rounded-xl bg-indigo-600 text-white px-4 py-2 hover:bg-indigo-700">Tambah</a>
        </div>
    </x-slot>

    <div class="p-6 space-y-4">
        @if (session('success'))
            <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        {{-- Filter --}}
        <form method="GET"
            class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-4">
            <div class="grid gap-3 md:grid-cols-3">
                <div class="md:col-span-2">
                    <label class="text-sm text-gray-600 dark:text-gray-300">Cari</label>
                    <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Nama/keterangan…"
                        class="mt-1 w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700">
                </div>
                <div>
                    <label class="text-sm text-gray-600 dark:text-gray-300">Status</label>
                    <select name="active"
                        class="mt-1 w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700">
                        <option value="">— Semua —</option>
                        <option value="1" @selected(($active ?? '') === '1')>Aktif</option>
                        <option value="0" @selected(($active ?? '') === '0')>Nonaktif</option>
                    </select>
                </div>
            </div>
            <div class="mt-3 flex justify-end gap-2">
                <a href="{{ route('collaborations.index') }}" class="rounded-xl border px-4 py-2">Reset</a>
                <button class="rounded-xl bg-gray-900 text-white px-4 py-2 hover:bg-black">Terapkan</button>
            </div>
        </form>

        {{-- Grid --}}
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($collabs as $c)
                <div
                    class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 overflow-hidden">
                    <div class="h-40 bg-gray-50 dark:bg-gray-800 grid place-items-center">
                        @if ($c->url)
                            <img src="{{ $c->url }}" alt="{{ $c->name }}" class="max-h-32 object-contain">
                        @else
                            <div class="text-gray-400 text-sm">No Logo</div>
                        @endif
                    </div>
                    <div class="p-4 space-y-2">
                        <div class="flex items-center justify-between">
                            <h3 class="font-semibold">{{ $c->name }}</h3>
                            <span
                                class="text-xs rounded-full px-2 py-0.5
                                {{ $c->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ $c->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                        @if ($c->description)
                            <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-2">{{ $c->description }}</p>
                        @endif
                        @if ($c->website_url)
                            <a href="{{ $c->website_url }}" target="_blank"
                                class="text-xs text-indigo-600 hover:underline">
                                {{ parse_url($c->website_url, PHP_URL_HOST) }}
                            </a>
                        @endif

                        <div class="pt-3 flex items-center justify-end gap-2">
                            <a href="{{ route('collaborations.edit', $c) }}"
                                class="rounded-lg px-3 py-1.5 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20">Edit</a>
                            <form action="{{ route('collaborations.destroy', $c) }}" method="POST"
                                class="inline js-del">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="rounded-lg px-3 py-1.5 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/20">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center text-gray-500 dark:text-gray-400 py-10">
                    Belum ada data kolaborasi.
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if ($collabs->hasPages())
            <div>{{ $collabs->onEachSide(1)->links() }}</div>
        @endif
    </div>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('click', function(e) {
            const f = e.target.closest('.js-del');
            if (!f) return;
            e.preventDefault();
            Swal.fire({
                title: 'Hapus data ini?',
                text: 'Tindakan ini tidak dapat dibatalkan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal'
            }).then(res => {
                if (res.isConfirmed) f.submit();
            });
        });
    </script>
</x-app-layout>
