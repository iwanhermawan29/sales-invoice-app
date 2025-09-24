<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                Dashboard Head
            </h2>
            <div class="text-sm text-gray-500 dark:text-gray-400">
                {{ $start->format('d M Y') }} — {{ $end->format('d M Y') }}
            </div>
        </div>
    </x-slot>

    <div class="p-6 space-y-6">
        {{-- Filter Global --}}
        <form method="GET"
            class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-4">
            <div class="grid gap-3 md:grid-cols-6">
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
                        class="mt-1 w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700">
                </div>
                <div>
                    <label class="text-sm text-gray-600 dark:text-gray-300">Kota</label>
                    <select name="kota"
                        class="mt-1 w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700">
                        <option value="">— Semua —</option>
                        @foreach ($cities as $c)
                            <option value="{{ $c }}" @selected(($kota ?? '') === $c)>{{ $c }}
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
                <div class="md:col-span-2">
                    <label class="text-sm text-gray-600 dark:text-gray-300">Agency / Team</label>
                    <select name="agency"
                        class="mt-1 w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700">
                        <option value="">— Semua —</option>
                        @foreach ($agencies as $a)
                            <option value="{{ $a }}" @selected(($agency ?? '') === $a)>{{ $a }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-3 flex justify-end gap-2">
                <button class="inline-flex rounded-xl bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">
                    Terapkan
                </button>
                <a href="{{ route('dashboard') ?? url('/dashboard') }}"
                    class="inline-flex rounded-xl border px-4 py-2">Reset</a>
            </div>
        </form>

        {{-- KPI Cards --}}
        <div class="grid gap-4 md:grid-cols-4">
            <div class="rounded-2xl bg-gradient-to-br from-indigo-600 to-blue-500 text-white p-5 shadow">
                <div class="text-sm opacity-80">Total Premi</div>
                <div class="mt-1 text-2xl font-semibold">
                    Rp {{ number_format($summary->total_premium ?? 0, 0, ',', '.') }}
                </div>
            </div>
            <div
                class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-5 shadow-sm">
                <div class="text-sm text-gray-500">Total Case</div>
                <div class="mt-1 text-2xl font-semibold">{{ $summary->total_cases ?? 0 }}</div>
            </div>
            <div
                class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-5 shadow-sm">
                <div class="text-sm text-gray-500">Avg Premi/Case</div>
                <div class="mt-1 text-2xl font-semibold">Rp {{ number_format($avgPremium, 0, ',', '.') }}</div>
            </div>
            <div
                class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-5 shadow-sm">
                <div class="text-sm text-gray-500">Dimensi Aktif</div>
                <div class="mt-1 text-sm">
                    <div>Kota: <b>{{ $kota ?: 'Semua' }}</b></div>
                    <div>Produk: <b>
                            @php $pn = optional($products->firstWhere('id', $productId))->name; @endphp
                            {{ $pn ?: 'Semua' }}
                        </b></div>
                    <div>Agency: <b>{{ $agency ?: 'Semua' }}</b></div>
                </div>
            </div>
        </div>

        {{-- Charts: Tren & Distribusi --}}
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

        {{-- Charts: Produk, Kota, Agency --}}
        <div class="grid gap-6 lg:grid-cols-3">
            <div
                class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-5 shadow-sm">
                <div class="mb-3 font-semibold">Top Produk (Premi)</div>
                <canvas id="chartProduct" height="130"></canvas>
            </div>
            <div
                class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-5 shadow-sm">
                <div class="mb-3 font-semibold">Top Kota (Premi)</div>
                <canvas id="chartCity" height="130"></canvas>
            </div>
            <div
                class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-5 shadow-sm">
                <div class="mb-3 font-semibold">Top Agency (Premi)</div>
                <canvas id="chartAgency" height="130"></canvas>
            </div>
        </div>

        {{-- Top 3 Agent --}}
        <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-5 shadow-sm">
            <div class="mb-3 font-semibold">Top 3 Agent (Premi)</div>
            <canvas id="chartAgents" height="120"></canvas>
        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Server data
        const tsLabels = @json($tsLabels);
        const tsValues = @json($tsValues);
        const byCase = @json($byCase);
        const byProduct = @json($byProduct);
        const byCity = @json($byCity);
        const byAgency = @json($byAgency);
        const topAgents = @json($topAgents);

        // Tren (line/area)
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
                    pointRadius: 2
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
                labels: byCase.map(r => 'Case ' + r.case_level),
                datasets: [{
                    data: byCase.map(r => r.cnt),
                    backgroundColor: ['#6366F1', '#F59E0B', '#EF4444']
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

        // Produk (bar vertikal)
        new Chart(document.getElementById('chartProduct'), {
            type: 'bar',
            data: {
                labels: byProduct.map(p => p.product),
                datasets: [{
                    label: 'Premi',
                    data: byProduct.map(p => p.total)
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
                        }
                    }
                }
            }
        });

        // Kota (bar vertikal)
        new Chart(document.getElementById('chartCity'), {
            type: 'bar',
            data: {
                labels: byCity.map(c => c.city),
                datasets: [{
                    label: 'Premi',
                    data: byCity.map(c => c.total)
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
                        }
                    }
                }
            }
        });

        // Agency (bar vertikal)
        new Chart(document.getElementById('chartAgency'), {
            type: 'bar',
            data: {
                labels: byAgency.map(a => a.agency),
                datasets: [{
                    label: 'Premi',
                    data: byAgency.map(a => a.total)
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
                        }
                    }
                }
            }
        });

        // Top 3 Agent (bar vertikal)
        new Chart(document.getElementById('chartAgents'), {
            type: 'bar',
            data: {
                labels: topAgents.map(a => a.name),
                datasets: [{
                    label: 'Premi',
                    data: topAgents.map(a => a.total)
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
                        }
                    }
                }
            }
        });
    </script>
</x-app-layout>
