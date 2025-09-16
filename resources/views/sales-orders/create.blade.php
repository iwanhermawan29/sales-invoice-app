<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">{{ __('Buat Sales Order') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 bg-white rounded-lg shadow">
                <form action="{{ route('sales-orders.store') }}" method="POST" class="space-y-6" x-data="{ lines: [{ item_id: '', qty: 1, price: 0 }] }">
                    @csrf
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="so_number" :value="__('SO Number')" />
                            <x-text-input id="so_number" name="so_number" type="text"
                                value="{{ old('so_number', $soNumber) }}" readonly
                                class="mt-1 block w-full bg-gray-100" />
                            <x-input-error :messages="$errors->get('so_number')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="order_date" :value="__('Order Date')" />
                            <x-text-input id="order_date" name="order_date" type="date"
                                value="{{ old('order_date', now()->toDateString()) }}" required
                                class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('order_date')" class="mt-2" />
                        </div>
                        <div class="col-span-2">
                            <x-input-label for="customer_id" :value="__('Customer')" />
                            <select id="customer_id" name="customer_id" required
                                class="mt-1 block w-full border-gray-300 rounded">
                                <option value="">-- Pilih Customer --</option>
                                @foreach ($customers as $c)
                                    <option value="{{ $c->id }}"
                                        {{ old('customer_id') == $c->id ? 'selected' : '' }}>
                                        {{ $c->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('customer_id')" class="mt-2" />
                        </div>
                    </div>

                    {{-- Detail Lines --}}
                    <div class="space-y-2">
                        <template x-for="(line, idx) in lines" :key="idx">
                            <div class="flex space-x-2 items-end">
                                <div class="flex-1">
                                    <x-input-label x-bind:for="'item_' + idx" :value="__('Item')" />
                                    <select x-bind:id="'item_' + idx" x-bind:name="'lines[' + idx + '][item_id]'"
                                        x-model="line.item_id" class="mt-1 block w-full border-gray-300 rounded">
                                        <option value="">-- Pilih Item --</option>
                                        @foreach ($items as $it)
                                            <option value="{{ $it->id }}">{{ $it->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="w-24">
                                    <x-input-label x-bind:for="'qty_' + idx" :value="__('Qty')" />
                                    <x-text-input x-bind:id="'qty_' + idx" type="number" min="1"
                                        x-bind:name="'lines[' + idx + '][qty]'" x-model="line.qty"
                                        class="mt-1 block w-full" />
                                </div>
                                <div class="w-32">
                                    <x-input-label x-bind:for="'price_' + idx" :value="__('Unit Price')" />
                                    <x-text-input x-bind:id="'price_' + idx" type="number" step="0.01"
                                        x-bind:name="'lines[' + idx + '][unit_price]'" x-model="line.price"
                                        class="mt-1 block w-full" />
                                </div>
                                <button type="button" @click="lines.splice(idx,1)" class="mt-6 text-red-500">×</button>
                            </div>
                        </template>
                        <button type="button" @click="lines.push({item_id:'',qty:1,price:0})" class="text-blue-600">+
                            Add Line</button>
                    </div>

                    {{-- Submit --}}
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('sales-orders.index') }}"
                            class="text-gray-600 hover:underline">{{ __('Batal') }}</a>
                        <x-primary-button>{{ __('Simpan SO') }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
