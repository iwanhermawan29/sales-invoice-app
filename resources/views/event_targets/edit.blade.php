<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Event Target') }}
        </h2>
    </x-slot>

    <div class="p-6 max-w-lg mx-auto">
        <form action="{{ route('event-targets.update', $eventTarget) }}" method="POST" class="space-y-6">
            @csrf @method('PATCH')
            @include('event_targets._form', ['eventTarget' => $eventTarget, 'agents' => $agents])
            <div class="flex justify-end space-x-4">
                <a href="{{ route('event-targets.index') }}"
                    class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">{{ __('Batal') }}</a>
                <button type="submit"
                    class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">{{ __('Update') }}</button>
            </div>
        </form>
    </div>
</x-app-layout>
