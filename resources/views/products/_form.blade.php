<div class="space-y-4">
    <div>
        <label for="type" class="block font-medium">{{ __('Tipe Produk') }}</label>
        <select name="type" id="type" required class="w-full mt-1 p-2 border rounded">
            <option value="health" {{ old('type', $product->type ?? '') == 'health' ? 'selected' : '' }}>
                {{ __('Asuransi Kesehatan') }}</option>
            <option value="life" {{ old('type', $product->type ?? '') == 'life' ? 'selected' : '' }}>
                {{ __('Asuransi Jiwa') }}</option>
        </select>
        @error('type')
            <p class="text-red-600 text-sm">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label for="name" class="block font-medium">{{ __('Nama Produk') }}</label>
        <input type="text" name="name" id="name" value="{{ old('name', $product->name ?? '') }}" required
            class="w-full mt-1 p-2 border rounded" />
        @error('name')
            <p class="text-red-600 text-sm">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label for="description" class="block font-medium">{{ __('Deskripsi') }}</label>
        <textarea name="description" id="description" rows="3" class="w-full mt-1 p-2 border rounded">{{ old('description', $product->description ?? '') }}</textarea>
        @error('description')
            <p class="text-red-600 text-sm">{{ $message }}</p>
        @enderror
    </div>
</div>
