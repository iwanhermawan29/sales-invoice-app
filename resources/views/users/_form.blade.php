<div class="space-y-4">
    <div>
        <label for="name" class="block font-medium">Nama</label>
        <input type="text" name="name" id="name" value="{{ old('name', $user->name ?? '') }}" required
            class="w-full mt-1 p-2 border rounded" />
        @error('name')
            <p class="text-red-600 text-sm">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="email" class="block font-medium">Email</label>
        <input type="email" name="email" id="email" value="{{ old('email', $user->email ?? '') }}" required
            class="w-full mt-1 p-2 border rounded" />
        @error('email')
            <p class="text-red-600 text-sm">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="role_id" class="block font-medium">Role</label>
        <select name="role_id" id="role_id" required class="w-full mt-1 p-2 border rounded">
            @foreach ($roles as $role)
                <option value="{{ $role->id }}"
                    {{ old('role_id', $user->role_id ?? '') == $role->id ? 'selected' : '' }}>
                    {{ $role->name }}
                </option>
            @endforeach
        </select>
        @error('role_id')
            <p class="text-red-600 text-sm">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="password" class="block font-medium">Password
            {{ isset($user) ? '(kosongkan jika tidak diubah)' : '' }}</label>
        <input type="password" name="password" id="password" class="w-full mt-1 p-2 border rounded"
            {{ isset($user) ? '' : 'required' }} />
        @error('password')
            <p class="text-red-600 text-sm">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="password_confirmation" class="block font-medium">Konfirmasi Password</label>
        <input type="password" name="password_confirmation" id="password_confirmation"
            class="w-full mt-1 p-2 border rounded" {{ isset($user) ? '' : 'required' }} />
    </div>
</div>
