{{-- Invoice Edit --}}
@php
    // Prepare Alpine’s initial lines (fallback to old inputs)
    $initialLines = old(
        'lines',
        $invoice->items
            ->map(
                fn($it) => [
                    'sales_order_item_id' => $it->sales_order_item_id,
                    'qty' => $it->salesOrderItem->quantity,
                    'unit_price' => round($it->amount / max($it->salesOrderItem->quantity, 1), 2),
                ],
            )
            ->toArray(),
    );
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Edit Invoice</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 bg-white rounded-lg shadow">
                <form method="POST" action="{{ route('invoices.update', $invoice) }}"
                    x-data='{
            lines: {!! json_encode($initialLines) !!},
            deliveryOrders: {!! $deliveryOrders->toJson() !!},
            addLine()    { this.lines.push({ sales_order_item_id:"", qty:1, unit_price:0 }); },
            removeLine(i){ this.lines.splice(i,1); }
          }'
                    class="space-y-6">
                    @csrf @method('PUT')

                    {{-- Header --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="invoice_number" value="Invoice Number" />
                            <x-text-input id="invoice_number" name="invoice_number" type="text"
                                value="{{ $invoice->invoice_number }}" readonly class="mt-1 block w-full bg-gray-100" />
                        </div>
                        <div>
                            <x-input-label for="invoice_date" value="Invoice Date" />
                            <x-text-input id="invoice_date" name="invoice_date" type="date"
                                value="{{ $invoice->invoice_date->format('Y-m-d') }}" required
                                class="mt-1 block w-full" />
                        </div>
                        <div>
                            <x-input-label for="due_date" value="Due Date" />
                            <x-text-input id="due_date" name="due_date" type="date"
                                value="{{ optional($invoice->due_date)->format('Y-m-d') }}" class="mt-1 block w-full" />
                        </div>
                        <div>
                            <x-input-label for="delivery_order_id" value="Delivery Order" />
                            <select id="delivery_order_id" name="delivery_order_id" required
                                class="mt-1 block w-full border-gray-300 rounded">
                                <option value="">-- Pilih DO --</option>
                                @foreach ($deliveryOrders as $do)
                                    <option value="{{ $do->id }}"
                                        {{ $invoice->delivery_order_id == $do->id ? 'selected' : '' }}>
                                        {{ $do->do_number }} – {{ $do->salesOrder->customer->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Dynamic Lines --}}
                    <div class="space-y-4">
                        <template x-for="(line,i) in lines" :key="i">
                            <div class="flex space-x-2 items-end">
                                {{-- SO Item --}}
                                <div class="flex-1">
                                    <label :for="'line_item_' + i" class="block text-sm font-medium text-gray-700">
                                        SO Item
                                    </label>
                                    <select :id="'line_item_' + i" :name="'lines[' + i + '][sales_order_item_id]'"
                                        x-model="line.sales_order_item_id"
                                        class="mt-1 block w-full border-gray-300 rounded">
                                        <option value="">-- Pilih Item --</option>

                                        <!-- note snake_case relation keys! -->
                                        <template x-for="order in deliveryOrders" :key="order.id">
                                            <optgroup
                                                :label="order.do_number + ' – ' + order.sales_order.customer.name">
                                                <template x-for="itm in order.items" :key="itm.id">
                                                    <option :value="itm.sales_order_item_id"
                                                        x-text="itm.sales_order_item.item.name
                              + ' (sisa: ' + itm.sales_order_item.quantity + ')'">
                                                    </option>
                                                </template>
                                            </optgroup>
                                        </template>
                                    </select>
                                </div>

                                {{-- Qty --}}
                                <div class="w-24">
                                    <label :for="'line_qty_' + i" class="block text-sm font-medium text-gray-700">
                                        Qty
                                    </label>
                                    <input :id="'line_qty_' + i" :name="'lines[' + i + '][qty]'" x-model.number="line.qty"
                                        type="number" min="1"
                                        class="mt-1 block w-full border-gray-300 rounded" />
                                </div>

                                {{-- Unit Price --}}
                                <div class="w-32">
                                    <label :for="'line_price_' + i" class="block text-sm font-medium text-gray-700">
                                        Unit Price
                                    </label>
                                    <input :id="'line_price_' + i" :name="'lines[' + i + '][unit_price]'"
                                        x-model.number="line.unit_price" type="number" step="0.01"
                                        class="mt-1 block w-full border-gray-300 rounded" />
                                </div>

                                {{-- Remove --}}
                                <button type="button" @click="removeLine(i)" class="mt-6 text-red-500">
                                    ×
                                </button>
                            </div>
                        </template>

                        <button type="button" @click="addLine()" class="text-blue-600">
                            + Add Line
                        </button>
                    </div>

                    {{-- Actions --}}
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('invoices.index') }}" class="text-gray-600 hover:underline">
                            Batal
                        </a>
                        <x-primary-button>Update Invoice</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
