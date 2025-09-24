<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100">Target Penjualan Saya</h2>
    </x-slot>

    <div class="p-6 space-y-6">
        {{-- FILTER --}}
        <form method="GET"
            class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-4">
            <div class="grid gap-3 sm:grid-cols-4">
                <div class="sm:col-span-2">
                    <label class="text-sm text-gray-600 dark:text-gray-300">Cari Target</label>
                    <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Ketik judul target…"
                        class="mt-1 w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700" />
                </div>

                <div>
                    <label class="text-sm text-gray-600 dark:text-gray-300">Periode</label>
                    <select name="period"
                        class="mt-1 w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700">
                        <option value="">— Semua —</option>
                        <option value="monthly" @selected(($period ?? '') === 'monthly')>Bulanan</option>
                        <option value="quarterly" @selected(($period ?? '') === 'quarterly')>Quarterly</option>
                        <option value="annual" @selected(($period ?? '') === 'annual')>Tahunan</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="text-sm text-gray-600 dark:text-gray-300">Dari</label>
                        <input type="date" name="from" value="{{ optional($from)->format('Y-m-d') }}"
                            class="mt-1 w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700" />
                    </div>
                    <div>
                        <label class="text-sm text-gray-600 dark:text-gray-300">Sampai</label>
                        <input type="date" name="to" value="{{ optional($to)->format('Y-m-d') }}"
                            class="mt-1 w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700" />
                    </div>
                </div>
            </div>
            <div class="mt-3 flex justify-end">
                <button class="inline-flex rounded-xl bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">
                    Terapkan
                </button>
            </div>
        </form>

        {{-- LIST TARGET --}}
        <div class="grid gap-6 lg:grid-cols-3">
            @foreach ($targets as $t)
                @php
                    $pg = $progress[$t->id] ?? ['premi' => 0, 'cases' => 0, 'premi_pct' => null, 'case_pct' => null];
                    $premiPct = $pg['premi_pct']; // bisa null
                    $casePct = $pg['case_pct']; // bisa null
                    $status = $t->end_date && $t->end_date->isPast() ? 'Selesai' : 'Ongoing';
                @endphp

                <article
                    class="overflow-hidden rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow">
                    {{-- HEADER + BADGE PERIODE --}}
                    <div class="relative h-36 bg-gradient-to-br from-indigo-600 to-blue-500">
                        <div class="absolute top-3 right-3 flex items-center gap-2">
                            <span
                                class="text-xs rounded-full px-2.5 py-1 bg-white/90 text-gray-700">{{ ucfirst($status) }}</span>
                            <span class="text-xs rounded-full px-2.5 py-1 bg-white/90 text-indigo-700">
                                {{ ucfirst($t->period ?? '-') }}
                            </span>
                        </div>
                    </div>

                    <div class="p-5 space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            {{ $t->title ?: 'Target Penjualan' }}</h3>
                        <div class="text-xs text-gray-500">
                            {{ $t->start_date ? $t->start_date->format('d M Y') : '—' }} —
                            {{ $t->end_date ? $t->end_date->format('d M Y') : 'Ongoing' }}
                        </div>

                        <div class="grid md:grid-cols-2 gap-3">
                            <div class="rounded-xl bg-gray-50 dark:bg-gray-800 p-3">
                                <div class="text-gray-500 text-xs">Produk</div>
                                <div class="font-medium">{{ $t->product->name ?? '—' }}</div>
                            </div>
                            <div class="rounded-xl bg-gray-50 dark:bg-gray-800 p-3">
                                <div class="text-gray-500 text-xs">Agent</div>
                                <div class="font-medium">{{ $t->agent->name ?? '—' }}</div>
                            </div>
                            <div class="rounded-xl bg-gray-50 dark:bg-gray-800 p-3">
                                <div class="text-gray-500 text-xs">Target Premi</div>
                                <div class="font-semibold">Rp {{ number_format($t->target_premium, 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="rounded-xl bg-gray-50 dark:bg-gray-800 p-3">
                                <div class="text-gray-500 text-xs">Target Case</div>
                                <div class="font-semibold">{{ number_format($t->target_case, 0, ',', '.') }}</div>
                            </div>
                        </div>

                        {{-- PIE / DONUT PROGRESS --}}
                        <div class="grid md:grid-cols-2 gap-4">
                            {{-- Premi --}}
                            <div
                                class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-3">
                                <div class="text-xs text-gray-500 mb-1">Premi</div>
                                <div class="flex items-center gap-3">
                                    <div class="w-20 h-20">
                                        <canvas id="donut-premi-{{ $t->id }}" width="80" height="80"
                                            data-percent="{{ $premiPct ?? 0 }}"></canvas>
                                    </div>
                                    <div>
                                        <div class="text-lg font-semibold">
                                            {{ $premiPct !== null ? $premiPct . '%' : '—' }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            Rp {{ number_format($pg['premi'], 0, ',', '.') }}
                                            / Rp {{ number_format($t->target_premium, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Case --}}
                            <div
                                class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-3">
                                <div class="text-xs text-gray-500 mb-1">Case</div>
                                <div class="flex items-center gap-3">
                                    <div class="w-20 h-20">
                                        <canvas id="donut-case-{{ $t->id }}" width="80" height="80"
                                            data-percent="{{ $casePct ?? 0 }}"></canvas>
                                    </div>
                                    <div>
                                        <div class="text-lg font-semibold">
                                            {{ $casePct !== null ? $casePct . '%' : '—' }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ number_format($pg['cases'], 0, ',', '.') }}
                                            / {{ number_format($t->target_case, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $targets->onEachSide(1)->links() }}
        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // render semua canvas donut yang ada di halaman
            document.querySelectorAll('canvas[id^="donut-"]').forEach(cv => {
                const pct = Math.max(0, Math.min(100, parseFloat(cv.dataset.percent || '0')));
                const remain = 100 - pct;

                new Chart(cv.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Pencapaian', 'Sisa'],
                        datasets: [{
                            data: [pct, remain],
                            // biarkan warna default Chart.js atau atur kalau mau
                        }]
                    },
                    options: {
                        responsive: false,
                        cutout: '70%',
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: (ctx) => `${ctx.label}: ${ctx.parsed}%`
                                }
                            }
                        }
                    }
                });
            });
        });
    </script>
</x-app-layout>
