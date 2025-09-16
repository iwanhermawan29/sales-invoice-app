<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800">
            {{ __('Daftar Closing') }} — {{ $sales_target->period }}
        </h2>
    </x-slot>

    <div class="p-6 space-y-6">
        {{-- Ringkasan Target --}}
        <div class="bg-white p-5 rounded-lg shadow flex flex-col sm:flex-row sm:justify-between sm:items-center">
            <div class="flex-1">
                <p class="text-sm text-gray-500">{{ __('Original Target') }}</p>
                <p class="text-xl font-semibold">
                    Rp {{ number_format($sales_target->target_amount, 2, ',', '.') }}
                </p>
            </div>
            <div class="flex-1 mt-4 sm:mt-0">
                <p class="text-sm text-gray-500">{{ __('Total Closed') }}</p>
                <p class="text-xl font-semibold text-green-600">
                    Rp {{ number_format($sales_target->target_amount - $sales_target->remaining_target, 2, ',', '.') }}
                </p>
            </div>
            <div class="flex-1 mt-4 sm:mt-0">
                <p class="text-sm text-gray-500">{{ __('Sisa Target') }}</p>
                <p class="text-xl font-semibold text-blue-600">
                    Rp {{ number_format($sales_target->remaining_target, 2, ',', '.') }}
                </p>
            </div>
            <div class="mt-4 sm:mt-0">
                <a href="{{ route('sales-targets.index') }}"
                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md">
                    {{ __('Kembali ke Target') }}
                </a>
            </div>
        </div>

        {{-- Daftar Closing --}}
        @if ($closings->isEmpty())
            <div class="bg-white p-6 rounded-lg shadow text-center text-gray-500">
                {{ __('Belum ada closing untuk periode ini.') }}
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($closings as $c)
                    <div class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow">
                        <div class="p-5 flex flex-col h-full">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-500">{{ $c->closing_date }}</span>
                                <span class="text-sm text-gray-500">{{ __('Polis:') }} {{ $c->policy_number }}</span>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">{{ $c->customer }}</h3>
                            <p class="mt-2 text-xl font-bold text-green-600">
                                Rp {{ number_format($c->premium_amount, 2, ',', '.') }}
                            </p>
                            @if ($c->product)
                                <p class="mt-2 text-sm text-gray-600">
                                    {{ __('Produk:') }} {{ $c->product->type_label }} — {{ $c->product->name }}
                                </p>
                            @endif
                            @if ($c->notes)
                                <p class="mt-3 text-sm text-gray-600 italic">
                                    {{ __('Catatan:') }} {{ $c->notes }}
                                </p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
