<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">{{ __('Detail Pelanggan') }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="p-6 bg-white rounded-lg shadow space-y-4">
                <div><strong>Kode:</strong> {{ $customer->code }}</div>
                <div><strong>Nama:</strong> {{ $customer->name }}</div>
                <div><strong>Alamat:</strong> {{ $customer->address }}</div>
                <div><strong>Telepon:</strong> {{ $customer->phone }}</div>
                <div><strong>Email:</strong> {{ $customer->email }}</div>
                <div><strong>Status:</strong> {{ $customer->is_active ? 'Aktif' : 'Nonaktif' }}</div>
                <a href="{{ route('customers.index') }}" class="text-blue-600 hover:underline">Kembali</a>
            </div>
        </div>
    </div>
</x-app-layout>
