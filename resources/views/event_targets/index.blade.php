<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Event Target') }}
        </h2>
    </x-slot>

    <div class="p-6">
        {{-- Tambah Event Target (Admin only) --}}
        @if (auth()->user()->isRole('admin'))
            <div class="flex justify-end mb-4">
                <a href="{{ route('event-targets.create') }}"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    {{ __('Tambah Event Target') }}
                </a>
            </div>
        @endif

        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            {{ __('Agent') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            {{ __('Event') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            {{ __('Tahun') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            {{ __('Target') }}
                        </th>
                        @if (auth()->user()->isRole('admin'))
                            <th class="px-6 py-3"></th>
                        @endif
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($eventTargets as $et)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $et->user->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $et->event_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $et->year }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ number_format($et->target_amount, 2, ',', '.') }}
                            </td>

                            {{-- Aksi (Admin only) --}}
                            @if (auth()->user()->isRole('admin'))
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                    <a href="{{ route('event-targets.edit', $et) }}"
                                        class="text-indigo-600 hover:text-indigo-900">
                                        {{ __('Edit') }}
                                    </a>
                                    <form action="{{ route('event-targets.destroy', $et) }}" method="POST"
                                        class="inline"
                                        onsubmit="return confirm('{{ __('Hapus event target ini?') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            {{ __('Hapus') }}
                                        </button>
                                    </form>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->isRole('admin') ? 5 : 4 }}"
                                class="px-6 py-4 text-center text-gray-500">
                                {{ __('Belum ada event target.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $eventTargets->links() }}
        </div>
    </div>
</x-app-layout>
