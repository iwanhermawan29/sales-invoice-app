<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Produk') }}
        </h2>
    </x-slot>

    <div class="p-6 max-w-lg mx-auto bg-white rounded shadow">
        <p><strong>{{ __('Tipe:') }}</strong> {{ $product->type_label }}</p>
        <p class="mt-2"><strong>{{ __('Nama:') }}</strong> {{ $product->name }}</p>
        <p class="mt-2"><strong>{{ __('Deskripsi:') }}</strong> {{ $product->description }}</p>
        <div class="mt-6">
            <a href="{{ route('products.index') }}"
                class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">{{ __('Kembali') }}</a>
        </div>
    </div>
</x-app-layout>
