{{-- resources/views/contests/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100">{{ $contest->nama_kontes }}</h2>
            <a href="{{ route('contests.index') }}" class="rounded-xl border px-4 py-2">Kembali</a>
        </div>
    </x-slot>

    <div class="p-6 space-y-6">
        <div class="rounded-2xl border bg-white dark:bg-gray-900 p-6">
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <div class="text-sm text-gray-500">Periode</div>
                    <div class="font-semibold">{{ ucfirst($contest->periode ?? '—') }}</div>
                </div>
                <div>
                    <div class="text-sm text-gray-500">Rentang Tanggal</div>
                    <div class="font-semibold">
                        {{ optional($contest->tanggal_mulai)->format('d M Y') ?? '—' }}
                        —
                        {{ optional($contest->tanggal_selesai)->format('d M Y') ?? 'Ongoing' }}
                    </div>
                </div>
                <div>
                    <div class="text-sm text-gray-500">Target Premi</div>
                    <div class="font-semibold">Rp {{ number_format($contest->target_premi, 0, ',', '.') }}</div>
                </div>
                <div>
                    <div class="text-sm text-gray-500">Target Case</div>
                    <div class="font-semibold">{{ number_format($contest->target_case, 0, ',', '.') }}</div>
                </div>
            </div>

            <div class="mt-6">
                <div class="text-sm text-gray-500">Flyer</div>
                @if ($contest->flyer_url)
                    <a href="{{ $contest->flyer_url }}" target="_blank" class="text-indigo-600 hover:underline">
                        Buka Flyer
                    </a>
                @else
                    <div class="text-gray-500">Tidak ada flyer</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
