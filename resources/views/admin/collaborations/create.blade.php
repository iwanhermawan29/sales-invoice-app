<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100">Tambah Kolaborasi</h2>
    </x-slot>

    <div class="p-6">
        <form action="{{ route('collaborations.store') }}" method="POST" enctype="multipart/form-data"
            class="max-w-3xl mx-auto rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-6 space-y-6">
            @csrf

            @include('admin.collaborations._form', ['collab' => $collab])

            <div class="flex justify-end gap-2">
                <a href="{{ route('collaborations.index') }}" class="rounded-xl border px-4 py-2">Batal</a>
                <button class="rounded-xl bg-indigo-600 text-white px-4 py-2 hover:bg-indigo-700">Simpan</button>
            </div>
        </form>
    </div>
</x-app-layout>
