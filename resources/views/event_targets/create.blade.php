<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Event Target') }}
        </h2>
    </x-slot>

    <div class="p-6 max-w-lg mx-auto">
        <form action="{{ route('event-targets.store') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label for="user_id" class="block font-medium">{{ __('Agent') }}</label>
                <select name="user_id" id="user_id" required class="w-full mt-1 p-2 border rounded">
                    @foreach ($agents as $agent)
                        <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                    @endforeach
                </select>
                @error('user_id')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="event_name" class="block font-medium">{{ __('Nama Event') }}</label>
                <input type="text" name="event_name" id="event_name" value="{{ old('event_name') }}" required
                    class="w-full mt-1 p-2 border rounded" />
                @error('event_name')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="year" class="block font-medium">{{ __('Tahun') }}</label>
                <input type="number" name="year" id="year" min="2000" max="2100"
                    value="{{ old('year') }}" required class="w-full mt-1 p-2 border rounded" />
                @error('year')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="target_amount" class="block font-medium">{{ __('Target Penjualan') }}</label>
                <input type="number" step="0.01" name="target_amount" id="target_amount"
                    value="{{ old('target_amount') }}" required class="w-full mt-1 p-2 border rounded" />
                @error('target_amount')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="notes" class="block font-medium">{{ __('Catatan') }}</label>
                <textarea name="notes" id="notes" rows="3" class="w-full mt-1 p-2 border rounded">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex justify-end space-x-4">
                <a href="{{ route('event-targets.index') }}"
                    class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">{{ __('Batal') }}</a>
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">{{ __('Simpan') }}</button>
            </div>
        </form>
    </div>
</x-app-layout>
