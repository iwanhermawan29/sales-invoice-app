<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                {{ __('Tambah User') }}
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
                <div class="font-semibold mb-1">Periksa kembali isian Anda:</div>
                <ul class="list-disc ms-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('users.store') }}" method="POST" class="mx-auto max-w-3xl space-y-6">
            @csrf

            @include('users._form', ['roles' => $roles])

            <div class="flex justify-end gap-3 sticky bottom-4">
                <a href="{{ route('users.index') }}"
                    class="inline-flex items-center gap-2 rounded-xl border px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800">
                    {{ __('Batal') }}
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-white hover:bg-blue-700 shadow">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M5 12a1 1 0 011-1h9.586l-3.293-3.293a1 1 0 111.414-1.414l5 5a.997.997 0 01.22.326.997.997 0 010 .764 1 1 0 01-.22.326l-5 5a1 1 0 11-1.414-1.414L15.586 13H6a1 1 0 01-1-1z" />
                    </svg>
                    {{ __('Simpan') }}
                </button>
            </div>
        </form>
    </div>

    {{-- Toggle show/hide password --}}
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
