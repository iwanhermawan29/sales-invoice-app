<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Produk') }}
        </h2>
    </x-slot>

    <div class="p-6 max-w-lg mx-auto">
        <form action="{{ route('products.store') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label for="type" class="block font-medium">{{ __('Tipe Produk') }}</label>
                <select name="type" id="type" required class="w-full mt-1 p-2 border rounded">
                    <option value="health">{{ __('Asuransi Kesehatan') }}</option>
                    <option value="life">{{ __('Asuransi Jiwa') }}</option>
                </select>
                @error('type')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="name" class="block font-medium">{{ __('Nama Produk') }}</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                    class="w-full mt-1 p-2 border rounded" />
                @error('name')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="description" class="block font-medium">{{ __('Deskripsi') }}</label>
                <textarea name="description" id="description" rows="3" class="w-full mt-1 p-2 border rounded">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex justify-end space-x-4">
                <a href="{{ route('products.index') }}"
                    class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">{{ __('Batal') }}</a>
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">{{ __('Simpan') }}</button>
            </div>
        </form>
    </div>
</x-app-layout>
