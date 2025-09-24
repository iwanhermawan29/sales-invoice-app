{{-- resources/views/dashboard/agent.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                {{ __('Dashboard Penjualan (Agent)') }}
            </h2>
            <div class="text-sm text-gray-500 dark:text-gray-400">
                {{ $start->format('d M Y') }} — {{ $end->format('d M Y') }}
            </div>
        </div>
    </x-slot>

    <div class="p-6 space-y-6">
        {{-- Filter Periode --}}
        <form method="GET"
            class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-4">
            <div class="grid gap-3 sm:grid-cols-3">
                <div>
                    <label class="text-sm text-gray-600 dark:text-gray-300">Periode</label>
                    <select name="period"
                        class="mt-1 w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700">
                        <option value="month" @selected($period === 'month')>Bulanan</option>
                        <option value="quarter" @selected($period === 'quarter')>Kuartal</option>
                        <option value="year" @selected($period === 'year')>Tahunan</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm text-gray-600 dark:text-gray-300">Tanggal Acuan</label>
                    <input type="date" name="date" value="{{ $base->format('Y-m-d') }}"
                        class="mt-1 w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700" />
                </div>
                <div class="flex items-end">
                    <button
                        class="inline-flex w-full justify-center rounded-xl bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">
                        Terapkan
                    </button>
                </div>
            </div>
        </form>

        {{-- Summary Cards --}}
        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-2xl bg-gradient-to-br from-indigo-600 to-blue-500 text-white p-5 shadow">
                <div class="text-sm opacity-80">Total Premi</div>
                <div class="mt-1 text-2xl font-semibold">
                    Rp {{ number_format($summary->total_premium, 0, ',', '.') }}
                </div>
            </div>
            <div
                class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-5 shadow-sm">
                <div class="text-sm text-gray-500">Total Case</div>
                <div class="mt-1 text-2xl font-semibold">{{ $summary->total_cases }}</div>
            </div>
            <div
                class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-5 shadow-sm">
                <div class="text-sm text-gray-500">Produk Aktif</div>
                <div class="mt-1 text-2xl font-semibold">{{ $summary->total_products }}</div>
            </div>
        </div>

        {{-- Charts --}}
        <div class="grid gap-6 lg:grid-cols-3">
            <div
                class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-5 shadow-sm lg:col-span-2">
                <div class="mb-3 font-semibold">Tren Premi</div>
                <canvas id="chartTrend" height="120"></canvas>
            </div>
            <div
                class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-5 shadow-sm">
                <div class="mb-3 font-semibold">Distribusi Case</div>
                <canvas id="chartCase" height="120"></canvas>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <div
                class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-5 shadow-sm">
                <div class="mb-3 font-semibold">Top Produk (Premi)</div>
                <canvas id="chartProduct" height="140"></canvas>
            </div>
            <div
                class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-5 shadow-sm">
                <div class="mb-3 font-semibold">Top Kota (Premi)</div>
                <canvas id="chartCity" height="140"></canvas>
            </div>
        </div>

        {{-- Leaderboards --}}
        <div class="grid gap-6 lg:grid-cols-3">
            <div
                class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-5 shadow-sm">
                <div class="mb-3 font-semibold">Leaderboard Nasional (Premi)</div>
                <ol class="space-y-2">
                    @foreach ($leaderAgents as $i => $a)
                        <li class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span
                                    class="inline-flex h-6 w-6 items-center justify-center rounded-full
                                    {{ $i === 0 ? 'bg-yellow-100 text-yellow-700' : ($i === 1 ? 'bg-gray-200 text-gray-700' : ($i === 2 ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-700')) }}">
                                    {{ $i + 1 }}
                                </span>
                                <div>
                                    <div class="font-medium">{{ $a->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $a->city }} • {{ $a->cases }} case
                                    </div>
                                </div>
                            </div>
                            <div class="font-semibold">Rp {{ number_format($a->total, 0, ',', '.') }}</div>
                        </li>
                    @endforeach
                </ol>
            </div>

            <div
                class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-5 shadow-sm">
                <div class="mb-3 font-semibold">Leaderboard Kota (Premi)</div>
                <ol class="space-y-2">
                    @foreach ($leaderCities as $i => $c)
                        <li class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span
                                    class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-indigo-100 text-indigo-700">
                                    {{ $i + 1 }}
                                </span>
                                <div>
                                    <div class="font-medium">{{ $c->city }}</div>
                                    <div class="text-xs text-gray-500">{{ $c->cases }} case</div>
                                </div>
                            </div>
                            <div class="font-semibold">Rp {{ number_format($c->total, 0, ',', '.') }}</div>
                        </li>
                    @endforeach
                </ol>
            </div>

            <div
                class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-5 shadow-sm">
                <div class="mb-3 font-semibold">Leaderboard Case (Jumlah)</div>
                <ol class="space-y-2">
                    @foreach ($leaderCases as $i => $lc)
                        <li class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span
                                    class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-blue-100 text-blue-700">
                                    {{ $i + 1 }}
                                </span>
                                <div>
                                    <div class="font-medium">{{ $lc->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $lc->cases }} case</div>
                                </div>
                            </div>
                            <div class="font-semibold">Rp {{ number_format($lc->total, 0, ',', '.') }}</div>
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>
    </div>

    {{-- Chart.js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Data dari server (JSON encode)
        const tsLabels = @json($tsLabels);
        const tsValues = @json($tsValues);
        const byProduct = @json($byProduct);
        const byCity = @json($byCity);
        const byCase = @json($byCase);

        // Tren premi (line)
        new Chart(document.getElementById('chartTrend'), {
            type: 'line',
            data: {
                labels: tsLabels,
                datasets: [{
                    label: 'Premi',
                    data: tsValues,
                    tension: .35,
                    fill: true,
                    backgroundColor: 'rgba(79,70,229,0.12)',
                    borderColor: 'rgba(79,70,229,1)',
                    pointRadius: 3
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        ticks: {
                            callback: v => 'Rp ' + new Intl.NumberFormat('id-ID').format(v)
                        }
                    }
                }
            }
        });

        // Case (donut)
        new Chart(document.getElementById('chartCase'), {
            type: 'doughnut',
            data: {
                labels: byCase.map(c => 'Case ' + c.case_level),
                datasets: [{
                    data: byCase.map(c => c.cnt),
                    backgroundColor: ['#6366F1', '#F59E0B', '#EF4444'],
                }]
            },
            options: {
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Produk (bar)
        new Chart(document.getElementById('chartProduct'), {
            type: 'bar',
            data: {
                labels: byProduct.map(p => p.product),
                datasets: [{
                    label: 'Premi',
                    data: byProduct.map(p => p.total),
                    backgroundColor: 'rgba(99,102,241,.5)',
                    borderColor: 'rgba(99,102,241,1)',
                    borderWidth: 1,
                }]
            },
            options: {
                indexAxis: 'y',
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            callback: v => 'Rp ' + new Intl.NumberFormat('id-ID').format(v)
                        }
                    }
                }
            }
        });

        // Kota (bar)
        new Chart(document.getElementById('chartCity'), {
            type: 'bar',
            data: {
                labels: byCity.map(c => c.city),
                datasets: [{
                    label: 'Premi',
                    data: byCity.map(c => c.total),
                    backgroundColor: 'rgba(59,130,246,.5)',
                    borderColor: 'rgba(59,130,246,1)',
                    borderWidth: 1,
                }]
            },
            options: {
                indexAxis: 'y',
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            callback: v => 'Rp ' + new Intl.NumberFormat('id-ID').format(v)
                        }
                    }
                }
            }
        });
    </script>
</x-app-layout>
