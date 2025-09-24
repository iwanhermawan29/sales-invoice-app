<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Informasi Profil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
            {{ __('Perbarui informasi profil dan alamat email akun Anda. Setiap perubahan akan menunggu verifikasi admin.') }}
        </p>

        {{-- Badge status + kode agent + catatan admin --}}
        <div class="mt-3 flex flex-wrap items-center gap-2">
            <span
                class="inline-flex rounded-full px-3 py-1 text-xs font-medium {{ auth()->user()->profile_status_color }}">
                Status: {{ auth()->user()->profile_status_label }}
            </span>

            @if (auth()->user()->kode_agent)
                <span class="text-sm text-gray-800 dark:text-gray-200">
                    Kode Agent: <b>{{ auth()->user()->kode_agent }}</b>
                </span>
            @endif

            @if (auth()->user()->profile_approval_note)
                <span class="text-sm text-gray-500">
                    • Catatan Admin: {{ auth()->user()->profile_approval_note }}
                </span>
            @endif
        </div>

        @if (session('status') === 'profile-updated' || is_string(session('status')))
            <div class="mt-3 rounded-lg border border-green-200 bg-green-50 px-3 py-2 text-green-800 text-sm">
                {{ is_string(session('status')) ? session('status') : __('Profil berhasil diperbarui.') }}
            </div>
        @endif
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch') {{-- sesuai Breeze --}}

        {{-- Nama --}}
        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)"
                required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        {{-- Email --}}
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)"
                required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if (!$user->hasVerifiedEmail())
                <p class="mt-2 text-xs text-amber-600">
                    {{ __('Email belum terverifikasi. Anda mungkin perlu verifikasi ulang setelah mengubah email.') }}
                </p>
            @endif
        </div>

        {{-- ===== Field tambahan agent ===== --}}
        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <x-input-label for="agency_name" value="Nama Agency" />
                <x-text-input id="agency_name" name="agency_name" type="text" class="mt-1 block w-full"
                    :value="old('agency_name', $user->agency_name)" />
                <x-input-error class="mt-2" :messages="$errors->get('agency_name')" />
            </div>

            <div>
                <x-input-label for="phone" value="No. HP" />
                <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full"
                    :value="old('phone', $user->phone)" />
                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
            </div>

            <div class="md:col-span-2">
                <x-input-label for="address" value="Alamat" />
                <x-text-input id="address" name="address" type="text" class="mt-1 block w-full"
                    :value="old('address', $user->address)" />
                <x-input-error class="mt-2" :messages="$errors->get('address')" />
            </div>

            <div>
                <x-input-label for="birth_date" value="Tanggal Lahir" />
                <x-text-input id="birth_date" name="birth_date" type="date" class="mt-1 block w-full"
                    :value="old('birth_date', optional($user->birth_date)->format('Y-m-d'))" />
                <x-input-error class="mt-2" :messages="$errors->get('birth_date')" />
            </div>

            <div>
                <x-input-label for="id_number" value="No. Identitas" />
                <x-text-input id="id_number" name="id_number" type="text" class="mt-1 block w-full"
                    :value="old('id_number', $user->id_number)" />
                <x-input-error class="mt-2" :messages="$errors->get('id_number')" />
            </div>

            <div>
                <x-input-label for="bank_name" value="Bank" />
                <x-text-input id="bank_name" name="bank_name" type="text" class="mt-1 block w-full"
                    :value="old('bank_name', $user->bank_name)" />
                <x-input-error class="mt-2" :messages="$errors->get('bank_name')" />
            </div>

            <div>
                <x-input-label for="bank_account" value="No. Rekening" />
                <x-text-input id="bank_account" name="bank_account" type="text" class="mt-1 block w-full"
                    :value="old('bank_account', $user->bank_account)" />
                <x-input-error class="mt-2" :messages="$errors->get('bank_account')" />
            </div>
        </div>
        {{-- ===== /Field tambahan agent ===== --}}

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Simpan') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-300">
                    {{ __('Tersimpan.') }}
                </p>
            @endif
        </div>
    </form>
</section>
