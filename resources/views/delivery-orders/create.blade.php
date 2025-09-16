{{-- resources/views/delivery-orders/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">{{ __('Buat Delivery Order') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 bg-white rounded-lg shadow">
                <form method="POST" action="{{ route('delivery-orders.store') }}" {{-- Perhatikan: kita gunakan single‐quoted attribute di x-data --}}
                    x-data='{
                        lines: [{ sales_order_item_id: "", shipped_qty: 1 }],
                        salesOrders: {!! $salesOrders->load('customer', 'items.item')->toJson() !!},
                        addLine() { this.lines.push({ sales_order_item_id: "", shipped_qty: 1 }); },
                        removeLine(i) { this.lines.splice(i, 1); }
                    }'
                    class="space-y-6">
                    @csrf

                    {{-- Header fields --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="do_number" :value="__('DO Number')" />
                            <x-text-input id="do_number" name="do_number" type="text"
                                value="{{ old('do_number', $doNumber) }}" readonly
                                class="mt-1 block w-full bg-gray-100" />
                            <x-input-error :messages="$errors->get('do_number')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="delivery_date" :value="__('Delivery Date')" />
                            <x-text-input id="delivery_date" name="delivery_date" type="date"
                                value="{{ old('delivery_date', now()->toDateString()) }}" required
                                class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('delivery_date')" class="mt-2" />
                        </div>

                        <div class="col-span-2">
                            <x-input-label for="sales_order_id" :value="__('Sales Order')" />
                            <select id="sales_order_id" name="sales_order_id" required
                                class="mt-1 block w-full border-gray-300 rounded">
                                <option value="">-- Pilih SO --</option>
                                @foreach ($salesOrders as $so)
                                    <option value="{{ $so->id }}"
                                        {{ old('sales_order_id') == $so->id ? 'selected' : '' }}>
                                        {{ $so->so_number }} – {{ $so->customer->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('sales_order_id')" class="mt-2" />
                        </div>
                    </div>

                    {{-- Detail Lines --}}
                    <div class="space-y-4">
                        <template x-for="(line, i) in lines" :key="i">
                            <div class="flex space-x-2 items-end">
                                {{-- Pilih SO Item --}}
                                <div class="flex-1">
                                    <label x-bind:for="'soitem_' + i"
                                        class="block font-medium text-sm text-gray-700">{{ __('SO Item') }}</label>
                                    <select x-bind:id="'soitem_' + i"
                                        x-bind:name="'lines[' + i + '][sales_order_item_id]'"
                                        x-model="line.sales_order_item_id"
                                        class="mt-1 block w-full border-gray-300 rounded">
                                        <option value="">-- Pilih Item SO --</option>
                                        <template x-for="so in salesOrders" :key="so.id">
                                            <optgroup x-bind:label="so.so_number + ' – ' + so.customer.name">
                                                <template x-for="item in so.items" :key="item.id">
                                                    <option x-bind:value="item.id"
                                                        x-text="item.item.name + ' (sisa: ' + item.quantity + ')'">
                                                    </option>
                                                </template>
                                            </optgroup>
                                        </template>
                                    </select>
                                </div>

                                {{-- Qty --}}
                                <div class="w-24">
                                    <label x-bind:for="'qty_' + i"
                                        class="block font-medium text-sm text-gray-700">{{ __('Qty') }}</label>
                                    <input x-bind:id="'qty_' + i" x-bind:name="'lines[' + i + '][shipped_qty]'"
                                        x-model.number="line.shipped_qty" type="number" min="1"
                                        class="mt-1 block w-full border-gray-300 rounded" />
                                </div>

                                {{-- Remove Line --}}
                                <button type="button" @click="removeLine(i)" class="mt-6 text-red-500">×</button>
                            </div>
                        </template>

                        {{-- Add Line --}}
                        <button type="button" @click="addLine()" class="text-blue-600">+ Add Line</button>
                    </div>

                    {{-- Actions --}}
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('delivery-orders.index') }}"
                            class="text-gray-600 hover:underline">{{ __('Batal') }}</a>
                        <x-primary-button>{{ __('Simpan DO') }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
