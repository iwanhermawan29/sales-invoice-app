{{-- resources/views/receivables/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2>Detail Receivable</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="bg-white p-6 rounded-lg shadow space-y-2">
                <div><strong>Invoice#:</strong> {{ $receivable->invoice->invoice_number }}</div>
                <div>
                    <strong>Customer:</strong>
                    {{ optional($receivable->invoice->deliveryOrder->salesOrder->customer)->name }}
                </div>
                <div>
                    <strong>Due Date:</strong>
                    {{ \Carbon\Carbon::parse($receivable->due_date)->format('d-m-Y') }}
                </div>
                <div>
                    <strong>Amount Due:</strong>
                    {{ number_format($receivable->amount_due, 2, ',', '.') }}
                </div>
                <div>
                    <strong>Amount Paid:</strong>
                    {{ number_format($receivable->amount_paid, 2, ',', '.') }}
                </div>
                <div>
                    <strong>Status:</strong>
                    {{ ucfirst($receivable->status) }}
                </div>
            </div>

            <a href="{{ route('receivables.index') }}" class="text-blue-600 hover:underline">
                Kembali
            </a>
        </div>
    </div>
</x-app-layout>
