<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Monitoring Piutang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Filter Form --}}
            <form method="GET" class="flex flex-wrap gap-4 bg-white p-6 rounded-lg shadow">
                <div>
                    <x-input-label for="from" :value="__('Dari Tanggal')" />
                    <x-text-input id="from" name="from" type="date" value="{{ old('from', $from) }}"
                        class="mt-1 block w-full" />
                </div>
                <div>
                    <x-input-label for="to" :value="__('Sampai Tanggal')" />
                    <x-text-input id="to" name="to" type="date" value="{{ old('to', $to) }}"
                        class="mt-1 block w-full" />
                </div>
                <div class="flex items-end space-x-2">
                    <label class="flex items-center space-x-1">
                        <input type="checkbox" name="outstanding_only"
                            {{ request()->filled('outstanding_only') ? 'checked' : '' }}
                            class="rounded border-gray-300" />
                        <span class="text-sm">Outstanding saja</span>
                    </label>
                    <x-primary-button>{{ __('Filter') }}</x-primary-button>
                </div>
            </form>

            {{-- Summary Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-gray-500">Total Piutang</h3>
                    <p class="mt-2 text-2xl font-semibold">Rp {{ number_format($totalDue, 2, ',', '.') }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-gray-500">Total Dibayar</h3>
                    <p class="mt-2 text-2xl font-semibold">Rp {{ number_format($totalPaid, 2, ',', '.') }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-gray-500">Outstanding</h3>
                    <p class="mt-2 text-2xl font-semibold">Rp {{ number_format($totalOutstanding, 2, ',', '.') }}</p>
                </div>
            </div>

            {{-- Table --}}
            <div class="bg-white overflow-auto shadow-sm sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2">No</th>
                            <th class="px-4 py-2">Invoice#</th>
                            <th class="px-4 py-2">Customer</th>
                            <th class="px-4 py-2">Due Date</th>
                            <th class="px-4 py-2 text-right">Amt Due</th>
                            <th class="px-4 py-2 text-right">Amt Paid</th>
                            <th class="px-4 py-2 text-right">Outstanding</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($receivables as $i => $r)
                            <tr>
                                <td class="px-4 py-2">{{ $i + 1 }}</td>
                                <td class="px-4 py-2">{{ $r->invoice->invoice_number }}</td>
                                <td class="px-4 py-2">
                                    {{ optional($r->invoice->deliveryOrder->salesOrder->customer)->name }}
                                </td>
                                <td class="px-4 py-2">{{ $r->due_date->format('d-m-Y') }}</td>
                                <td class="px-4 py-2 text-right">{{ number_format($r->amount_due, 2, ',', '.') }}</td>
                                <td class="px-4 py-2 text-right">{{ number_format($r->amount_paid, 2, ',', '.') }}</td>
                                <td class="px-4 py-2 text-right">
                                    {{ number_format($r->amount_due - $r->amount_paid, 2, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                        @if ($receivables->isEmpty())
                            <tr>
                                <td colspan="7" class="text-center py-4">Tidak ada data untuk periode ini.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>
