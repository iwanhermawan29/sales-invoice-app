<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Target Penjualan') }}
        </h2>
    </x-slot>

    <div class="p-6 max-w-lg mx-auto">
        <form action="{{ route('sales-targets.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="period" class="block font-medium">{{ __('Periode (YYYY-MM)') }}</label>
                <input type="text" name="period" id="period" value="{{ old('period') }}" required
                    class="w-full mt-1 p-2 border rounded" placeholder="2025-08" />
                @error('period')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="target_amount" class="block font-medium">{{ __('Target Penjualan') }}</label>
                <input type="number" step="0.01" name="target_amount" id="target_amount"
                    value="{{ old('target_amount') }}" required class="w-full mt-1 p-2 border rounded"
                    placeholder="10000000" />
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
                <a href="{{ route('sales-targets.index') }}" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                    {{ __('Batal') }}
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    {{ __('Simpan') }}
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
