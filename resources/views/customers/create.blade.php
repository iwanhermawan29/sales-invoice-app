<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">{{ __('Tambah Pelanggan') }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="p-6 bg-white rounded-lg shadow">
                <form action="{{ route('customers.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <x-input-label for="code" :value="__('Kode')" />
                        <x-text-input id="code" name="code" type="text" value="{{ old('code', $code) }}"
                            readonly class="mt-1 block w-full bg-gray-100 cursor-not-allowed" />
                        <x-input-error :messages="$errors->get('code')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="name" :value="__('Nama')" />
                        <x-text-input id="name" name="name" type="text" value="{{ old('name') }}" required
                            class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="address" :value="__('Alamat')" />
                        <textarea id="address" name="address" class="mt-1 block w-full border rounded">{{ old('address') }}</textarea>
                    </div>
                    <div>
                        <x-input-label for="phone" :value="__('Telepon')" />
                        <x-text-input id="phone" name="phone" type="text" value="{{ old('phone') }}"
                            class="mt-1 block w-full" />
                    </div>
                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" name="email" type="email" value="{{ old('email') }}"
                            class="mt-1 block w-full" />
                    </div>
                    <div class="flex items-center">
                        <input id="is_active" name="is_active" type="checkbox" checked
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" />
                        <x-input-label for="is_active" :value="__('Aktif?')" class="ml-2" />
                    </div>
                    <div class="flex items-center justify-end space-x-4">
                        <a href="{{ route('customers.index') }}"
                            class="text-gray-600 hover:underline">{{ __('Batal') }}</a>
                        <x-primary-button>{{ __('Simpan') }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
