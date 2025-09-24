@php $isEdit = isset($user); @endphp

<div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-6 shadow-sm space-y-8">

    {{-- Section: Akun --}}
    <div>
        <div class="mb-4 flex items-center gap-3">
            <div class="h-9 w-9 rounded-xl bg-blue-600/10 text-blue-700 flex items-center justify-center">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 12a5 5 0 100-10 5 5 0 000 10zM4 20a8 8 0 1116 0H4z" />
                </svg>
            </div>
            <div>
                <div class="font-semibold text-gray-900 dark:text-gray-100">Informasi Akun</div>
                <div class="text-sm text-gray-500">Nama, email, dan peran pengguna.</div>
            </div>
        </div>

        <div class="grid gap-5 md:grid-cols-2">
            {{-- Nama --}}
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Nama</label>
                <div class="mt-1 relative">
                    <span class="absolute inset-y-0 left-0 flex items-center ps-3 text-gray-400">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 12a5 5 0 100-10 5 5 0 000 10z" />
                        </svg>
                    </span>
                    <input type="text" name="name" id="name" required
                        value="{{ old('name', $user->name ?? '') }}"
                        class="block w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700 ps-10 p-2.5
                                  focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-400"
                        placeholder="Nama lengkap">
                </div>
                @error('name')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Email</label>
                <div class="mt-1 relative">
                    <span class="absolute inset-y-0 left-0 flex items-center ps-3 text-gray-400">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M20 8l-8 5-8-5V6l8 5 8-5v2z" />
                        </svg>
                    </span>
                    <input type="email" name="email" id="email" required
                        value="{{ old('email', $user->email ?? '') }}"
                        class="block w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700 ps-10 p-2.5
                                  focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-400"
                        placeholder="email@contoh.com">
                </div>
                @error('email')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Role --}}
            <div>
                <label for="role_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Role</label>
                <select name="role_id" id="role_id" required
                    class="mt-1 block w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700 p-2.5
                               focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-400">
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}"
                            {{ old('role_id', $user->role_id ?? '') == $role->id ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
                @error('role_id')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            @php
                $cities = [
                    'Jakarta',
                    'Medan',
                    'Bandung',
                    'Semarang',
                    'Surabaya',
                    'Bali',
                    'Manado',
                    'Makasar',
                    'Palembang',
                ];
                $current = old('kota', $user->kota ?? '');
            @endphp

            {{-- Kota (BARU) --}}
            <div>
                <label for="kota" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Kota</label>
                <div class="mt-1 relative">
                    <span class="absolute inset-y-0 left-0 flex items-center ps-3 text-gray-400 pointer-events-none">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M3 21h18V3H3v18zm2-2V5h14v14H5z" />
                        </svg>
                    </span>
                    <select name="kota" id="kota"
                        class="block w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700 ps-10 p-2.5
                       focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-400">
                        {{-- jika nilai lama/tersimpan tidak ada di daftar, tetap tampilkan agar tidak hilang --}}
                        @if ($current && !in_array($current, $cities))
                            <option value="{{ $current }}" selected>{{ $current }} (tersimpan)</option>
                            <option disabled>──────────</option>
                        @endif

                        @foreach ($cities as $city)
                            <option value="{{ $city }}" @selected($current === $city)>{{ $city }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @error('kota')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <hr class="border-gray-100 dark:border-gray-800">

    {{-- Section: Keamanan --}}
    <div>
        <div class="mb-4 flex items-center gap-3">
            <div class="h-9 w-9 rounded-xl bg-emerald-600/10 text-emerald-700 flex items-center justify-center">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                    <path
                        d="M12 1l8 4v6c0 5-3.8 9.7-8 11-4.2-1.3-8-6-8-11V5l8-4zM7 10l5 5 5-5-1.4-1.4L12 12.2 8.4 8.6 7 10z" />
                </svg>
            </div>
            <div>
                <div class="font-semibold text-gray-900 dark:text-gray-100">Keamanan</div>
                <div class="text-sm text-gray-500">Password baru untuk akun.</div>
            </div>
        </div>

        <div class="grid gap-5 md:grid-cols-2">
            {{-- Password --}}
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                    Password {{ $isEdit ? '(opsional)' : '' }}
                </label>
                <div class="mt-1 relative">
                    <input type="password" name="password" id="password" {{ $isEdit ? '' : 'required' }}
                        class="block w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700 pe-10 p-2.5
                                  focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-400"
                        placeholder="{{ $isEdit ? 'Kosongkan jika tidak diubah' : 'Minimal 8 karakter' }}">
                    <button type="button" class="js-toggle-pass absolute inset-y-0 right-0 px-3 text-gray-400">
                        <svg data-eye-open class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M12 5c-7 0-10 7-10 7s3 7 10 7 10-7 10-7-3-7-10-7zm0 12a5 5 0 110-10 5 5 0 010 10z" />
                        </svg>
                        <svg data-eye-closed class="h-5 w-5 hidden" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M3 3l18 18-1.5 1.5L17 19.5A12.7 12.7 0 0112 21C5 21 2 14 2 14a21.9 21.9 0 015.2-6.9L1.5 4.5 3 3zM12 7a5 5 0 014.9 6.1l-1.7-1.7A3 3 0 009 10.8L7.6 9.4A5 5 0 0112 7z" />
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
                @unless ($isEdit)
                    <p class="mt-2 text-xs text-gray-500">Gunakan kombinasi huruf, angka, dan simbol.</p>
                @endunless
            </div>

            {{-- Konfirmasi Password --}}
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                    Konfirmasi Password {{ $isEdit ? '(opsional)' : '' }}
                </label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                    {{ $isEdit ? '' : 'required' }}
                    class="mt-1 block w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700 p-2.5
                              focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-400">
            </div>
        </div>
    </div>

</div>
