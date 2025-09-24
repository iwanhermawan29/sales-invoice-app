{{-- resources/views/gallery/public.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Galeri — Gen Prime</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.17/dist/tailwind.min.css" rel="stylesheet">
    @endif
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="antialiased bg-gray-50 text-gray-800">
    {{-- Navbar ringan --}}
    <nav class="bg-white/80 backdrop-blur border-b border-gray-100 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-6 h-14 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-2">
                <x-application-logo class="w-7 h-7 text-indigo-600" />
                <span class="font-semibold text-gray-900">Gen Prime</span>
            </a>
            <div class="hidden md:flex items-center gap-6 text-sm">
                <a href="{{ url('/') }}" class="text-gray-600 hover:text-gray-900">Home</a>
                <a href="{{ url('/about') }}" class="text-gray-600 hover:text-gray-900">About</a>
                <a href="{{ url('/demography') }}" class="text-gray-600 hover:text-gray-900">Demography</a>
                <a href="{{ url('/gallery') }}" class="text-indigo-600 font-semibold">Galeri</a>
                <a href="{{ url('/collaboration') }}" class="text-gray-600 hover:text-gray-900">Collaboration</a>
            </div>
        </div>
    </nav>

    {{-- Hero --}}
    <header class="bg-gradient-to-r from-indigo-600 via-blue-600 to-cyan-500 text-white">
        <div class="max-w-7xl mx-auto px-6 py-12 md:py-16">
            <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight">Galeri Kegiatan Gen Prime</h1>
            <p class="mt-2 text-white/90 max-w-2xl">
                Dokumentasi foto-foto acara dan aktivitas komunitas, dapat difilter berdasarkan kota.
            </p>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 py-8" x-data="{ lightbox: false, src: null, caption: null }">
        {{-- Filter Bar --}}
        <form method="GET" class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm -mt-12 relative">
            <div class="grid gap-3 md:grid-cols-3">
                <div class="md:col-span-2">
                    <label class="text-sm text-gray-600">Cari</label>
                    <input type="text" name="q" value="{{ $q ?? '' }}"
                        placeholder="Ketik judul / keterangan…" class="mt-1 w-full rounded-xl border-gray-300" />
                </div>
                <div>
                    <label class="text-sm text-gray-600">Kota</label>
                    <select name="city" class="mt-1 w-full rounded-xl border-gray-300">
                        <option value="">— Semua Kota —</option>
                        @foreach ($cities ?? [] as $c)
                            <option value="{{ $c }}" @selected(($city ?? '') === $c)>{{ $c }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-3 flex items-center justify-between">
                <p class="text-xs text-gray-500">
                    Menampilkan dokumentasi terbaru terlebih dahulu.
                </p>
                <div class="flex items-center gap-2">
                    <a href="{{ url('/gallery') }}" class="px-3 py-2 rounded-xl border text-gray-700 hover:bg-gray-50">
                        Reset
                    </a>
                    <button class="px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700">
                        Terapkan
                    </button>
                </div>
            </div>
        </form>

        {{-- Info jumlah --}}
        <div class="mt-6 text-sm text-gray-600">
            Total: <span class="font-semibold">{{ $galleries->total() }}</span> foto
            @if (!empty($city))
                • Kota: <span class="font-medium">{{ $city }}</span>
            @endif
            @if (!empty($q))
                • Cari: “<span class="font-medium">{{ $q }}</span>”
            @endif
        </div>

        {{-- Grid Galeri --}}
        @php
            // helper url gambar (fallback Storage::url)
            $imgUrl = function ($g) {
                if (!empty($g->url)) {
                    return $g->url;
                }
                if (!empty($g->image_url)) {
                    return $g->image_url;
                }
                if (!empty($g->image_path)) {
                    try {
                        return \Illuminate\Support\Facades\Storage::url($g->image_path);
                    } catch (\Throwable $e) {
                        return null;
                    }
                }
                return null;
            };
        @endphp

        @if ($galleries->count())
            <div class="mt-4 grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach ($galleries as $g)
                    @php
                        $url = $imgUrl($g);
                    @endphp
                    <article
                        class="group overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm hover:shadow-md transition">
                        <div class="relative aspect-[4/3] bg-gray-100">
                            @if ($url)
                                <img src="{{ $url }}" alt="{{ $g->title ?? 'Dokumentasi' }}"
                                    class="h-full w-full object-cover" loading="lazy"
                                    @click="lightbox=true; src='{{ $url }}'; caption='{{ e($g->title ?? '') }}'">
                            @else
                                <div class="absolute inset-0 grid place-items-center text-gray-400">
                                    <svg class="w-10 h-10" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M3 5h18v14H3zM3 15l5-5 4 4 3-3 6 6" />
                                    </svg>
                                </div>
                            @endif

                            {{-- Badge kota --}}
                            @if (!empty($g->city))
                                <span
                                    class="absolute top-3 left-3 inline-flex rounded-full bg-white/90 px-3 py-1 text-xs font-medium text-gray-800 shadow">
                                    {{ $g->city }}
                                </span>
                            @endif
                        </div>

                        <div class="p-4">
                            <h3 class="font-semibold text-gray-900 line-clamp-1">
                                {{ $g->title ?? 'Dokumentasi' }}
                            </h3>
                            <div class="mt-1 text-xs text-gray-500 flex items-center gap-2">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2v-6H3v6a2 2 0 002 2z" />
                                </svg>
                                {{ optional($g->created_at)->format('d M Y') ?? '—' }}
                            </div>

                            @if (!empty($g->description))
                                <p class="mt-2 text-sm text-gray-600 line-clamp-2">
                                    {{ $g->description }}
                                </p>
                            @endif

                            @if ($url)
                                <div class="mt-3">
                                    <button
                                        class="inline-flex items-center gap-2 rounded-xl border px-3 py-1.5 text-sm hover:bg-gray-50"
                                        @click="lightbox=true; src='{{ $url }}'; caption='{{ e($g->title ?? '') }}'">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M15 10l4.553-4.553a.5.5 0 01.707.707L15.707 10.707H15zm0 0l-6 6M9 14H4v5h5l6-6" />
                                        </svg>
                                        Lihat Besar
                                    </button>
                                </div>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $galleries->onEachSide(1)->links() }}
            </div>
        @else
            <div class="mt-10 text-center text-gray-500">
                Belum ada foto yang cocok dengan filter.
            </div>
        @endif

        {{-- Lightbox --}}
        <div x-show="lightbox" x-cloak class="fixed inset-0 z-50 bg-black/80 grid place-items-center p-4"
            @keydown.escape.window="lightbox=false">
            <figure class="max-w-5xl w-full">
                <img :src="src" class="max-h-[80vh] w-full object-contain rounded shadow-2xl">
                <figcaption class="mt-3 text-center text-white/90" x-text="caption"></figcaption>
            </figure>
            <button class="absolute top-5 right-6 text-white text-xl" @click="lightbox=false">✕</button>
        </div>
    </main>

    <footer class="border-t bg-white">
        <div class="max-w-7xl mx-auto px-6 py-6 text-sm text-gray-500 text-center">
            &copy; {{ date('Y') }} Gen Prime. All rights reserved.
        </div>
    </footer>
</body>

</html>
