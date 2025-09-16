{{-- resources/views/items/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Item') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="p-6 bg-white rounded-lg shadow">
                <form action="{{ route('items.store') }}" method="POST" class="space-y-6">
                    @csrf

                    {{-- Kode --}}
                    <div>
                        <x-input-label for="code" :value="__('Kode')" />
                        <x-text-input id="code" name="code" type="text" value="{{ old('code', $code) }}"
                            readonly class="mt-1 block w-full bg-gray-100 cursor-not-allowed" />
                        <x-input-error :messages="$errors->get('code')" class="mt-2" />
                    </div>

                    {{-- Nama --}}
                    <div>
                        <x-input-label for="name" :value="__('Nama')" />
                        <x-text-input id="name" name="name" type="text" value="{{ old('name') }}" required
                            class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    {{-- Unit --}}
                    <div>
                        <x-input-label for="unit" :value="__('Unit')" />
                        <x-text-input id="unit" name="unit" type="text" value="{{ old('unit') }}"
                            class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('unit')" class="mt-2" />
                    </div>

                    {{-- Harga --}}
                    <div>
                        <x-input-label for="price" :value="__('Harga')" />
                        <x-text-input id="price" name="price" type="number" step="0.01"
                            value="{{ old('price', 0) }}" required class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('price')" class="mt-2" />
                    </div>

                    {{-- Status Aktif --}}
                    <div class="flex items-center">
                        <input id="is_active" name="is_active" type="checkbox" checked
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" />
                        <x-input-label for="is_active" :value="__('Aktif?')" class="ml-2" />
                    </div>

                    {{-- Tombol --}}
                    <div class="flex items-center justify-end space-x-4">
                        <a href="{{ route('items.index') }}" class="text-gray-600 hover:underline">
                            {{ __('Batal') }}
                        </a>
                        <x-primary-button>
                            {{ __('Simpan') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
