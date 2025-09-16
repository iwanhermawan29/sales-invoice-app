<x-app-layout>
    <x-slot name="header">
        <h2>Delivery Orders</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <form class="flex space-x-2" method="GET">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari DO/SO..."
                    class="border rounded px-2 py-1 flex-1" />
                <select name="status" class="border rounded px-2 py-1">
                    <option value="">-- Semua Status --</option>
                    @foreach (['pending', 'shipped', 'delivered'] as $s)
                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                            {{ ucfirst($s) }}
                        </option>
                    @endforeach
                </select>
                <button class="bg-blue-600 text-white px-4 py-1 rounded">Filter</button>
                <a href="{{ route('delivery-orders.print', request()->all()) }}" target="_blank"
                    class="ml-auto bg-green-600 text-white px-4 py-1 rounded">
                    Cetak
                </a>
            </form>

            <div class="bg-white shadow-sm sm:rounded-lg overflow-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2">No</th>
                            <th class="px-4 py-2">DO Number</th>
                            <th class="px-4 py-2">SO Number</th>
                            <th class="px-4 py-2">Customer</th>
                            <th class="px-4 py-2">Delivery Date</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($dos as $do)
                            <tr>
                                <td class="px-4 py-2">{{ $dos->firstItem() + $loop->index }}</td>
                                <td class="px-4 py-2">{{ $do->do_number }}</td>
                                <td class="px-4 py-2">{{ $do->salesOrder->so_number }}</td>
                                <td class="px-4 py-2">{{ $do->salesOrder->customer->name }}</td>
                                <td class="px-4 py-2">{{ $do->delivery_date->format('d-m-Y') }}</td>
                                <td class="px-4 py-2">{{ ucfirst($do->status) }}</td>
                                <td class="px-4 py-2 space-x-2">
                                    <a href="{{ route('delivery-orders.edit', $do) }}"
                                        class="text-yellow-600 hover:underline">Edit</a>
                                    <a href="{{ route('delivery-orders.show', $do) }}"
                                        class="text-indigo-600 hover:underline">Detail</a>
                                    <form action="{{ route('delivery-orders.destroy', $do) }}" method="POST"
                                        class="inline" onsubmit="return confirm('Hapus DO ini?')">
                                        @csrf @method('DELETE')
                                        <button class="text-red-600 hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">Tidak ada data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div>{{ $dos->links() }}</div>

            <div class="flex justify-end">
                <a href="{{ route('delivery-orders.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">+
                    Tambah DO</a>
            </div>
        </div>
    </div>
</x-app-layout>
