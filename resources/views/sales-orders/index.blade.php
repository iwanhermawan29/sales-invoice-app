<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">{{ __('Sales Orders') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Filter & Actions --}}
            <div class="flex space-x-2">
                <form method="GET" action="{{ route('sales-orders.index') }}" class="flex space-x-2 flex-1">
                    <input type="text" name="q" value="{{ request('q') }}"
                        placeholder="Cari SO Number atau Customer..." class="border rounded px-2 py-1 flex-1" />
                    <select name="status" class="border rounded px-2 py-1">
                        <option value="">-- Semua Status --</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    <button type="submit" class="px-4 py-1 bg-blue-600 text-white rounded">Filter</button>
                </form>
                <a href="{{ route('sales-orders.create') }}" class="px-4 py-1 bg-blue-600 text-white rounded">+ New
                    SO</a>
                <a href="{{ route('sales-orders.print', request()->all()) }}" target="_blank"
                    class="px-4 py-1 bg-green-600 text-white rounded">Cetak</a>
            </div>

            {{-- Table --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left">No</th>
                            <th class="px-4 py-2 text-left">SO Number</th>
                            <th class="px-4 py-2 text-left">Customer</th>
                            <th class="px-4 py-2 text-left">Order Date</th>
                            <th class="px-4 py-2 text-left">Status</th>
                            <th class="px-4 py-2 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($salesOrders as $so)
                            <tr>
                                <td class="px-4 py-2">{{ $salesOrders->firstItem() + $loop->index }}</td>
                                <td class="px-4 py-2">{{ $so->so_number }}</td>
                                <td class="px-4 py-2">{{ $so->customer->name }}</td>
                                <td class="px-4 py-2">{{ \Carbon\Carbon::parse($so->order_date)->format('d-m-Y') }}
                                </td>
                                <td class="px-4 py-2">{{ ucfirst($so->status) }}</td>
                                <td class="px-4 py-2 space-x-2">
                                    <a href="{{ route('sales-orders.show', $so) }}"
                                        class="text-blue-600 hover:underline">View</a>
                                    <a href="{{ route('sales-orders.edit', $so) }}"
                                        class="text-yellow-600 hover:underline">Edit</a>
                                    <form action="{{ route('sales-orders.destroy', $so) }}" method="POST"
                                        class="inline" onsubmit="return confirm('Hapus SO ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-4 text-center text-gray-500">Tidak ada data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div>{{ $salesOrders->links() }}</div>
        </div>
    </div>
</x-app-layout>
