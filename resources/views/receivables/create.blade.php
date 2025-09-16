{{-- resources/views/receivables/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2>Buat Receivable</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow">
                <form method="POST" action="{{ route('receivables.store') }}" class="space-y-4">
                    @csrf

                    {{-- Invoice --}}
                    <div>
                        <x-input-label for="invoice_id" :value="__('Invoice')" />
                        <select id="invoice_id" name="invoice_id" required
                            class="mt-1 block w-full border-gray-300 rounded">
                            <option value="">-- Pilih Invoice --</option>
                            @foreach ($invoices as $inv)
                                <option value="{{ $inv->id }}">
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
                        <x-text-input id="due_date" name="due_date" type="date" value="{{ old('due_date') }}"
                            required class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('due_date')" class="mt-1" />
                    </div>

                    {{-- Amount Due --}}
                    <div>
                        <x-input-label for="amount_due" :value="__('Amount Due')" />
                        <x-text-input id="amount_due" name="amount_due" type="number" step="0.01"
                            value="{{ old('amount_due') }}" required class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('amount_due')" class="mt-1" />
                    </div>

                    {{-- Amount Paid --}}
                    <div>
                        <x-input-label for="amount_paid" :value="__('Amount Paid')" />
                        <x-text-input id="amount_paid" name="amount_paid" type="number" step="0.01"
                            value="{{ old('amount_paid', 0) }}" class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('amount_paid')" class="mt-1" />
                    </div>

                    {{-- Status --}}
                    <div>
                        <x-input-label for="status" :value="__('Status')" />
                        <select id="status" name="status" required
                            class="mt-1 block w-full border-gray-300 rounded">
                            @foreach (['unpaid', 'partial', 'paid'] as $s)
                                <option value="{{ $s }}" {{ old('status') == $s ? 'selected' : '' }}>
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
                        <x-primary-button>Simpan</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
