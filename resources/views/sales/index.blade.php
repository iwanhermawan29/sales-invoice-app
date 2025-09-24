{{-- resources/views/sales/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100">Data Penjualan</h2>

            <div class="flex items-center gap-2">
                <a href="{{ route('sales.export.excel', request()->query()) }}"
                    class="inline-flex items-center gap-2 rounded-xl border border-gray-300 dark:border-gray-700 px-3 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M4 6h16M4 10h7" />
                    </svg>
                    Export Excel
                </a>
                <a href="{{ route('sales.export.pdf', request()->query()) }}"
                    class="inline-flex items-center gap-2 rounded-xl border border-gray-300 dark:border-gray-700 px-3 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M6 2a2 2 0 00-2 2v16a2 2 0 002 2h7a1 1 0 001-1v-5h4a1 1 0 001-1V9l-5-5H6z" />
                    </svg>
                    Export PDF
                </a>
                <a href="{{ route('sales.create') }}"
                    class="inline-flex items-center gap-2 rounded-xl px-4 py-2 bg-blue-600 text-white hover:bg-blue-700 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 5v14m-7-7h14" />
                    </svg>
                    Tambah
                </a>
            </div>
        </div>
    </x-slot>

    <div class="p-6 space-y-4">
        @if (session('error'))
            <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-rose-800">{{ session('error') }}
            </div>
        @endif
        @if (session('status'))
            <div id="toast-status" class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-800">
                {{ session('status') }}</div>
        @endif

        {{-- Filter Bar --}}
        <form id="filter-form" method="GET" class="grid gap-3 md:grid-cols-5">
            <div class="md:col-span-2">
                <label class="text-sm text-gray-600 dark:text-gray-300">Cari Nasabah</label>
                <input id="q-input" name="q" type="text" value="{{ $q ?? '' }}"
                    placeholder="Ketik nama nasabah…"
                    class="mt-1 w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700" />
            </div>

            <div>
                <label class="text-sm text-gray-600 dark:text-gray-300">Produk</label>
                <select name="product_id"
                    class="mt-1 w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700">
                    <option value="">— Semua —</option>
                    @foreach ($products as $p)
                        <option value="{{ $p->id }}" @selected(($productId ?? null) == $p->id)>{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm text-gray-600 dark:text-gray-300">Bulan</label>
                <input type="month" name="month" value="{{ $month ?? '' }}"
                    class="mt-1 w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700" />
            </div>

            <div class="flex items-end gap-2">
                <button
                    class="inline-flex w-full justify-center rounded-xl bg-gray-900 px-4 py-2 text-white hover:bg-black">Terapkan</button>
                <a href="{{ route('sales.index') }}"
                    class="inline-flex justify-center rounded-xl border px-4 py-2 text-gray-700 dark:text-gray-200">Reset</a>
            </div>
        </form>

        @php
            function sort_link($label, $key, $currentSort, $currentDir)
            {
                $nextDir = $currentSort === $key && strtolower($currentDir) === 'asc' ? 'desc' : 'asc';
                $icon = $currentSort === $key ? (strtolower($currentDir) === 'asc' ? '↑' : '↓') : '';
                $query = array_merge(request()->query(), ['sort' => $key, 'dir' => $nextDir]);
                $url = request()->url() . '?' . http_build_query($query);
                return '<a href="' .
                    $url .
                    '" class="inline-flex items-center gap-1 hover:underline">' .
                    $label .
                    ' <span class="opacity-60">' .
                    $icon .
                    '</span></a>';
            }
            $pageTotal = $sales->sum('premium');
        @endphp

        {{-- Card Tabel --}}
        <div
            class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm overflow-hidden">
            <div
                class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 border-b border-gray-100 dark:border-gray-800 px-4 py-3">
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    Menampilkan
                    <span class="font-semibold">{{ $sales->firstItem() ?? 0 }}</span>–<span
                        class="font-semibold">{{ $sales->lastItem() ?? 0 }}</span>
                    dari <span class="font-semibold">{{ $sales->total() }}</span> data
                </p>
                <p class="text-sm text-gray-700 dark:text-gray-200">
                    Total premi halaman: <span
                        class="font-semibold">{{ number_format($pageTotal, 2, ',', '.') }}</span>
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="sticky top-0 bg-gray-50 dark:bg-gray-800/70 backdrop-blur">
                        <tr class="text-left text-gray-600 dark:text-gray-300">
                            <th class="px-4 py-3">{!! sort_link('Tanggal', 'sale_date', $sort, $dir) !!}</th>
                            <th class="px-4 py-3">{!! sort_link('Nasabah', 'customer_name', $sort, $dir) !!}</th>
                            <th class="px-4 py-3">Produk</th>
                            <th class="px-4 py-3">{!! sort_link('Case', 'case_level', $sort, $dir) !!}</th>
                            <th class="px-4 py-3">{!! sort_link('Status', 'status', $sort, $dir) !!}</th>
                            <th class="px-4 py-3 text-right">{!! sort_link('Premi', 'premium', $sort, $dir) !!}</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse($sales as $s)
                            <tr class="hover:bg-gray-50/70 dark:hover:bg-gray-800/50">
                                <td class="px-4 py-3 whitespace-nowrap">{{ $s->sale_date->format('Y-m-d') }}</td>
                                <td class="px-4 py-3">{{ $s->customer_name }}</td>
                                <td class="px-4 py-3">{{ $s->product->name ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    @php $colors=[1=>'bg-blue-100 text-blue-700',2=>'bg-amber-100 text-amber-700',3=>'bg-rose-100 text-rose-700']; @endphp
                                    <span
                                        class="inline-flex rounded-full px-3 py-1 text-xs font-medium {{ $colors[$s->case_level] ?? 'bg-gray-100 text-gray-700' }}">
                                        Case {{ $s->case_level }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="inline-flex rounded-full px-3 py-1 text-xs font-medium {{ $s->status_color }}">
                                        {{ $s->status_label }}
                                    </span>
                                    @if ($s->approval_note)
                                        <div class="text-xs text-gray-500 mt-1">Catatan: {{ $s->approval_note }}</div>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right font-semibold">
                                    {{ number_format($s->premium, 2, ',', '.') }}</td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('sales.edit', $s) }}"
                                        class="inline-flex items-center rounded-lg px-3 py-1 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20">
                                        Edit
                                    </a>
                                    <form action="{{ route('sales.destroy', $s) }}" method="POST"
                                        class="inline js-delete-form">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center rounded-lg px-3 py-1 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/20">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-10 text-center text-gray-500 dark:text-gray-400">
                                    <div class="mx-auto w-full max-w-sm">
                                        <div class="mb-3 text-lg font-semibold">Belum ada data penjualan</div>
                                        <p class="mb-4 text-sm">Coba ubah filter/pencarian atau tambahkan data baru.</p>
                                        <a href="{{ route('sales.create') }}"
                                            class="rounded-xl bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Tambah
                                            Penjualan</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($sales->hasPages())
                <div class="border-t border-gray-100 dark:border-gray-800 px-4 py-3">
                    {{ $sales->onEachSide(1)->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- SweetAlert2 + debounce --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        (function() {
            const t = document.getElementById('toast-status');
            if (t) setTimeout(() => t.remove(), 4000);
        })();

        document.addEventListener('click', function(e) {
            const form = e.target.closest('.js-delete-form');
            if (form && e.target.matches('button[type="submit"], .js-delete-form button')) {
                e.preventDefault();
                Swal.fire({
                    title: 'Hapus data ini?',
                    text: 'Tindakan ini tidak dapat dibatalkan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus',
                    cancelButtonText: 'Batal'
                }).then((r) => {
                    if (r.isConfirmed) form.submit();
                });
            }
        });

        (function() {
            const input = document.getElementById('q-input');
            const form = document.getElementById('filter-form');
            if (!input || !form) return;
            let timer = null;
            input.addEventListener('input', function() {
                if (timer) clearTimeout(timer);
                timer = setTimeout(() => form.submit(), 500);
            });
        })();
    </script>
</x-app-layout>
