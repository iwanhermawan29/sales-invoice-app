<x-app-layout>
    <x-slot name="header">
        <h2>Master Invoice</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('success'))
                <div class="bg-green-100 border-green-400 text-green-700 px-4 py-2 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <form class="flex space-x-2" method="GET">
                <input name="q" value="{{ request('q') }}" placeholder="Cari nomor..."
                    class="border rounded px-2 py-1 flex-1" />
                <select name="status" class="border rounded px-2 py-1">
                    <option value="">-- Semua Status --</option>
                    @foreach (['open', 'paid', 'partial', 'cancelled'] as $s)
                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                            {{ ucfirst($s) }}
                        </option>
                    @endforeach
                </select>
                <button class="bg-blue-600 text-white px-4 py-1 rounded">Filter</button>
                <a href="{{ route('invoices.print', request()->all()) }}" target="_blank"
                    class="ml-auto bg-green-600 text-white px-4 py-1 rounded">Cetak</a>
            </form>

            <div class="bg-white shadow overflow-auto sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2">No</th>
                            <th class="px-4 py-2">Invoice#</th>
                            <th class="px-4 py-2">DO#</th>
                            <th class="px-4 py-2">Customer</th>
                            <th class="px-4 py-2">Invoice Date</th>
                            <th class="px-4 py-2">Due Date</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($invoices as $inv)
                            <tr>
                                <td class="px-4 py-2">{{ $invoices->firstItem() + $loop->index }}</td>
                                <td class="px-4 py-2">{{ $inv->invoice_number }}</td>
                                <td class="px-4 py-2">{{ $inv->deliveryOrder->do_number }}</td>
                                <td class="px-4 py-2">{{ $inv->deliveryOrder->salesOrder->customer->name }}</td>
                                <td class="px-4 py-2">{{ $inv->invoice_date->format('d-m-Y') }}</td>
                                <td class="px-4 py-2">{{ optional($inv->due_date)->format('d-m-Y') }}</td>
                                <td class="px-4 py-2">{{ ucfirst($inv->status) }}</td>
                                <td class="px-4 py-2 space-x-2">
                                    <a href="{{ route('invoices.edit', $inv) }}" class="text-yellow-600">Edit</a>
                                    <a href="{{ route('invoices.show', $inv) }}" class="text-indigo-600">Detail</a>
                                    <form action="{{ route('invoices.destroy', $inv) }}" method="POST" class="inline"
                                        onsubmit="return confirm('Hapus invoice?')">
                                        @csrf @method('DELETE')
                                        <button class="text-red-600">Hapus</button>
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

            <div>{{ $invoices->links() }}</div>

            <div class="flex justify-end">
                <a href="{{ route('invoices.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">+ Tambah
                    Invoice</a>
            </div>
        </div>
    </div>
</x-app-layout>
