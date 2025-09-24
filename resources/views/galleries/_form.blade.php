{{-- resources/views/galleries/_form.blade.php --}}
@php $isEdit = isset($gallery); @endphp

<div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-6 shadow-sm space-y-6"
    x-data="{ preview: '{{ $isEdit && $gallery->photo_path ? Storage::url($gallery->photo_path) : '' }}' }">

    <div class="grid gap-5 md:grid-cols-2">
        {{-- City --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Kota</label>
            @php
                $cityOptions = [
                    'Jakarta',
                    'Medan',
                    'Bandung',
                    'Semarang',
                    'Surabaya',
                    'Bali',
                    'Manado',
                    'Makassar',
                    'Palembang',
                ];
            @endphp
            <select name="city" required
                class="mt-1 block w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700 p-2.5">
                <option value="">Pilih Kota…</option>
                @foreach ($cityOptions as $c)
                    <option value="{{ $c }}" @selected(old('city', $gallery->city ?? '') === $c)>{{ $c }}</option>
                @endforeach
            </select>
            @error('city')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Tanggal pengambilan --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Tanggal Pengambilan</label>
            <input type="date" name="taken_at"
                value="{{ old('taken_at', optional($gallery->taken_at ?? null)->format('Y-m-d')) }}"
                class="mt-1 block w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700 p-2.5">
            @error('taken_at')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Title --}}
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Judul</label>
            <input type="text" name="title" placeholder="Judul (opsional)"
                value="{{ old('title', $gallery->title ?? '') }}"
                class="mt-1 block w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700 p-2.5">
            @error('title')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Caption --}}
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Caption</label>
            <textarea name="caption" rows="3" placeholder="Deskripsi singkat…"
                class="mt-1 block w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700 p-2.5">{{ old('caption', $gallery->caption ?? '') }}</textarea>
            @error('caption')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Relasi ke Kontes (opsional) --}}
        @isset($contests)
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Tautkan ke Kontes
                    (opsional)</label>
                <select name="contest_id"
                    class="mt-1 block w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700 p-2.5">
                    <option value="">— Tidak Ditautkan —</option>
                    @foreach ($contests as $ct)
                        <option value="{{ $ct->id }}" @selected(old('contest_id', $gallery->contest_id ?? null) == $ct->id)>
                            {{ $ct->nama_kontes }}
                        </option>
                    @endforeach
                </select>
                @error('contest_id')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>
        @endisset

        {{-- Published --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Status</label>
            <select name="is_published"
                class="mt-1 block w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700 p-2.5">
                <option value="1" @selected(old('is_published', (int) ($gallery->is_published ?? 1)) === 1)>Published</option>
                <option value="0" @selected(old('is_published', (int) ($gallery->is_published ?? 1)) === 0)>Draft</option>
            </select>
            @error('is_published')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Upload photo + preview --}}
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                Foto {{ $isEdit ? '(opsional jika tidak diganti)' : '(wajib)' }}
            </label>
            <input type="file" name="photo" accept="image/*"
                class="mt-1 block w-full text-sm file:mr-4 file:py-2 file:px-4
                          file:rounded-md file:border-0 file:text-sm file:font-semibold
                          file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                @change="preview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : preview">
            @error('photo')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror

            <template x-if="preview">
                <div class="mt-3">
                    <img :src="preview"
                        class="h-44 w-auto rounded-xl border border-gray-200 dark:border-gray-700 object-cover">
                </div>
            </template>
        </div>
    </div>
</div>
