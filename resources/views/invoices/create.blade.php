{{-- resources/views/invoices/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Invoice') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 bg-white rounded-lg shadow">
                <form method="POST" action="{{ route('invoices.store') }}" {{-- wrap all of x-data in single quotes to avoid Blade/JS conflicts --}}
                    x-data='{
                        lines: [{ sales_order_item_id:"", qty:1, unit_price:0 }],
                        deliveryOrders: {!! $deliveryOrders->load('salesOrder.customer', 'items.salesOrderItem.item')->toJson() !!},
                        addLine() { this.lines.push({ sales_order_item_id:"", qty:1, unit_price:0 }); },
                        removeLine(i) { this.lines.splice(i,1); }
                    }'
                    class="space-y-6">
                    @csrf

                    {{-- Header fields --}}
                    <div class="grid grid-cols-2 gap-4">
                        {{-- Invoice Number --}}
                        <div>
                            <x-input-label for="invoice_number" :value="__('Invoice Number')" />
                            <x-text-input id="invoice_number" name="invoice_number" type="text"
                                value="{{ old('invoice_number', $invNumber) }}" readonly
                                class="mt-1 block w-full bg-gray-100" />
                            <x-input-error :messages="$errors->get('invoice_number')" class="mt-2" />
                        </div>

                        {{-- Invoice Date --}}
                        <div>
                            <x-input-label for="invoice_date" :value="__('Invoice Date')" />
                            <x-text-input id="invoice_date" name="invoice_date" type="date"
                                value="{{ old('invoice_date', now()->toDateString()) }}" required
                                class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('invoice_date')" class="mt-2" />
                        </div>

                        {{-- Due Date --}}
                        <div>
                            <x-input-label for="due_date" :value="__('Due Date')" />
                            <x-text-input id="due_date" name="due_date" type="date" value="{{ old('due_date') }}"
                                class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
                        </div>

                        {{-- Delivery Order --}}
                        <div>
                            <x-input-label for="delivery_order_id" :value="__('Delivery Order')" />
                            <select id="delivery_order_id" name="delivery_order_id" required
                                class="mt-1 block w-full border-gray-300 rounded">
                                <option value="">-- Pilih DO --</option>
                                @foreach ($deliveryOrders as $do)
                                    <option value="{{ $do->id }}"
                                        {{ old('delivery_order_id') == $do->id ? 'selected' : '' }}>
                                        {{ $do->do_number }} – {{ $do->salesOrder->customer->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('delivery_order_id')" class="mt-2" />
                        </div>
                    </div>

                    {{-- Dynamic Line Items --}}
                    <div class="space-y-4">
                        <template x-for="(line, i) in lines" :key="i">
                            <div class="flex space-x-2 items-end">
                                {{-- SO Item selector --}}
                                <div class="flex-1">
                                    <label x-bind:for="'line_item_' + i"
                                        class="block text-sm font-medium text-gray-700">{{ __('SO Item') }}</label>
                                    <select x-bind:id="'line_item_' + i"
                                        x-bind:name="'lines[' + i + '][sales_order_item_id]'"
                                        x-model="line.sales_order_item_id"
                                        class="mt-1 block w-full border-gray-300 rounded">
                                        <option value="">-- Pilih Item --</option>
                                        <template x-for="order in deliveryOrders" :key="order.id">
                                            <optgroup
                                                x-bind:label="order.do_number + ' – ' + order.sales_order.customer.name">
                                                <template x-for="itm in order.items" :key="itm.id">
                                                    <option x-bind:value="itm.sales_order_item_id"
                                                        x-text="itm.sales_order_item.item.name
                                                         + ' (qty: ' + itm.sales_order_item.quantity + ')'" />
                                                </template>
                                            </optgroup>
                                        </template>
                                    </select>
                                </div>

                                {{-- Qty --}}
                                <div class="w-24">
                                    <label x-bind:for="'line_qty_' + i"
                                        class="block text-sm font-medium text-gray-700">{{ __('Qty') }}</label>
                                    <input x-bind:id="'line_qty_' + i" x-bind:name="'lines[' + i + '][qty]'"
                                        x-model.number="line.qty" type="number" min="1"
                                        class="mt-1 block w-full border-gray-300 rounded" />
                                </div>

                                {{-- Unit Price --}}
                                <div class="w-32">
                                    <label x-bind:for="'line_price_' + i"
                                        class="block text-sm font-medium text-gray-700">{{ __('Unit Price') }}</label>
                                    <input x-bind:id="'line_price_' + i" x-bind:name="'lines[' + i + '][unit_price]'"
                                        x-model.number="line.unit_price" type="number" step="0.01"
                                        class="mt-1 block w-full border-gray-300 rounded" />
                                </div>

                                {{-- Remove Line --}}
                                <button type="button" @click="removeLine(i)" class="mt-6 text-red-500">×</button>
                            </div>
                        </template>

                        <button type="button" @click="addLine()" class="text-blue-600">+ Add Line</button>
                    </div>

                    {{-- Form Actions --}}
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('invoices.index') }}"
                            class="text-gray-600 hover:underline">{{ __('Batal') }}</a>
                        <x-primary-button>{{ __('Simpan Invoice') }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
