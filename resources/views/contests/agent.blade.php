{{-- resources/views/contests/agent.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100">Kontes Untuk Agent</h2>
            @can('manage-contests')
                <a href="{{ route('contests.index') }}" class="text-sm text-indigo-600 hover:underline">Kelola Kontes</a>
            @endcan
        </div>
    </x-slot>

    <div class="p-6 space-y-6 max-w-7xl mx-auto" x-data="{ showFlyer: false, flyerSrc: null }">
        {{-- Filter --}}
        <form method="GET"
            class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-4">
            <div class="grid gap-3 sm:grid-cols-3">
                <div class="sm:col-span-2">
                    <label class="text-sm text-gray-600 dark:text-gray-300">Cari Kontes</label>
                    <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Ketik nama kontes…"
                        class="mt-1 w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700" />
                </div>
                <div>
                    <label class="text-sm text-gray-600 dark:text-gray-300">Periode</label>
                    <select name="periode"
                        class="mt-1 w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700">
                        <option value="">— Semua —</option>
                        <option value="monthly" @selected(($periode ?? '') === 'monthly')>Bulanan</option>
                        <option value="quarterly" @selected(($periode ?? '') === 'quarterly')>Quarterly</option>
                        <option value="annual" @selected(($periode ?? '') === 'annual')>Annual</option>
                    </select>
                </div>
            </div>
            <div class="mt-3 flex justify-end">
                <button class="inline-flex rounded-xl bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">
                    Terapkan
                </button>
            </div>
        </form>

        {{-- List Kontes --}}
        <div class="space-y-12">
            @forelse ($contests as $c)
                @php
                    $badge =
                        [
                            'monthly' => 'bg-indigo-100 text-indigo-700',
                            'quarterly' => 'bg-amber-100 text-amber-700',
                            'annual' => 'bg-green-100 text-green-700',
                        ][$c->periode] ?? 'bg-gray-100 text-gray-700';

                    $isImage = $c->flyer_mime && \Illuminate\Support\Str::startsWith($c->flyer_mime, 'image/');
                    $isPdf =
                        $c->flyer_mime === 'application/pdf' ||
                        \Illuminate\Support\Str::endsWith($c->flyer_path ?? '', '.pdf');

                    $photos = method_exists($c, 'photos') ? $c->photos : collect();
                    $logos = method_exists($c, 'logos') ? $c->logos : collect();

                    // progress per kontes (dipass dari controller)
                    $pg = $progress[$c->id] ?? ['premi' => 0, 'cases' => 0, 'premi_pct' => null, 'case_pct' => null];
                    $premiPct = $pg['premi_pct'];
                    $casePct = $pg['case_pct'];
                @endphp

                {{-- Grid 2 kolom: KIRI kartu, KANAN panel --}}
                <div class="grid gap-6 lg:grid-cols-2">
                    {{-- KIRI: Kartu kontes --}}
                    <article
                        class="overflow-hidden rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow">
                        <div class="relative h-48 bg-gradient-to-br from-indigo-600 to-blue-500">
                            @if ($c->flyer_url && $isImage)
                                <img src="{{ $c->flyer_url }}" alt="Flyer {{ $c->nama_kontes }}"
                                    class="h-full w-full object-cover opacity-90" loading="lazy">
                            @else
                                <div class="absolute inset-0 grid place-content-center text-white/90">
                                    @if ($isPdf)
                                        <div class="text-center">
                                            <svg class="mx-auto h-10 w-10" viewBox="0 0 24 24" fill="currentColor">
                                                <path
                                                    d="M6 2a2 2 0 00-2 2v16a2 2 0 002 2h7a1 1 0 001-1v-5h4a1 1 0 001-1V9l-5-5H6z" />
                                            </svg>
                                            <div class="text-xs mt-1">PDF Flyer</div>
                                        </div>
                                    @else
                                        <div class="text-lg font-semibold">Kontes</div>
                                    @endif
                                </div>
                            @endif

                            <div class="absolute top-3 left-3">
                                <span
                                    class="inline-flex rounded-full px-3 py-1 text-xs font-medium {{ $badge }}">
                                    {{ ucfirst($c->periode) }}
                                </span>
                            </div>
                        </div>

                        <div class="p-4 space-y-3">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $c->nama_kontes }}
                            </h3>

                            @if ($c->tanggal_mulai || $c->tanggal_selesai)
                                <div class="text-xs text-gray-500">
                                    {{ optional($c->tanggal_mulai)->format('d M Y') ?? '—' }} —
                                    {{ optional($c->tanggal_selesai)->format('d M Y') ?? 'Ongoing' }}
                                </div>
                            @endif

                            <div class="grid grid-cols-2 gap-3 text-sm">
                                <div class="rounded-xl bg-gray-50 dark:bg-gray-800 p-3">
                                    <div class="text-gray-500">Target Premi</div>
                                    <div class="font-semibold">Rp {{ number_format($c->target_premi, 0, ',', '.') }}
                                    </div>
                                </div>
                                <div class="rounded-xl bg-gray-50 dark:bg-gray-800 p-3">
                                    <div class="text-gray-500">Target Case</div>
                                    <div class="font-semibold">{{ number_format($c->target_case, 0, ',', '.') }}</div>
                                </div>
                            </div>

                            <div class="flex justify-end">
                                @if ($c->flyer_url)
                                    <button type="button" @click="showFlyer=true; flyerSrc='{{ $c->flyer_url }}'"
                                        class="rounded-xl px-4 py-2 bg-indigo-600 text-white hover:bg-indigo-700">
                                        Buka Flyer
                                    </button>
                                @endif
                            </div>
                        </div>
                    </article>

                    {{-- KANAN: Panel (progress, dokumentasi, collaboration) --}}
                    <div class="space-y-6">
                        {{-- Pencapaian --}}
                        <section class="space-y-3">
                            <h4 class="text-base font-semibold text-gray-800 dark:text-gray-100">Pencapaian Anda</h4>
                            <div class="grid grid-cols-2 gap-4">
                                {{-- Donut Premi --}}
                                <div
                                    class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-3">
                                    <div class="text-xs text-gray-500 mb-1">Premi</div>
                                    <div class="flex items-center gap-3">
                                        <div class="w-20 h-20">
                                            <canvas id="donut-premi-{{ $c->id }}" width="80" height="80"
                                                data-percent="{{ $premiPct ?? 0 }}"></canvas>
                                        </div>
                                        <div>
                                            <div class="text-lg font-semibold">
                                                {{ $premiPct !== null ? $premiPct . '%' : '—' }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                Rp {{ number_format($pg['premi'], 0, ',', '.') }}
                                                / Rp {{ number_format($c->target_premi, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Donut Case --}}
                                <div
                                    class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-3">
                                    <div class="text-xs text-gray-500 mb-1">Case</div>
                                    <div class="flex items-center gap-3">
                                        <div class="w-20 h-20">
                                            <canvas id="donut-case-{{ $c->id }}" width="80" height="80"
                                                data-percent="{{ $casePct ?? 0 }}"></canvas>
                                        </div>
                                        <div>
                                            <div class="text-lg font-semibold">
                                                {{ $casePct !== null ? $casePct . '%' : '—' }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ number_format($pg['cases'], 0, ',', '.') }}
                                                / {{ number_format($c->target_case, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        {{-- Dokumentasi --}}
                        @if ($photos->isNotEmpty())
                            <section class="space-y-3">
                                <h4 class="text-base font-semibold text-gray-800 dark:text-gray-100">Dokumentasi</h4>
                                <div class="grid gap-3 grid-cols-2 md:grid-cols-3">
                                    @foreach ($photos as $p)
                                        <img src="{{ $p->url }}" alt="Dokumentasi"
                                            class="h-28 w-full object-cover rounded-xl cursor-pointer"
                                            @click="showFlyer=true; flyerSrc='{{ $p->url }}'">
                                    @endforeach
                                </div>
                            </section>
                        @endif

                        {{-- Collaboration --}}
                        @if ($logos->isNotEmpty())
                            <section class="space-y-3">
                                <h4 class="text-base font-semibold text-gray-800 dark:text-gray-100">Collaboration</h4>
                                <div
                                    class="flex flex-wrap items-center gap-4 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-3">
                                    @foreach ($logos as $lg)
                                        <img src="{{ $lg->url }}" alt="Logo" class="h-8 object-contain">
                                    @endforeach
                                </div>
                            </section>
                        @endif
                    </div>
                </div>

                <hr class="my-8 border-gray-200 dark:border-gray-700">
            @empty
                <div class="text-center text-gray-500 dark:text-gray-400 py-10">
                    Belum ada kontes aktif.
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if ($contests->hasPages())
            <div>
                {{ $contests->onEachSide(1)->links() }}
            </div>
        @endif

        {{-- LIGHTBOX sederhana --}}
        <div x-show="showFlyer" x-cloak class="fixed inset-0 z-40 bg-black/80 grid place-items-center p-4"
            @keydown.escape.window="showFlyer=false">
            <img :src="flyerSrc" class="max-h-[90vh] max-w-[90vw] object-contain rounded shadow-2xl">
            <button class="absolute top-4 right-4 text-white text-xl" @click="showFlyer=false">✕</button>
        </div>
    </div>

    {{-- Chart.js untuk donut progress --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('canvas[id^="donut-"]').forEach(cv => {
                const pct = parseFloat(cv.dataset.percent || '0');
                const remain = Math.max(0, 100 - pct);
                new Chart(cv.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Pencapaian', 'Sisa'],
                        datasets: [{
                            data: [pct, remain]
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
