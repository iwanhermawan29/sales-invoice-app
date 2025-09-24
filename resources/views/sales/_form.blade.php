@php($editing = isset($sale))
@csrf
<div class="grid gap-4 md:grid-cols-2">
    <div class="md:col-span-2">
        <label class="text-sm text-gray-600">Nama Nasabah</label>
        <input name="customer_name" class="mt-1 w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700"
            value="{{ old('customer_name', $sale->customer_name ?? '') }}" required>
        @error('customer_name')
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="text-sm text-gray-600">Tanggal Penjualan</label>
        <input type="date" name="sale_date"
            class="mt-1 w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700"
            value="{{ old('sale_date', isset($sale) ? $sale->sale_date->format('Y-m-d') : now()->toDateString()) }}"
            required>
        @error('sale_date')
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="text-sm text-gray-600">Produk</label>
        <select name="product_id" class="mt-1 w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700"
            required>
            <option value="">— pilih —</option>
            @foreach ($products as $p)
                <option value="{{ $p->id }}" @selected(old('product_id', $sale->product_id ?? null) == $p->id)>{{ $p->name }}</option>
            @endforeach
        </select>
        @error('product_id')
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="text-sm text-gray-600">Case</label>
        <input type="number" name="case_level" id="case_level" min="1" max="3"
            value="{{ old('case_level', $sale->case_level ?? 1) }}"
            class="mt-1 w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700">
        @error('case_level')
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="md:col-span-2">
        <label class="text-sm text-gray-600">Premi</label>
        <div class="mt-1 flex rounded-xl border border-gray-300 dark:bg-gray-800 dark:border-gray-700">
            <span class="inline-flex items-center px-3 text-gray-500">Rp</span>
            <input type="number" step="0.01" min="0" name="premium"
                class="w-full rounded-r-xl border-0 focus:ring-0 dark:bg-gray-800"
                value="{{ old('premium', $sale->premium ?? '') }}" required>
        </div>
        @error('premium')
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="mt-6 flex items-center justify-end gap-2">
    <a href="{{ route('sales.index') }}" class="rounded-xl border px-4 py-2">Batal</a>
    <button class="rounded-xl bg-blue-600 px-5 py-2 text-white hover:bg-blue-700">
        {{ $editing ? 'Perbarui' : 'Simpan' }}
    </button>
</div>
