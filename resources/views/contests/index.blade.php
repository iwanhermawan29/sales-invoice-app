{{-- resources/views/contests/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100">Kontes</h2>
            <a href="{{ route('contests.create') }}" class="rounded-xl px-4 py-2 bg-indigo-600 text-white">Tambah</a>
        </div>
    </x-slot>

    <div class="p-6 space-y-6">
        {{-- Filter Bar --}}
        <form method="GET" class="rounded-2xl border bg-white dark:bg-gray-900 p-4">
            <div class="grid gap-3 md:grid-cols-5">
                <div class="md:col-span-2">
                    <label class="text-sm text-gray-600 dark:text-gray-300">Cari</label>
                    <input type="text" name="q" value="{{ $q ?? '' }}"
                        class="mt-1 w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700"
                        placeholder="Ketik nama kontes…">
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
                <div>
                    <label class="text-sm text-gray-600 dark:text-gray-300">Dari</label>
                    <input type="date" name="start" value="{{ optional($start)->format('Y-m-d') }}"
                        class="mt-1 w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700">
                </div>
                <div>
                    <label class="text-sm text-gray-600 dark:text-gray-300">Sampai</label>
                    <input type="date" name="end" value="{{ optional($end)->format('Y-m-d') }}"
                        class="mt-1 w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700">
                </div>
            </div>
            <div class="mt-3 flex justify-end gap-2">
                <a href="{{ route('contests.index') }}" class="rounded-xl border px-4 py-2">Reset</a>
                <button class="rounded-xl bg-gray-900 text-white px-4 py-2">Terapkan</button>
            </div>
        </form>

        {{-- Grid Cards --}}
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($contests as $c)
                @php
                    $badgeMap = [
                        'monthly' => 'bg-indigo-100 text-indigo-700',
                        'quarterly' => 'bg-amber-100 text-amber-700',
                        'annual' => 'bg-green-100 text-green-700',
                    ];
                    $badge = $badgeMap[$c->periode] ?? 'bg-gray-100 text-gray-700';
                @endphp

                <article class="overflow-hidden rounded-2xl border bg-white dark:bg-gray-900 shadow">
                    <div class="p-4 space-y-3">
                        <div class="flex items-center justify-between gap-2">
                            <h3 class="text-lg font-semibold">{{ $c->nama_kontes }}</h3>
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium {{ $badge }}">
                                {{ ucfirst($c->periode ?? '—') }}
                            </span>
                        </div>

                        {{-- Rentang tanggal (BARU) --}}
                        @if ($c->tanggal_mulai || $c->tanggal_selesai)
                            <div class="text-xs text-gray-500">
                                <span>{{ optional($c->tanggal_mulai)->format('d M Y') ?? '—' }}</span>
                                —
                                <span>{{ optional($c->tanggal_selesai)->format('d M Y') ?? 'Ongoing' }}</span>
                            </div>
                        @endif

                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div class="rounded-xl bg-gray-50 dark:bg-gray-800 p-3">
                                <div class="text-gray-500">Target Premi</div>
                                <div class="font-semibold">Rp {{ number_format($c->target_premi, 0, ',', '.') }}</div>
                            </div>
                            <div class="rounded-xl bg-gray-50 dark:bg-gray-800 p-3">
                                <div class="text-gray-500">Target Case</div>
                                <div class="font-semibold">{{ number_format($c->target_case, 0, ',', '.') }}</div>
                            </div>
                        </div>

                        <div class="flex justify-between items-center pt-1">
                            <div class="text-xs text-gray-500 truncate">
                                @if (!empty($c->flyer_url))
                                    <a href="{{ $c->flyer_url }}" target="_blank"
                                        class="text-indigo-600 hover:underline">Lihat Flyer</a>
                                @else
                                    <span class="opacity-70">Tidak ada flyer</span>
                                @endif
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('contests.edit', $c) }}"
                                    class="px-3 py-1.5 rounded-lg text-blue-600 hover:bg-blue-50">Edit</a>
                                <form action="{{ route('contests.destroy', $c) }}" method="POST"
                                    class="inline js-delete">
                                    @csrf @method('DELETE')
                                    <button class="px-3 py-1.5 rounded-lg text-rose-600 hover:bg-rose-50">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full text-center text-gray-500 py-10">Belum ada kontes.</div>
            @endforelse
        </div>

        @if ($contests->hasPages())
            <div>{{ $contests->onEachSide(1)->links() }}</div>
        @endif
    </div>

    {{-- SweetAlert konfirmasi hapus --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('click', (e) => {
            const form = e.target.closest('.js-delete');
            if (!form) return;
            e.preventDefault();
            Swal.fire({
                title: 'Hapus kontes ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then(r => {
                if (r.isConfirmed) form.submit();
            });
        });
    </script>
</x-app-layout>
