<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail User') }}
        </h2>
    </x-slot>

    <div class="p-6 max-w-lg mx-auto bg-white rounded shadow">
        <p><strong>Nama:</strong> {{ $user->name }}</p>
        <p class="mt-2"><strong>Email:</strong> {{ $user->email }}</p>
        <p class="mt-2"><strong>Role:</strong> {{ $user->role->display_name }}</p>

        <div class="mt-6">
            <a href="{{ route('users.index') }}" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                Kembali
            </a>
        </div>
    </div>
</x-app-layout>
