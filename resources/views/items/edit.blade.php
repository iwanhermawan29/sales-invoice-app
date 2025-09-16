{{-- resources/views/items/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Item') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="p-6 bg-white rounded-lg shadow">
                <form action="{{ route('items.update', $item) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- Kode (readonly) --}}
                    <div>
                        <x-input-label for="code" :value="__('Kode')" />
                        <x-text-input id="code" name="code" type="text" value="{{ old('code', $item->code) }}"
                            readonly class="mt-1 block w-full bg-gray-100 cursor-not-allowed" />
                        <x-input-error :messages="$errors->get('code')" class="mt-2" />
                    </div>

                    {{-- Nama --}}
                    <div>
                        <x-input-label for="name" :value="__('Nama')" />
                        <x-text-input id="name" name="name" type="text"
                            value="{{ old('name', $item->name) }}" required class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    {{-- Unit --}}
                    <div>
                        <x-input-label for="unit" :value="__('Unit')" />
                        <x-text-input id="unit" name="unit" type="text"
                            value="{{ old('unit', $item->unit) }}" class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('unit')" class="mt-2" />
                    </div>

                    {{-- Harga --}}
                    <div>
                        <x-input-label for="price" :value="__('Harga')" />
                        <x-text-input id="price" name="price" type="number" step="0.01"
                            value="{{ old('price', $item->price) }}" required class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('price')" class="mt-2" />
                    </div>

                    {{-- Status Aktif --}}
                    <div class="flex items-center">
                        <input id="is_active" name="is_active" type="checkbox"
                            {{ old('is_active', $item->is_active) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" />
                        <x-input-label for="is_active" :value="__('Aktif?')" class="ml-2" />
                    </div>
                    <x-input-error :messages="$errors->get('is_active')" class="mt-2" />

                    {{-- Tombol --}}
                    <div class="flex items-center justify-end space-x-4">
                        <a href="{{ route('items.index') }}" class="text-gray-600 hover:underline">
                            {{ __('Batal') }}
                        </a>
                        <x-primary-button>
                            {{ __('Update') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
