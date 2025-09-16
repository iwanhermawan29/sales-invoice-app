<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">{{ __('Detail Sales Order') }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 bg-white rounded-lg shadow space-y-4">
                <div><strong>SO Number:</strong> {{ $salesOrder->so_number }}</div>
                <div><strong>Customer:</strong> {{ $salesOrder->customer->name }}</div>
                <div>
                    <strong>Order Date:</strong>
                    {{ \Carbon\Carbon::parse($salesOrder->order_date)->format('d-m-Y') }}
                </div>
                <div><strong>Status:</strong> {{ ucfirst($salesOrder->status) }}</div>
                <div><strong>Items:</strong>
                    <ul class="list-disc ml-5">
                        @foreach ($salesOrder->items as $line)
                            <li>{{ $line->item->name }} — {{ $line->quantity }} x
                                {{ 'Rp ' . number_format($line->unit_price, 2, ',', '.') }}</li>
                        @endforeach
                    </ul>
                </div>
                <a href="{{ route('sales-orders.index') }}" class="text-blue-600 hover:underline">Kembali</a>
            </div>
        </div>
    </div>
</x-app-layout>
