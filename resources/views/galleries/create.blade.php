{{-- resources/views/galleries/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100">Tambah Dokumentasi</h2>
            <a href="{{ route('galleries.index') }}" class="text-sm text-indigo-600 hover:underline">Kembali</a>
        </div>
    </x-slot>

    <div class="p-6 space-y-6">
        <form method="POST" action="{{ route('galleries.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @include('galleries._form', ['contests' => $contests ?? null])

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('galleries.index') }}" class="rounded-xl border px-4 py-2">Batal</a>
                <button class="rounded-xl px-4 py-2 bg-indigo-600 text-white hover:bg-indigo-700">Simpan</button>
            </div>
        </form>
    </div>
</x-app-layout>
