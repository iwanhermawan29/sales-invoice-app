{{-- resources/views/contests/_form.blade.php --}}
@php $isEdit = isset($contest); @endphp

<div class="space-y-6">
    {{-- Nama Kontes --}}
    <div>
        <label class="block text-sm font-medium text-gray-700">Nama Kontes</label>
        <input type="text" name="nama_kontes" required value="{{ old('nama_kontes', $contest->nama_kontes ?? '') }}"
            class="mt-1 w-full rounded-xl border-gray-300">
        @error('nama_kontes')
            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Periode (enum) --}}
    <div>
        <label class="block text-sm font-medium text-gray-700">Periode</label>
        <select name="periode" class="mt-1 w-full rounded-xl border-gray-300">
            <option value="">— Pilih —</option>
            <option value="monthly" @selected(old('periode', $contest->periode ?? '') === 'monthly')>Bulanan</option>
            <option value="quarterly" @selected(old('periode', $contest->periode ?? '') === 'quarterly')>Quarterly</option>
            <option value="annual" @selected(old('periode', $contest->periode ?? '') === 'annual')>Annual</option>
        </select>
        @error('periode')
            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Tanggal Mulai / Selesai (BARU) --}}
    <div class="grid gap-4 md:grid-cols-2">
        <div>
            <label class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
            <input type="date" name="tanggal_mulai"
                value="{{ old('tanggal_mulai', optional($contest->tanggal_mulai ?? null)->format('Y-m-d')) }}"
                class="mt-1 w-full rounded-xl border-gray-300">
            @error('tanggal_mulai')
                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
            <input type="date" name="tanggal_selesai"
                value="{{ old('tanggal_selesai', optional($contest->tanggal_selesai ?? null)->format('Y-m-d')) }}"
                class="mt-1 w-full rounded-xl border-gray-300">
            @error('tanggal_selesai')
                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Target --}}
    <div class="grid gap-4 md:grid-cols-2">
        <div>
            <label class="block text-sm font-medium text-gray-700">Target Premi</label>
            <input type="number" step="0.01" min="0" name="target_premi" required
                value="{{ old('target_premi', $contest->target_premi ?? 0) }}"
                class="mt-1 w-full rounded-xl border-gray-300">
            @error('target_premi')
                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Target Case</label>
            <input type="number" min="0" name="target_case" required
                value="{{ old('target_case', $contest->target_case ?? 0) }}"
                class="mt-1 w-full rounded-xl border-gray-300">
            @error('target_case')
                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Flyer --}}
    <div>
        <label class="block text-sm font-medium text-gray-700">Flyer (JPG/PNG/PDF, maks 2MB)</label>
        <input type="file" name="flyer" accept=".jpg,.jpeg,.png,.pdf"
            class="mt-1 w-full rounded-xl border-gray-300 bg-white">
        @error('flyer')
            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
        @enderror

        @if ($isEdit && !empty($contest->flyer_url))
            <div class="mt-2 text-sm">
                File saat ini: <a class="text-indigo-600 hover:underline" href="{{ $contest->flyer_url }}"
                    target="_blank">Lihat</a>
            </div>
        @endif
    </div>
</div>
