<div class="space-y-4">
    <div>
        <label for="user_id" class="block font-medium">{{ __('Agen') }}</label>
        <select name="user_id" id="user_id" required class="w-full mt-1 p-2 border rounded">
            @foreach($agents as $agent)
                <option value="{{ $agent->id }}" {{ old('user_id', \$eventTarget->user_id ?? '') == \$agent->id ? 'selected' : '' }}>
                    {{ \$agent->name }}
                </option>
            @endforeach
        </select>
        @error('user_id')<p class="text-red-600 text-sm">{{ \$message }}</p>@enderror
    </div>

    <div>
        <label for="event_name" class="block font-medium">{{ __('Nama Event') }}</label>
        <input type="text" name="event_name" id="event_name" value="{{ old('event_name', \$eventTarget->event_name ?? '') }}" required class="w-full mt-1 p-2 border rounded" />
        @error('event_name')<p class="text-red-600 text-sm">{{ \$message }}</p>@enderror
    </div>

    <div>
        <label for="year" class="block font-medium">{{ __('Tahun') }}</label>
        <input type="number" name="year" id="year" value="{{ old('year', \$eventTarget->year ?? date('Y')) }}" required min="2020" max="2100" class="w-full mt-1 p-2 border rounded" />
        @error('year')<p class="text-red-600 text-sm">{{ \$message }}</p>@enderror
    </div>

    <div>
        <label for="target_amount" class="block font-medium">{{ __('Target (Rp)') }}</label>
        <input type="number" name="target_amount" id="target_amount" value="{{ old('target_amount', \$eventTarget->target_amount ?? '') }}" required step="0.01" class="w-full mt-1 p-2 border rounded" />
        @error('target_amount')<p class="text-red-600 text-sm">{{ \$message }}</p>@enderror
    </div>

    <div>
        <label for="notes" class="block font-medium">{{ __('Notes') }}</label>
        <textarea name="notes" id="notes" rows="3" class="w-full mt-1 p-2 border rounded">{{ old('notes', \$eventTarget->notes ?? '') }}</textarea>
        @error('notes')<p class="text-red-600 text-sm">{{ \$message }}</p>@enderror
    </div>
</div>
