<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100">Dashboard Admin</h2>
            <div class="text-sm text-gray-500 dark:text-gray-400">
                {{ $start?->format('d M Y') }} — {{ $end?->format('d M Y') }}
            </div>
        </div>
    </x-slot>

    <div class="p-6 space-y-6">
        {{-- Filter --}}
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
                    <input type="date" name="date" value="{{ $base?->format('Y-m-d') ?? now()->format('Y-m-d') }}"
                        class="mt-1 w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700">
                </div>
                <div class="flex items-end">
                    <button
                        class="inline-flex w-full justify-center rounded-xl bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">
                        Terapkan
                    </button>
                </div>
            </div>
        </form>

        {{-- Ringkasan --}}
        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-2xl bg-gradient-to-br from-indigo-600 to-blue-500 text-white p-5 shadow">
                <div class="text-sm opacity-80">Total Premi</div>
                <div class="mt-1 text-2xl font-semibold">Rp {{ number_format($summary->premium ?? 0, 0, ',', '.') }}
                </div>
            </div>
            <div
                class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-5 shadow-sm">
                <div class="text-sm text-gray-500">Total Case</div>
                <div class="mt-1 text-2xl font-semibold">{{ $summary->cases ?? 0 }}</div>
            </div>
            <div
                class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-5 shadow-sm">
                <div class="text-sm text-gray-500">Periode</div>
                <div class="mt-1 text-lg font-medium">{{ ucfirst($period) }}</div>
            </div>
        </div>

        {{-- Charts --}}
        <div class="grid gap-6 lg:grid-cols-3">
            <div
                class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-5 shadow-sm">
                <div class="mb-3 font-semibold">Top 3 Agent (Premi)</div>
                <canvas id="chartAgents" height="160"></canvas>
            </div>
            <div
                class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-5 shadow-sm">
                <div class="mb-3 font-semibold">Top Kota (Premi)</div>
                <canvas id="chartCities" height="160"></canvas>
            </div>
            <div
                class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-5 shadow-sm">
                <div class="mb-3 font-semibold">Top Agency (Premi)</div>
                <canvas id="chartAgencies" height="160"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const topAgents = @json($topAgents);
        const topCities = @json($topCities);
        const topAgencies = @json($topAgencies);

        const fmtRp = v => 'Rp ' + new Intl.NumberFormat('id-ID').format(v);

        // Top 3 Agent
        new Chart(document.getElementById('chartAgents'), {
            type: 'bar',
            data: {
                labels: topAgents.map(a => a.name),
                datasets: [{
                    label: 'Premi',
                    data: topAgents.map(a => Number(a.total)),
                    backgroundColor: 'rgba(99,102,241,.6)',
                    borderColor: 'rgba(99,102,241,1)',
                    borderWidth: 1
                }]
            },
            options: {
                // HAPUS indexAxis: 'y' agar jadi vertikal
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: { // format Rupiah di sumbu Y (nilai)
                        ticks: {
                            callback: v => 'Rp ' + new Intl.NumberFormat('id-ID').format(v)
                        },
                        beginAtZero: true
                    },
                    x: { // rapikan label panjang
                        ticks: {
                            maxRotation: 45,
                            minRotation: 0,
                            autoSkip: true
                        }
                    }
                }
            }
        });
        // Top Kota
        new Chart(document.getElementById('chartCities'), {
            type: 'bar',
            data: {
                labels: topCities.map(c => c.city),
                datasets: [{
                    label: 'Premi',
                    data: topCities.map(c => Number(c.total)),
                    backgroundColor: 'rgba(59,130,246,.6)',
                    borderColor: 'rgba(59,130,246,1)',
                    borderWidth: 1
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        ticks: {
                            callback: v => 'Rp ' + new Intl.NumberFormat('id-ID').format(v)
                        },
                        beginAtZero: true
                    },
                    x: {
                        ticks: {
                            maxRotation: 45,
                            autoSkip: true
                        }
                    }
                }
            }
        });


        // Top Agency
        new Chart(document.getElementById('chartAgencies'), {
            type: 'bar',
            data: {
                labels: topAgencies.map(a => a.agency),
                datasets: [{
                    label: 'Premi',
                    data: topAgencies.map(a => Number(a.total)),
                    backgroundColor: 'rgba(147,51,234,.6)',
                    borderColor: 'rgba(147,51,234,1)',
                    borderWidth: 1
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
                            callback: fmtRp
                        }
                    }
                }
            }
        });
    </script>
</x-app-layout>
