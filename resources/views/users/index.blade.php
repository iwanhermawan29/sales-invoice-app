<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Management') }}
        </h2>
    </x-slot>

    <div class="p-6">
        <div class="flex justify-end mb-4">
            <a href="{{ route('users.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                {{ __('Tambah User') }}
            </a>
        </div>
    </div>

    <div class="p-6 space-y-4">
        @forelse($users as $user)
            <div class="bg-white p-4 rounded-lg shadow flex justify-between items-center">
                <div>
                    <div class="font-medium">{{ $user->name }}</div>
                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('users.edit', $user) }}"
                        class="px-3 py-1 bg-yellow-400 text-white rounded hover:bg-yellow-500">
                        {{ __('Edit') }}
                    </a>
                    <form action="{{ route('users.destroy', $user) }}" method="POST"
                        onsubmit="return confirm('{{ __('Hapus user ini?') }}');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                            {{ __('Hapus') }}
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <p class="text-gray-500">{{ __('Belum ada user.') }}</p>
        @endforelse

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</x-app-layout>
