{{-- resources/views/sales_targets/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800">{{ __('Daftar Target Penjualan') }}</h2>
    </x-slot>

    <div class="p-6 space-y-6">
        {{-- Tombol Tambah --}}
        @if (auth()->user()->isRole('admin'))
            <div class="flex justify-end">
                <a href="{{ route('sales-targets.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow">
                    <!-- Heroicon Plus -->
                    <x-heroicon-o-plus class="w-5 h-5 mr-2" />
                    {{ __('Tambah Target') }}
                </a>
            </div>
        @endif

        {{-- Flash message --}}
        @if (session('success'))
            <div class="p-4 bg-green-100 text-green-800 rounded-lg shadow">
                {{ session('success') }}
            </div>
        @endif

        {{-- Daftar target sebagai grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($targets as $t)
                @php
                    // Hitung persen tercapai
                    $closed = $t->target_amount - ($t->remaining_target ?? $t->target_amount);
                    $percent = $t->target_amount > 0 ? round(($closed / $t->target_amount) * 100) : 0;
                @endphp

                <div class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow">
                    <div class="p-5">
                        <div class="flex justify-between items-center mb-3">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800">{{ $t->user->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $t->period }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xl font-bold text-blue-600">
                                    Rp {{ number_format($t->remaining_target ?? $t->target_amount, 2, ',', '.') }}
                                </p>
                                <p class="text-xs text-gray-500">{{ __('Sisa target') }}</p>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                                <div class="h-2.5 bg-blue-600" style="width: {{ $percent }}%"></div>
                            </div>
                            <p class="mt-1 text-xs text-gray-600">{{ $percent }}% tercapai</p>
                        </div>

                        @if ($t->notes)
                            <p class="text-sm text-gray-700 mb-4"><strong>{{ __('Catatan:') }}</strong>
                                {{ $t->notes }}</p>
                        @endif

                        <div class="flex flex-wrap gap-2 justify-end">
                            @if (auth()->user()->isRole('agent'))
                                <a href="{{ route('sales-targets.closings.create', $t) }}"
                                    class="px-3 py-1 bg-green-500 hover:bg-green-600 text-white text-sm rounded">
                                    {{ __('Catat Closing') }}
                                </a>
                            @endif
                            @if (auth()->user()->isRole('admin'))
                                <a href="{{ route('sales-targets.closings.index', $t) }}"
                                    class="px-3 py-1 bg-indigo-500 hover:bg-indigo-600 text-white text-sm rounded">
                                    {{ __('Detail') }}
                                </a>
                                <a href="{{ route('sales-targets.edit', $t) }}"
                                    class="px-3 py-1 bg-yellow-400 hover:bg-yellow-500 text-white text-sm rounded">
                                    {{ __('Edit') }}
                                </a>
                                <form action="{{ route('sales-targets.destroy', $t) }}" method="POST" class="inline"
                                    onsubmit="return confirm('{{ __('Hapus target ini?') }}')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-sm rounded">
                                        {{ __('Hapus') }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <p class="col-span-full text-center text-gray-500">{{ __('Belum ada target penjualan.') }}</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
