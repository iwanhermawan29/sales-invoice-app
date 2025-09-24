<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Tambah Penjualan</h2>
    </x-slot>
    <div class="p-6">
        <div
            class="mx-auto max-w-3xl rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-6">
            <form method="POST" action="{{ route('sales.store') }}">
                @include('sales._form')
            </form>
        </div>
    </div>
</x-app-layout>
