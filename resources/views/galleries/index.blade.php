{{-- resources/views/galleries/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100">Galeri Dokumentasi</h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('galleries.create') }}"
                    class="inline-flex items-center gap-2 rounded-xl px-4 py-2 bg-indigo-600 text-white hover:bg-indigo-700">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 5v14m-7-7h14" />
                    </svg>
                    Tambah Foto
                </a>
            </div>
        </div>
    </x-slot>

    <div class="p-6 space-y-6" x-data>
        {{-- Flash --}}
        @foreach (['success', 'error'] as $f)
            @if (session($f))
                <div
                    class="rounded-xl px-4 py-3 {{ $f === 'success' ? 'bg-green-50 text-green-800 border border-green-200' : 'bg-rose-50 text-rose-800 border border-rose-200' }}">
                    {{ session($f) }}
                </div>
            @endif
        @endforeach

        {{-- Filter & Search --}}
        <form method="GET"
            class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-4">
            <div class="grid gap-3 md:grid-cols-4">
                <div class="md:col-span-2">
                    <label class="text-sm text-gray-600 dark:text-gray-300">Cari</label>
                    <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Judul/Caption…"
                        class="mt-1 w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700" />
                </div>
                <div>
                    <label class="text-sm text-gray-600 dark:text-gray-300">Kota</label>
                    <select name="city"
                        class="mt-1 w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700">
                        <option value="">— Semua —</option>
                        @php
                            $cityOptions = $cities ?? [
                                'Jakarta',
                                'Medan',
                                'Bandung',
                                'Semarang',
                                'Surabaya',
                                'Bali',
                                'Manado',
                                'Makassar',
                                'Palembang',
                            ];
                        @endphp
                        @foreach ($cityOptions as $c)
                            <option value="{{ $c }}" @selected(($city ?? '') === $c)>{{ $c }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button
                        class="inline-flex w-full justify-center rounded-xl bg-gray-900 px-4 py-2 text-white hover:bg-black">
                        Terapkan
                    </button>
                </div>
            </div>
        </form>

        {{-- Grid Cards --}}
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @forelse ($galleries as $g)
                <div
                    class="group overflow-hidden rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow">
                    <div class="relative h-44 bg-gray-100 dark:bg-gray-800">
                        @if (!empty($g->photo_path))
                            <img src="{{ Storage::url($g->photo_path) }}" class="h-full w-full object-cover"
                                alt="{{ $g->title ?? 'Photo' }}">
                        @else
                            <div class="absolute inset-0 grid place-content-center text-gray-400">No Image</div>
                        @endif

                        <div class="absolute top-2 left-2">
                            <span
                                class="inline-flex rounded-full bg-indigo-100 text-indigo-700 px-3 py-1 text-xs font-medium">
                                {{ $g->city }}
                            </span>
                        </div>

                        @if ($g->contest_id && $g->relationLoaded('contest') && $g->contest)
                            <div class="absolute top-2 right-2">
                                <span
                                    class="inline-flex rounded-full bg-amber-100 text-amber-700 px-3 py-1 text-xs font-medium">
                                    Kontes
                                </span>
                            </div>
                        @endif
                    </div>

                    <div class="p-4 space-y-2">
                        <div class="font-semibold text-gray-900 dark:text-gray-100 line-clamp-1">
                            {{ $g->title ?? 'Tanpa Judul' }}
                        </div>
                        @if ($g->caption)
                            <div class="text-sm text-gray-500 line-clamp-2">{{ $g->caption }}</div>
                        @endif
                        <div class="text-xs text-gray-400">
                            {{ $g->taken_at ? $g->taken_at->format('d M Y') : 'Tanggal tidak diketahui' }}
                            • {{ $g->is_published ? 'Published' : 'Draft' }}
                        </div>

                        <div class="pt-2 flex items-center justify-between">
                            <a href="{{ route('galleries.edit', $g) }}"
                                class="rounded-lg px-3 py-1.5 text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/20">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('galleries.destroy', $g) }}"
                                onsubmit="return confirm('Hapus foto ini?')">
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
                <div class="sm:col-span-2 lg:col-span-3 xl:col-span-4">
                    <div
                        class="rounded-2xl border border-gray-200 dark:border-gray-700 p-10 text-center text-gray-500 dark:text-gray-400">
                        Belum ada dokumentasi.
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if ($galleries->hasPages())
            <div>{{ $galleries->onEachSide(1)->links() }}</div>
        @endif
    </div>
</x-app-layout>
