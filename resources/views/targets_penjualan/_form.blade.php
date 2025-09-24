@php
    $isEdit = isset($target);
@endphp

<div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-6 shadow-sm space-y-6">
    <div class="grid gap-5 md:grid-cols-2">
        {{-- Agent --}}
        <div>
            <label class="text-sm text-gray-600 dark:text-gray-300">Agent</label>
            <select name="agent_id" required
                class="mt-1 w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700">
                <option value="">— Pilih Agent —</option>
                @foreach ($agents as $a)
                    <option value="{{ $a->id }}" @selected(old('agent_id', $target->agent_id ?? '') == $a->id)>
                        {{ $a->name }}
                    </option>
                @endforeach
            </select>
            @error('agent_id')
                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Produk --}}
        <div>
            <label class="text-sm text-gray-600 dark:text-gray-300">Produk (opsional)</label>
            <select name="product_id"
                class="mt-1 w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700">
                <option value="">— Semua Produk —</option>
                @foreach ($products as $p)
                    <option value="{{ $p->id }}" @selected(old('product_id', $target->product_id ?? '') == $p->id)>
                        {{ $p->name }}
                    </option>
                @endforeach
            </select>
            @error('product_id')
                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Period --}}
        <div>
            <label class="text-sm text-gray-600 dark:text-gray-300">Periode</label>
            <select name="period" required
                class="mt-1 w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700">
                @foreach (['monthly' => 'Monthly', 'quarterly' => 'Quarterly', 'annual' => 'Annual'] as $k => $v)
                    <option value="{{ $k }}" @selected(old('period', $target->period ?? 'monthly') === $k)>{{ $v }}</option>
                @endforeach
            </select>
            @error('period')
                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Date range --}}
        <div>
            <label class="text-sm text-gray-600 dark:text-gray-300">Tanggal (dari — sampai)</label>
            <div class="mt-1 grid grid-cols-2 gap-2">
                <input type="date" name="start_date" required
                    value="{{ old('start_date', optional($target->start_date ?? null)->format('Y-m-d')) }}"
                    class="w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700" />
                <input type="date" name="end_date" required
                    value="{{ old('end_date', optional($target->end_date ?? null)->format('Y-m-d')) }}"
                    class="w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700" />
            </div>
            @error('start_date')
                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
            @enderror
            @error('end_date')
                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- KPI --}}
        <div>
            <label class="text-sm text-gray-600 dark:text-gray-300">Target Premi (Rp)</label>
            <input type="number" step="0.01" min="0" name="target_premium" required
                value="{{ old('target_premium', $target->target_premium ?? '') }}"
                class="mt-1 w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700" />
            @error('target_premium')
                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="text-sm text-gray-600 dark:text-gray-300">Target Case</label>
            <input type="number" step="1" min="0" name="target_case" required
                value="{{ old('target_case', $target->target_case ?? '') }}"
                class="mt-1 w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700" />
            @error('target_case')
                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Optional info --}}
        <div class="md:col-span-2">
            <label class="text-sm text-gray-600 dark:text-gray-300">Judul (opsional)</label>
            <input type="text" name="title" value="{{ old('title', $target->title ?? '') }}"
                class="mt-1 w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700"
                placeholder="Contoh: Target September - Health" />
            @error('title')
                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="md:col-span-2">
            <label class="text-sm text-gray-600 dark:text-gray-300">Catatan (opsional)</label>
            <textarea name="notes" rows="3"
                class="mt-1 w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700"
                placeholder="Catatan tambahan...">{{ old('notes', $target->notes ?? '') }}</textarea>
            @error('notes')
                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="md:col-span-2">
            <label class="inline-flex items-center gap-2">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $target->is_active ?? true) ? true : false)
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                <span class="text-sm text-gray-700 dark:text-gray-200">Aktif</span>
            </label>
            @error('is_active')
                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
