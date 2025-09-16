<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">{{ __('Master Pelanggan') }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('success'))
                <div class="bg-green-100 border-green-400 text-green-700 px-4 py-2 rounded">
                    {{ session('success') }}
                </div>
            @endif
            <form method="GET" action="{{ route('customers.index') }}" class="flex space-x-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode/nama..."
                    class="border rounded px-2 py-1 flex-1" />
                <select name="active" class="border rounded px-2 py-1">
                    <option value="">-- Semua Status --</option>
                    <option value="1" {{ request('active') === '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ request('active') === '0' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                <button type="submit" class="px-4 py-1 bg-blue-600 text-white rounded">Filter</button>
                <a href="{{ route('customers.print', request()->all()) }}" target="_blank"
                    class="ml-auto px-4 py-1 bg-green-600 text-white rounded">Cetak</a>
            </form>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left">No</th>
                            <th class="px-4 py-2">Kode</th>
                            <th class="px-4 py-2">Nama</th>
                            <th class="px-4 py-2">Email</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($customers as $cust)
                            <tr>
                                <td class="px-4 py-2">{{ $customers->firstItem() + $loop->index }}</td>
                                <td class="px-4 py-2">{{ $cust->code }}</td>
                                <td class="px-4 py-2">{{ $cust->name }}</td>
                                <td class="px-4 py-2">{{ $cust->email }}</td>
                                <td class="px-4 py-2">{{ $cust->is_active ? 'Aktif' : 'Nonaktif' }}</td>
                                <td class="px-4 py-2 space-x-2">
                                    <a href="{{ route('customers.edit', $cust) }}"
                                        class="text-yellow-600 hover:underline">Edit</a>
                                    <form action="{{ route('customers.destroy', $cust) }}" method="POST"
                                        class="inline" onsubmit="return confirm('Hapus pelanggan?')">
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
            <div class="mt-4">{{ $customers->links() }}</div>
            <div class="flex justify-end">
                <a href="{{ route('customers.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded">+ Tambah
                    Pelanggan</a>
            </div>
        </div>
    </div>
</x-app-layout>
