<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah User') }}
        </h2>
    </x-slot>

    <div class="p-6">
        <form action="{{ route('users.store') }}" method="POST" class="max-w-lg mx-auto space-y-6">
            @csrf

            @include('users._form', ['roles' => $roles])

            <div class="flex justify-end space-x-4">
                <a href="{{ route('users.index') }}" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
