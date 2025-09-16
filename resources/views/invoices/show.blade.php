{{-- resources/views/invoices/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2>Detail Invoice</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="bg-white p-6 rounded-lg shadow space-y-2">
                <div><strong>Invoice#:</strong> {{ $invoice->invoice_number }}</div>
                <div><strong>DO#:</strong> {{ $invoice->deliveryOrder->do_number }}</div>
                <div><strong>Customer:</strong> {{ $invoice->deliveryOrder->salesOrder->customer->name }}</div>
                <div><strong>Invoice Date:</strong> {{ $invoice->invoice_date->format('d-m-Y') }}</div>
                <div><strong>Due Date:</strong> {{ optional($invoice->due_date)->format('d-m-Y') }}</div>
                <div><strong>Status:</strong> {{ ucfirst($invoice->status) }}</div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="font-medium">Line Items</h3>
                <ul class="list-disc ml-6 space-y-1">
                    @foreach ($invoice->items as $it)
                        <li>
                            {{ $it->salesOrderItem->item->name }} —
                            {{-- correct usage of number_format --}}
                            {{ number_format($it->amount, 2, ',', '.') }}
                        </li>
                    @endforeach
                </ul>
            </div>
            <a href="{{ route('invoices.index') }}" class="text-blue-600 hover:underline">Kembali</a>
        </div>
    </div>
</x-app-layout>
