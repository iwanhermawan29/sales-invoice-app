<x-app-layout>
    <x-slot name="header">
        <h2>Detail Item</h2>
    </x-slot>
    <div class="p-6 bg-white rounded shadow max-w-md mx-auto space-y-3">
        <div><strong>Kode:</strong> {{ $item->code }}</div>
        <div><strong>Nama:</strong> {{ $item->name }}</div>
        <div><strong>Unit:</strong> {{ $item->unit }}</div>
        <div><strong>Harga:</strong> @number_format($item->price, 2)</div>
        <div><strong>Status:</strong> {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}</div>
        <a href="{{ route('items.index') }}" class="text-blue-600">Kembali</a>
    </div>
</x-app-layout>
