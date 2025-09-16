<x-app-layout>
    <x-slot name="header">
        <h2>Detail Delivery Order</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow space-y-3">
                <div><strong>DO Number:</strong> {{ $deliveryOrder->do_number }}</div>
                <div><strong>SO Number:</strong> {{ $deliveryOrder->salesOrder->so_number }}</div>
                <div><strong>Customer:</strong> {{ $deliveryOrder->salesOrder->customer->name }}</div>
                <div><strong>Delivery Date:</strong> {{ $deliveryOrder->delivery_date->format('d-m-Y') }}</div>
                <div><strong>Status:</strong> {{ ucfirst($deliveryOrder->status) }}</div>
                <div><strong>Items:</strong>
                    <ul class="list-disc ml-5">
                        @foreach ($deliveryOrder->items as $li)
                            <li>
                                {{ $li->salesOrderItem->item->name }} —
                                {{ $li->shipped_qty }}
                            </li>
                        @endforeach
                    </ul>
                </div>
                <a href="{{ route('delivery-orders.index') }}" class="text-blue-600 hover:underline">Kembali</a>
            </div>
        </div>
    </div>
</x-app-layout>
