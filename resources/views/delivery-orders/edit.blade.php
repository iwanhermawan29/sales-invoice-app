{{-- resources/views/delivery-orders/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">{{ __('Edit Delivery Order') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 bg-white rounded-lg shadow">
                <form method="POST" action="{{ route('delivery-orders.update', $do) }}" {{-- x-data dibungkus single-quoted agar Blade tidak mem-parsing i atau lines --}}
                    x-data='{
                        lines: {!! json_encode(
                            old(
                                'lines',
                                $do->items->map(
                                        fn($i) => [
                                            'sales_order_item_id' => $i->sales_order_item_id,
                                            'shipped_qty' => $i->shipped_qty,
                                        ],
                                    )->toArray(),
                            ),
                        ) !!},
                        salesOrders: {!! $salesOrders->load('customer', 'items.item')->toJson() !!},
                        addLine() { this.lines.push({ sales_order_item_id:"", shipped_qty:1 }); },
                        removeLine(i) { this.lines.splice(i,1); }
                    }'
                    class="space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- Header fields --}}
                    <div class="grid grid-cols-2 gap-4">
                        {{-- DO Number (readonly) --}}
                        <div>
                            <x-input-label for="do_number" :value="__('DO Number')" />
                            <x-text-input id="do_number" name="do_number" type="text"
                                value="{{ old('do_number', $do->do_number) }}" readonly
                                class="mt-1 block w-full bg-gray-100" />
                        </div>

                        {{-- Delivery Date --}}
                        <div>
                            <x-input-label for="delivery_date" :value="__('Delivery Date')" />
                            <x-text-input id="delivery_date" name="delivery_date" type="date"
                                value="{{ old('delivery_date', $do->delivery_date->format('Y-m-d')) }}" required
                                class="mt-1 block w-full" />
                        </div>

                        {{-- Status --}}
                        <div class="col-span-2">
                            <x-input-label for="status" :value="__('Status')" />
                            <select id="status" name="status" required
                                class="mt-1 block w-full border-gray-300 rounded">
                                @foreach (['pending', 'shipped', 'delivered'] as $s)
                                    <option value="{{ $s }}"
                                        {{ old('status', $do->status) === $s ? 'selected' : '' }}>
                                        {{ ucfirst($s) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Detail Lines --}}
                    <div class="space-y-4">
                        <template x-for="(line, i) in lines" :key="i">
                            <div class="flex space-x-2 items-end">
                                {{-- SO Item selector --}}
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
                                                <template x-for="itm in so.items" :key="itm.id">
                                                    <option x-bind:value="itm.id"
                                                        x-text="itm.item.name + ' (sisa: ' + itm.quantity + ')'">
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

                        {{-- Add new line --}}
                        <button type="button" @click="addLine()" class="text-blue-600">+ Add Line</button>
                    </div>

                    {{-- Form Actions --}}
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('delivery-orders.index') }}"
                            class="text-gray-600 hover:underline">{{ __('Batal') }}</a>
                        <x-primary-button>{{ __('Update DO') }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
