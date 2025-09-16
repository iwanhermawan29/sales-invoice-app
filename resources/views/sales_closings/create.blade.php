<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800">
            {{ __('Catat Closing') }} — {{ $sales_target->period }}
        </h2>
    </x-slot>

    <div class="p-6 max-w-lg mx-auto space-y-6 bg-white rounded-lg shadow">
        <form action="{{ route('sales-targets.closings.store', $sales_target) }}" method="POST" class="space-y-4">
            @csrf

            {{-- Pilih Produk --}}
            <div>
                <label for="product_id" class="block font-medium text-gray-700">{{ __('Produk') }}</label>
                <select name="product_id" id="product_id" required
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    @foreach (\App\Models\Product::all() as $product)
                        <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                            {{ $product->type_label }} — {{ $product->name }}
                        </option>
                    @endforeach
                </select>
                @error('product_id')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            {{-- Customer --}}
            <div>
                <label for="customer" class="block font-medium text-gray-700">{{ __('Customer') }}</label>
                <input type="text" name="customer" id="customer" value="{{ old('customer') }}" required
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
                @error('customer')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            {{-- Polis --}}
            <div>
                <label for="policy_number" class="block font-medium text-gray-700">{{ __('Nomor Polis') }}</label>
                <input type="text" name="policy_number" id="policy_number" value="{{ old('policy_number') }}"
                    required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
                @error('policy_number')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            {{-- Premi --}}
            <div>
                <label for="premium_amount" class="block font-medium text-gray-700">{{ __('Nilai Premi') }}</label>
                <input type="number" name="premium_amount" id="premium_amount" step="0.01"
                    value="{{ old('premium_amount') }}" required
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
                @error('premium_amount')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tanggal --}}
            <div>
                <label for="closing_date" class="block font-medium text-gray-700">{{ __('Tanggal Closing') }}</label>
                <input type="date" name="closing_date" id="closing_date"
                    value="{{ old('closing_date', now()->toDateString()) }}" required
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
                @error('closing_date')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            {{-- Notes --}}
            <div>
                <label for="notes" class="block font-medium text-gray-700">{{ __('Catatan') }}</label>
                <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            {{-- Buttons --}}
            <div class="flex justify-end space-x-3">
                <a href="{{ route('sales-targets.index') }}"
                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md">
                    {{ __('Batal') }}
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md">
                    {{ __('Simpan Closing') }}
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
