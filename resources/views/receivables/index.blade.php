{{-- resources/views/receivables/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2>Master Receivables</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if (session('success'))
                <div class="bg-green-100 text-green-800 p-4 rounded">{{ session('success') }}</div>
            @endif

            <form method="GET" class="flex space-x-2">
                <input name="q" value="{{ request('q') }}" placeholder="Cari Invoice#..."
                    class="border rounded px-2 py-1 flex-1" />
                <select name="status" class="border rounded px-2 py-1">
                    <option value="">-- Semua Status --</option>
                    <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                    <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Partial</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                </select>
                <button class="bg-blue-600 text-white px-4 py-1 rounded">Filter</button>
                <a href="{{ route('receivables.print', request()->all()) }}" target="_blank"
                    class="ml-auto bg-green-600 text-white px-4 py-1 rounded">
                    Cetak
                </a>
            </form>

            <div class="bg-white overflow-auto shadow-sm sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2">#</th>
                            <th class="px-4 py-2">Invoice #</th>
                            <th class="px-4 py-2">Customer</th>
                            <th class="px-4 py-2">Due Date</th>
                            <th class="px-4 py-2 text-right">Amt Due</th>
                            <th class="px-4 py-2 text-right">Amt Paid</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($receivables as $r)
                            <tr>
                                <td class="px-4 py-2">{{ $receivables->firstItem() + $loop->index }}</td>
                                <td class="px-4 py-2">{{ $r->invoice->invoice_number }}</td>
                                <td class="px-4 py-2">
                                    {{ optional($r->invoice->deliveryOrder->salesOrder->customer)->name }}
                                </td>
                                <td class="px-4 py-2">{{ $r->due_date->format('d-m-Y') }}</td>
                                <td class="px-4 py-2 text-right">
                                    {{ number_format($r->amount_due, 2, ',', '.') }}
                                </td>
                                <td class="px-4 py-2 text-right">
                                    {{ number_format($r->amount_paid, 2, ',', '.') }}
                                </td>
                                <td class="px-4 py-2">{{ ucfirst($r->status) }}</td>
                                <td class="px-4 py-2 space-x-2">
                                    <a href="{{ route('receivables.show', $r) }}" class="text-blue-600">View</a>
                                    <a href="{{ route('receivables.edit', $r) }}" class="text-yellow-600">Edit</a>
                                    <form action="{{ route('receivables.destroy', $r) }}" method="POST" class="inline"
                                        onsubmit="return confirm('Hapus receivable ini?')">
                                        @csrf @method('DELETE')
                                        <button class="text-red-600">Del</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">Tidak ada data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div>{{ $receivables->links() }}</div>

            <div class="flex justify-end">
                <a href="{{ route('receivables.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">+ Tambah
                    Receivable</a>
            </div>
        </div>
    </div>
</x-app-layout>
