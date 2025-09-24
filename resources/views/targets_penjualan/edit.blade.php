<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100">Edit Target Penjualan</h2>
            <a href="{{ route('targets-penjualan.index') }}" class="rounded-xl border px-4 py-2">Kembali</a>
        </div>
    </x-slot>

    <div class="p-6 space-y-6">
        <form action="{{ route('targets-penjualan.update', $target) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            @include('targets_penjualan._form', [
                'agents' => $agents,
                'products' => $products,
                'target' => $target,
            ])

            <div class="flex justify-end gap-3">
                <a href="{{ route('targets-penjualan.index') }}" class="rounded-xl border px-4 py-2">Batal</a>
                <button type="submit" class="rounded-xl bg-emerald-600 px-5 py-2 text-white hover:bg-emerald-700">
                    Update
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
