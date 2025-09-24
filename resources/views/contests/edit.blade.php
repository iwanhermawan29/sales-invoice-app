{{-- resources/views/contests/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100">Edit Kontes</h2>
    </x-slot>

    <div class="p-6">
        <form action="{{ route('contests.update', $contest) }}" method="POST" enctype="multipart/form-data"
            class="max-w-3xl mx-auto space-y-6 rounded-2xl border bg-white dark:bg-gray-900 p-6">
            @csrf @method('PUT')

            @include('contests._form', ['contest' => $contest])

            <div class="flex justify-end gap-3">
                <a href="{{ route('contests.index') }}" class="px-4 py-2 rounded-xl border">Batal</a>
                <button class="px-4 py-2 rounded-xl bg-emerald-600 text-white hover:bg-emerald-700">Update</button>
            </div>
        </form>
    </div>
</x-app-layout>
