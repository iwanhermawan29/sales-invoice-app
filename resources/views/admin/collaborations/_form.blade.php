@php $isEdit = $collab && $collab->exists; @endphp

<div class="space-y-6">
    <div class="grid md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Nama</label>
            <input type="text" name="name" value="{{ old('name', $collab->name) }}" required
                class="mt-1 w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700">
            @error('name')
                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Website (opsional)</label>
            <input type="url" name="website_url" value="{{ old('website_url', $collab->website_url) }}"
                placeholder="https://example.com"
                class="mt-1 w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700">
            @error('website_url')
                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Keterangan</label>
        <textarea name="description" rows="4"
            class="mt-1 w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700"
            placeholder="Deskripsi singkat…">{{ old('description', $collab->description) }}</textarea>
        @error('description')
            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Logo (jpg/png/svg/pdf, max
                2MB)</label>
            <input type="file" name="image" accept=".jpg,.jpeg,.png,.svg,.pdf"
                class="mt-1 w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700">
            @error('image')
                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-end gap-6">
            <label class="inline-flex items-center gap-2">
                <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $collab->is_featured))>
                <span class="text-sm">Featured</span>
            </label>
            <label class="inline-flex items-center gap-2">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $collab->is_active ?? true))>
                <span class="text-sm">Aktif</span>
            </label>
        </div>
    </div>

    @if ($isEdit && $collab->url)
        <div>
            <div class="text-sm text-gray-600 mb-2">Preview Logo Saat Ini:</div>
            <img src="{{ $collab->url }}" alt="Logo" class="h-20 object-contain">
        </div>
    @endif
</div>
