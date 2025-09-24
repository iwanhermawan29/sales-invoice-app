{{-- resources/views/galleries/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100">Edit Dokumentasi</h2>
            <a href="{{ route('galleries.index') }}" class="text-sm text-indigo-600 hover:underline">Kembali</a>
        </div>
    </x-slot>

    <div class="p-6 space-y-6">
        <form method="POST" action="{{ route('galleries.update', $gallery) }}" enctype="multipart/form-data"
            class="space-y-6">
            @csrf
            @method('PUT')

            @include('galleries._form', ['gallery' => $gallery, 'contests' => $contests ?? null])

            <div class="flex items-center justify-between">
                <form method="POST" action="{{ route('galleries.destroy', $gallery) }}"
                    onsubmit="return confirm('Hapus foto ini?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                        class="rounded-xl px-4 py-2 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/20">
                        Hapus
                    </button>
                </form>

                <div class="flex items-center gap-3">
                    <a href="{{ route('galleries.index') }}" class="rounded-xl border px-4 py-2">Batal</a>
                    <button class="rounded-xl px-4 py-2 bg-indigo-600 text-white hover:bg-indigo-700">Update</button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
