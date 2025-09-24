<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                {{ __('Edit User') }}
            </h2>
            <a href="{{ route('users.index') }}"
                class="inline-flex items-center gap-2 rounded-xl border px-3 py-2 hover:bg-gray-50 dark:hover:bg-gray-800">
                ← {{ __('Kembali') }}
            </a>
        </div>
    </x-slot>

    <div class="p-6">
        {{-- Ringkasan error (jika ada) --}}
        @if ($errors->any())
            <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50 p-4 text-rose-800">
                <div class="font-semibold mb-1">{{ __('Periksa kembali isian Anda:') }}</div>
                <ul class="list-disc ms-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('users.update', $user) }}" method="POST" class="mx-auto max-w-3xl space-y-6">
            @csrf
            @method('PATCH')

            {{-- Partial form yang sudah “elegan” dan support edit --}}
            @include('users._form', ['roles' => $roles, 'user' => $user])

            <div class="flex justify-end gap-3 sticky bottom-4">
                <a href="{{ route('users.index') }}"
                    class="inline-flex items-center gap-2 rounded-xl border px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800">
                    {{ __('Batal') }}
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-xl bg-green-600 px-5 py-2.5 text-white hover:bg-green-700 shadow">
                    {{ __('Update') }}
                </button>
            </div>
        </form>
    </div>

    {{-- Toggle show/hide password (pakai selector yang sama dengan halaman create) --}}
    <script>
        document.addEventListener('click', (e) => {
            if (e.target.closest('.js-toggle-pass')) {
                const btn = e.target.closest('.js-toggle-pass');
                const input = btn.previousElementSibling;
                input.type = input.type === 'password' ? 'text' : 'password';
                btn.querySelector('[data-eye-open]').classList.toggle('hidden');
                btn.querySelector('[data-eye-closed]').classList.toggle('hidden');
            }
        });
    </script>
</x-app-layout>
