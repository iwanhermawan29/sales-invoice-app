<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Produk') }}
        </h2>
    </x-slot>

    <div class="p-6 max-w-lg mx-auto">
        <form action="{{ route('products.update', $product) }}" method="POST" class="space-y-6">
            @csrf @method('PATCH')
            @include('products._form', ['product' => $product])
            <div class="flex justify-end space-x-4">
                <a href="{{ route('products.index') }}"
                    class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">{{ __('Batal') }}</a>
                <button type="submit"
                    class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">{{ __('Update') }}</button>
            </div>
        </form>
    </div>
</x-app-layout>
