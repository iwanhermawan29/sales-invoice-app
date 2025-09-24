<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100">
                Target Penjualan
            </h2>
            <a href="{{ route('targets-penjualan.create') }}"
                class="inline-flex items-center gap-2 rounded-xl px-4 py-2 bg-indigo-600 text-white hover:bg-indigo-700">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 5v14m-7-7h14" />
                </svg>
                Tambah Target
            </a>
        </div>
    </x-slot>

    <div class="p-6 space-y-5">
        {{-- Flash success --}}
        @if (session('success'))
            <div id="toast-success" class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        {{-- Filter --}}
        <form method="GET"
            class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-4">
            <div class="grid gap-3 md:grid-cols-6">
                <div class="md:col-span-2">
                    <label class="text-sm text-gray-600 dark:text-gray-300">Cari (judul/catatan)</label>
                    <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Ketik kata kunci…"
                        class="mt-1 w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700" />
                </div>

                <div>
                    <label class="text-sm text-gray-600 dark:text-gray-300">Agent</label>
                    <select name="agent_id"
                        class="mt-1 w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700">
                        <option value="">— Semua —</option>
                        @foreach ($agents as $a)
                            <option value="{{ $a->id }}" @selected(($agentId ?? null) == $a->id)>{{ $a->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-sm text-gray-600 dark:text-gray-300">Produk</label>
                    <select name="product_id"
                        class="mt-1 w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700">
                        <option value="">— Semua —</option>
                        @foreach ($products as $p)
                            <option value="{{ $p->id }}" @selected(($productId ?? null) == $p->id)>{{ $p->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-sm text-gray-600 dark:text-gray-300">Periode</label>
                    <select name="period"
                        class="mt-1 w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700">
                        <option value="">— Semua —</option>
                        <option value="monthly" @selected(($period ?? '') === 'monthly')>Monthly</option>
                        <option value="quarterly" @selected(($period ?? '') === 'quarterly')>Quarterly</option>
                        <option value="annual" @selected(($period ?? '') === 'annual')>Annual</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="text-sm text-gray-600 dark:text-gray-300">Rentang Tanggal</label>
                    <div class="mt-1 grid grid-cols-2 gap-2">
                        <input type="date" name="from" value="{{ $from ?? '' }}"
                            class="w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700" />
                        <input type="date" name="to" value="{{ $to ?? '' }}"
                            class="w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700" />
                    </div>
                </div>
            </div>

            <div class="mt-3 flex items-center justify-end gap-2">
                <a href="{{ route('targets-penjualan.index') }}"
                    class="inline-flex rounded-xl border px-4 py-2">Reset</a>
                <button class="inline-flex rounded-xl bg-gray-900 px-4 py-2 text-white hover:bg-black">Terapkan</button>
            </div>
        </form>

        {{-- Table --}}
        <div
            class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm overflow-hidden">
            <div
                class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300 border-b border-gray-100 dark:border-gray-800">
                Menampilkan <b>{{ $targets->firstItem() ?? 0 }}</b>–<b>{{ $targets->lastItem() ?? 0 }}</b>
                dari <b>{{ $targets->total() }}</b> target
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-800/60 text-gray-600 dark:text-gray-300">
                        <tr>
                            <th class="px-4 py-3 text-left">Judul</th>
                            <th class="px-4 py-3 text-left">Agent</th>
                            <th class="px-4 py-3 text-left">Produk</th>
                            <th class="px-4 py-3 text-left">Periode</th>
                            <th class="px-4 py-3 text-left">Tanggal</th>
                            <th class="px-4 py-3 text-right">Target Premi</th>
                            <th class="px-4 py-3 text-right">Target Case</th>
                            <th class="px-4 py-3 text-center">Aktif</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse ($targets as $t)
                            @php
                                $badgeMap = [
                                    'monthly' => 'bg-indigo-100 text-indigo-700',
                                    'quarterly' => 'bg-amber-100 text-amber-700',
                                    'annual' => 'bg-green-100 text-green-700',
                                ];
                            @endphp
                            <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-800/50">
                                <td class="px-4 py-3">
                                    <div class="font-medium text-gray-900 dark:text-gray-100">{{ $t->title ?? '—' }}
                                    </div>
                                    @if ($t->notes)
                                        <div class="text-xs text-gray-500 line-clamp-1">{{ $t->notes }}</div>
                                    @endif
                                </td>
                                <td class="px-4 py-3">{{ $t->agent->name ?? '—' }}</td>
                                <td class="px-4 py-3">{{ $t->product->name ?? 'Semua Produk' }}</td>
                                <td class="px-4 py-3">
                                    <span
                                        class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium {{ $badgeMap[$t->period] ?? 'bg-gray-100 text-gray-700' }}">
                                        {{ ucfirst($t->period) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    {{ optional($t->start_date)->format('d M Y') }} —
                                    {{ optional($t->end_date)->format('d M Y') }}
                                </td>
                                <td class="px-4 py-3 text-right font-semibold">
                                    {{ number_format($t->target_premium, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-right font-semibold">
                                    {{ number_format($t->target_case, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if ($t->is_active)
                                        <span
                                            class="inline-flex items-center gap-1 rounded-full bg-emerald-100 text-emerald-700 px-2 py-0.5 text-xs">
                                            ● Aktif
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1 rounded-full bg-gray-100 text-gray-700 px-2 py-0.5 text-xs">
                                            ● Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('targets-penjualan.edit', $t) }}"
                                        class="inline-flex items-center rounded-lg px-3 py-1 text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/20">
                                        Edit
                                    </a>
                                    <form action="{{ route('targets-penjualan.destroy', $t) }}" method="POST"
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
                                <td colspan="9" class="px-4 py-10 text-center text-gray-500 dark:text-gray-400">
                                    Belum ada data target.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($targets->hasPages())
                <div class="border-t border-gray-100 dark:border-gray-800 px-4 py-3">
                    {{ $targets->onEachSide(1)->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Auto-hide toast success
        (function() {
            const t = document.getElementById('toast-success');
            if (t) setTimeout(() => t.remove(), 3500);
        })();

        // Konfirmasi hapus
        document.addEventListener('click', function(e) {
            const form = e.target.closest('.js-delete-form');
            if (form && e.target.matches('button[type="submit"], .js-delete-form button')) {
                e.preventDefault();
                Swal.fire({
                    title: 'Hapus target ini?',
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
    </script>
</x-app-layout>
