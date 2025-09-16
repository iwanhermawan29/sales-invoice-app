{{-- resources/views/receivables/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2>Edit Receivable</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow">
                <form method="POST" action="{{ route('receivables.update', $receivable) }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    {{-- Invoice --}}
                    <div>
                        <x-input-label for="invoice_id" :value="__('Invoice')" />
                        <select id="invoice_id" name="invoice_id" required
                            class="mt-1 block w-full border-gray-300 rounded">
                            <option value="">-- Pilih Invoice --</option>
                            @foreach ($invoices as $inv)
                                <option value="{{ $inv->id }}"
                                    {{ old('invoice_id', $receivable->invoice_id) == $inv->id ? 'selected' : '' }}>
                                    {{ $inv->invoice_number }}
                                    – {{ $inv->deliveryOrder->salesOrder->customer->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('invoice_id')" class="mt-1" />
                    </div>

                    {{-- Due Date --}}
                    <div>
                        <x-input-label for="due_date" :value="__('Due Date')" />
                        <x-text-input id="due_date" name="due_date" type="date"
                            value="{{ old('due_date', $receivable->due_date->toDateString()) }}" required
                            class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('due_date')" class="mt-1" />
                    </div>

                    {{-- Amount Due --}}
                    <div>
                        <x-input-label for="amount_due" :value="__('Amount Due')" />
                        <x-text-input id="amount_due" name="amount_due" type="number" step="0.01"
                            value="{{ old('amount_due', $receivable->amount_due) }}" required
                            class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('amount_due')" class="mt-1" />
                    </div>

                    {{-- Amount Paid --}}
                    <div>
                        <x-input-label for="amount_paid" :value="__('Amount Paid')" />
                        <x-text-input id="amount_paid" name="amount_paid" type="number" step="0.01"
                            value="{{ old('amount_paid', $receivable->amount_paid) }}" class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('amount_paid')" class="mt-1" />
                    </div>

                    {{-- Status --}}
                    <div>
                        <x-input-label for="status" :value="__('Status')" />
                        <select id="status" name="status" required
                            class="mt-1 block w-full border-gray-300 rounded">
                            @foreach (['unpaid', 'partial', 'paid'] as $s)
                                <option value="{{ $s }}"
                                    {{ old('status', $receivable->status) == $s ? 'selected' : '' }}>
                                    {{ ucfirst($s) }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-1" />
                    </div>

                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('receivables.index') }}" class="text-gray-600 hover:underline">
                            Batal
                        </a>
                        <x-primary-button>Update</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
