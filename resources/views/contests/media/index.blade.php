<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100">
                Media Kontes: {{ $contest->nama_kontes }}
            </h2>
            <a href="{{ route('contests.index') }}" class="text-indigo-600 hover:underline">← Kembali</a>
        </div>
    </x-slot>

    <div class="p-6 space-y-8">
        {{-- Upload --}}
        <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-5">
            <form action="{{ route('contests.media.store', $contest) }}" method="POST" enctype="multipart/form-data"
                class="grid gap-4 sm:grid-cols-3">
                @csrf
                <div>
                    <label class="text-sm text-gray-600 dark:text-gray-300">Tipe</label>
                    <select name="type"
                        class="mt-1 w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700" required>
                        <option value="photo">Foto</option>
                        <option value="logo">Logo</option>
                    </select>
                    @error('type')
                        <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="sm:col-span-2">
                    <label class="text-sm text-gray-600 dark:text-gray-300">Pilih Berkas (bisa banyak)</label>
                    <input type="file" name="files[]" multiple accept=".jpg,.jpeg,.png,.webp,.svg"
                        class="mt-1 block w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700">
                    <p class="text-xs text-gray-500 mt-1">Maks 3MB per file</p>
                    @error('files')
                        <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                    @error('files.*')
                        <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="sm:col-span-3 flex justify-end">
                    <button class="rounded-xl bg-indigo-600 px-5 py-2 text-white hover:bg-indigo-700">
                        Unggah
                    </button>
                </div>
            </form>
        </div>

        {{-- Grid LOGO --}}
        <div class="space-y-3">
            <div class="text-sm font-semibold text-gray-700 dark:text-gray-200">Logo</div>
            <div class="grid gap-4 sm:grid-cols-4 md:grid-cols-6">
                @forelse($contest->logos as $m)
                    <div
                        class="group rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden bg-white dark:bg-gray-900">
                        <div class="h-28 grid place-items-center bg-white">
                            <img src="{{ $m->url }}" alt="Logo" class="max-h-20 object-contain p-2">
                        </div>
                        <div class="flex items-center justify-between p-2">
                            <form action="{{ route('contests.media.update', [$contest, $m]) }}" method="POST"
                                class="flex items-center gap-2">
                                @csrf @method('PUT')
                                <label class="flex items-center gap-1 text-xs">
                                    <input type="checkbox" name="is_featured" value="1"
                                        {{ $m->is_featured ? 'checked' : '' }} onchange="this.form.submit()"> featured
                                </label>
                                <input type="number" name="sort_order" value="{{ $m->sort_order }}"
                                    class="w-16 rounded border text-xs px-2 py-1" onchange="this.form.submit()">
                            </form>
                            <form action="{{ route('contests.media.destroy', [$contest, $m]) }}" method="POST"
                                class="inline js-del">
                                @csrf @method('DELETE')
                                <button class="text-rose-600 text-sm px-2 py-1 rounded hover:bg-rose-50">Hapus</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500">Belum ada logo.</p>
                @endforelse
            </div>
        </div>

        {{-- Grid FOTO --}}
        <div class="space-y-3">
            <div class="text-sm font-semibold text-gray-700 dark:text-gray-200">Foto</div>
            <div class="grid gap-4 sm:grid-cols-2 md:grid-cols-3">
                @forelse($contest->photos as $m)
                    <div class="group rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <img src="{{ $m->url }}" alt="Foto" class="h-48 w-full object-cover">
                        <div class="p-2 flex items-center justify-between">
                            <form action="{{ route('contests.media.update', [$contest, $m]) }}" method="POST"
                                class="flex items-center gap-2">
                                @csrf @method('PUT')
                                <label class="flex items-center gap-1 text-xs">
                                    <input type="checkbox" name="is_featured" value="1"
                                        {{ $m->is_featured ? 'checked' : '' }} onchange="this.form.submit()"> featured
                                </label>
                                <input type="number" name="sort_order" value="{{ $m->sort_order }}"
                                    class="w-16 rounded border text-xs px-2 py-1" onchange="this.form.submit()">
                            </form>
                            <form action="{{ route('contests.media.destroy', [$contest, $m]) }}" method="POST"
                                class="inline js-del">
                                @csrf @method('DELETE')
                                <button class="text-rose-600 text-sm px-2 py-1 rounded hover:bg-rose-50">Hapus</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500">Belum ada foto.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if (session('status'))
        <script>
            Swal.fire({
                toast: true,
                position: 'top-end',
                timer: 3000,
                showConfirmButton: false,
                icon: 'success',
                title: @json(session('status'))
            });
        </script>
    @endif
    <script>
        document.addEventListener('click', function(e) {
            const f = e.target.closest('.js-del');
            if (!f || !e.target.matches('button')) return;
            e.preventDefault();
            Swal.fire({
                title: 'Hapus media ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then(r => {
                if (r.isConfirmed) f.submit();
            });
        });
    </script>
</x-app-layout>
