<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold">{{ __('Report Target Penjualan') }}</h2>
    </x-slot>

    <div class="p-6 space-y-6">

        {{-- Filter Periode --}}
        <form method="GET" action="{{ route('reports.sales-targets.index') }}" class="flex items-center space-x-2">
            <label for="period" class="text-sm text-gray-700">{{ __('Periode') }}</label>
            <input type="month" id="period" name="period" value="{{ $period }}"
                class="border rounded px-2 py-1">
            <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
                {{ __('Filter') }}
            </button>
        </form>

        {{-- Summary --}}
        <div class="bg-white p-6 rounded-lg shadow flex flex-wrap justify-between">
            <div class="mb-4">
                <p class="text-sm text-gray-500">{{ __('Total Target') }}</p>
                <p class="text-2xl font-semibold">Rp {{ number_format($totalTarget, 2, ',', '.') }}</p>
            </div>
            <div class="mb-4">
                <p class="text-sm text-gray-500">{{ __('Total Realisasi') }}</p>
                <p class="text-2xl font-semibold text-green-600">
                    Rp {{ number_format($totalRealisasi, 2, ',', '.') }}
                </p>
            </div>
            <div class="mb-4">
                <p class="text-sm text-gray-500">{{ __('Sisa Seluruh Agen') }}</p>
                <p class="text-2xl font-semibold text-blue-600">
                    Rp {{ number_format($totalTarget - $totalRealisasi, 2, ',', '.') }}
                </p>
            </div>
        </div>

        {{-- Tabel dengan Peringkat --}}
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            {{ __('Peringkat') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            {{ __('Agen') }}
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                            {{ __('Target') }}
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                            {{ __('Realisasi') }}
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                            {{ __('Sisa') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($ranked as $t)
                        @php
                            $sisa = $t->target_amount - $t->closed_sum;
                        @endphp
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-700">
                                {{ $loop->iteration }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $t->user->name }}
                            </td>
                            <td class="px-6 py-4 text-sm text-right text-gray-700">
                                Rp {{ number_format($t->target_amount, 2, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-right text-green-600">
                                Rp {{ number_format($t->closed_sum, 2, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-right text-blue-600">
                                Rp {{ number_format($sisa, 2, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
