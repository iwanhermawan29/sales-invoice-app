<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100">Head • Reports</h2>

            <div class="flex items-center gap-2">
                <a href="{{ route('head.reports.excel', request()->query()) }}"
                    class="inline-flex items-center gap-2 rounded-xl border px-3 py-2
                          text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M4 6h16M4 10h7" />
                    </svg>
                    Export Excel
                </a>
                <a href="{{ route('head.reports.print', request()->query()) }}"
                    class="inline-flex items-center gap-2 rounded-xl border px-3 py-2
                          text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M6 2a2 2 0 00-2 2v16a2 2 0 002 2h7a1 1 0 001-1v-5h4a1 1 0 001-1V9l-5-5H6z" />
                    </svg>
                    Print / PDF
                </a>
            </div>
        </div>
    </x-slot>

    <div class="p-6 space-y-5">
        {{-- Filter --}}
        <form method="GET"
            class="grid gap-3 md:grid-cols-5 rounded-2xl border p-4
                                 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900">
            <div>
                <label class="text-sm text-gray-600 dark:text-gray-300">Cari Nasabah</label>
                <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Nama nasabah…"
                    class="mt-1 w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700">
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
                <label class="text-sm text-gray-600 dark:text-gray-300">Kota</label>
                <select name="kota"
                    class="mt-1 w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700">
                    <option value="">— Semua —</option>
                    @foreach ($cities as $c)
                        <option value="{{ $c }}" @selected(($kota ?? '') === $c)>{{ $c }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-sm text-gray-600 dark:text-gray-300">Agency / Team</label>
                <select name="agency"
                    class="mt-1 w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700">
                    <option value="">— Semua —</option>
                    @foreach ($agencies as $a)
                        <option value="{{ $a }}" @selected(($agency ?? '') === $a)>{{ $a }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-sm text-gray-600 dark:text-gray-300">Bulan</label>
                <input type="month" name="month" value="{{ $month ?? '' }}"
                    class="mt-1 w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700">
            </div>
            <div class="md:col-span-5 flex items-end gap-2">
                <button class="inline-flex rounded-xl bg-gray-900 px-4 py-2 text-white hover:bg-black">Terapkan</button>
                <a href="{{ route('head.reports.index') }}"
                    class="inline-flex rounded-xl border px-4 py-2 text-gray-700 dark:text-gray-200">Reset</a>
            </div>
        </form>

        {{-- Table --}}
        <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 overflow-x-auto">
            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 dark:border-gray-800">
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    Menampilkan <b>{{ $sales->firstItem() ?? 0 }}</b>–<b>{{ $sales->lastItem() ?? 0 }}</b> dari
                    <b>{{ $sales->total() }}</b> data.
                </p>
                <p class="text-sm text-gray-700 dark:text-gray-200">
                    Total premi halaman: <b>{{ number_format($pageTotal, 2, ',', '.') }}</b>
                </p>
            </div>

            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800/70 text-gray-600 dark:text-gray-300">
                    <tr class="text-left">
                        <th class="px-4 py-2">Tanggal</th>
                        <th class="px-4 py-2">Nasabah</th>
                        <th class="px-4 py-2">Produk</th>
                        <th class="px-4 py-2">Case</th>
                        <th class="px-4 py-2">Premi</th>
                        <th class="px-4 py-2">Agent</th>
                        <th class="px-4 py-2">Kota</th>
                        <th class="px-4 py-2">Agency</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($sales as $s)
                        <tr>
                            <td class="px-4 py-2 whitespace-nowrap">{{ optional($s->sale_date)->format('Y-m-d') }}</td>
                            <td class="px-4 py-2">{{ $s->customer_name }}</td>
                            <td class="px-4 py-2">{{ $s->product_name }}</td>
                            <td class="px-4 py-2">Case {{ $s->case_level }}</td>
                            <td class="px-4 py-2 font-semibold text-right">
                                {{ number_format($s->premium, 2, ',', '.') }}</td>
                            <td class="px-4 py-2">{{ $s->agent_name }}</td>
                            <td class="px-4 py-2">{{ $s->kota }}</td>
                            <td class="px-4 py-2">{{ $s->agency_name }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-10 text-center text-gray-500">Tidak ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($sales->hasPages())
                <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-800">
                    {{ $sales->onEachSide(1)->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
