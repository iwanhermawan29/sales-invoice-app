{{-- resources/views/public/collaboration.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Collaboration — Gen Prime</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.17/dist/tailwind.min.css" rel="stylesheet">
    @endif
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="antialiased bg-gray-50 text-gray-800">
    {{-- Navbar ringan (sama seperti galeri) --}}
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
                <a href="{{ url('/gallery') }}" class="text-gray-600 hover:text-gray-900">Galeri</a>
                <a href="{{ url('/collaboration') }}" class="text-indigo-600 font-semibold">Collaboration</a>
            </div>
        </div>
    </nav>

    {{-- Hero (sama tone dengan galeri) --}}
    <header class="bg-gradient-to-r from-indigo-600 via-blue-600 to-cyan-500 text-white">
        <div class="max-w-7xl mx-auto px-6 py-12 md:py-16">
            <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight">Kolaborasi Gen Prime</h1>
            <p class="mt-2 text-white/90 max-w-2xl">
                Daftar partner & kolaborator yang mendukung ekosistem Gen Prime.
            </p>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 py-8">
        {{-- Filter Bar (match galeri: kiri info, kanan search) --}}
        <form method="GET" class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm -mt-12 relative">
            <div class="grid gap-3 md:grid-cols-3">
                <div class="md:col-span-2">
                    <label class="text-sm text-gray-600">Cari</label>
                    <input type="text" name="q" value="{{ $q ?? '' }}"
                        placeholder="Ketik nama / deskripsi partner…" class="mt-1 w-full rounded-xl border-gray-300" />
                </div>
                <div class="flex items-end">
                    <div class="ms-auto flex items-center gap-2">
                        <a href="{{ url('/collaboration') }}"
                            class="px-3 py-2 rounded-xl border text-gray-700 hover:bg-gray-50">
                            Reset
                        </a>
                        <button class="px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700">
                            Terapkan
                        </button>
                    </div>
                </div>
            </div>
        </form>

        {{-- Info jumlah --}}
        <div class="mt-6 text-sm text-gray-600">
            Total: <span class="font-semibold">{{ $collabs->total() }}</span> partner
            @if (!empty($q))
                • Cari: “<span class="font-medium">{{ $q }}</span>”
            @endif
        </div>

        @php
            // Helper url gambar (fallback Storage::url)
            $imgUrl = function ($c) {
                if (!empty($c->image_url)) {
                    return $c->image_url;
                }
                if (!empty($c->image_path)) {
                    try {
                        return \Illuminate\Support\Facades\Storage::url($c->image_path);
                    } catch (\Throwable $e) {
                        return null;
                    }
                }
                return null;
            };
        @endphp

        {{-- Featured --}}
        @if (isset($featured) && $featured->count())
            <div class="mt-4">
                <h2 class="text-sm font-semibold text-gray-700 mb-3">Featured</h2>
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @foreach ($featured as $c)
                        @php $url = $imgUrl($c); @endphp
                        <article
                            class="group overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm hover:shadow-md transition">
                            <div class="relative h-32 grid place-items-center bg-gray-50 border-b">
                                @if ($url)
                                    <img src="{{ $url }}" alt="{{ $c->name }}"
                                        class="max-h-16 object-contain">
                                @else
                                    <div class="h-10 w-24 bg-gray-200 rounded"></div>
                                @endif
                                <span
                                    class="absolute top-3 left-3 inline-flex rounded-full bg-amber-100 text-amber-700 text-xs px-2 py-0.5">
                                    Featured
                                </span>
                            </div>
                            <div class="p-4 space-y-2">
                                <h3 class="font-semibold text-gray-900 line-clamp-1">{{ $c->name }}</h3>
                                @if ($c->description)
                                    <p class="text-sm text-gray-600 line-clamp-2">{{ $c->description }}</p>
                                @endif

                                <div class="flex items-center justify-between pt-1">
                                    <span
                                        class="text-[10px] px-2 py-0.5 rounded-full bg-gray-100 text-gray-600">Partner</span>
                                    @if ($c->website_url)
                                        <a href="{{ $c->website_url }}" target="_blank" rel="noopener"
                                            class="inline-flex items-center gap-1 text-sm text-indigo-600 hover:text-indigo-700">
                                            Kunjungi
                                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor">
                                                <path d="M7 17L17 7M8 7h9v9" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Grid Utama (seperti galeri) --}}
        @if ($collabs->count())
            <div class="mt-6">
                <h2 class="text-sm font-semibold text-gray-700 mb-3">All Collaborations</h2>
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @foreach ($collabs as $c)
                        @php $url = $imgUrl($c); @endphp
                        <article
                            class="group overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm hover:shadow-md transition">
                            <div class="h-32 grid place-items-center bg-gray-50 border-b">
                                @if ($url)
                                    <img src="{{ $url }}" alt="{{ $c->name }}"
                                        class="max-h-16 object-contain">
                                @else
                                    <div class="h-10 w-24 bg-gray-200 rounded"></div>
                                @endif
                            </div>
                            <div class="p-4 space-y-2">
                                <h3 class="font-semibold text-gray-900 line-clamp-1">{{ $c->name }}</h3>
                                @if ($c->description)
                                    <p class="text-sm text-gray-600 line-clamp-2">{{ $c->description }}</p>
                                @endif

                                <div class="flex items-center justify-between pt-1">
                                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-gray-100 text-gray-600">
                                        {{ $c->is_featured ? 'Featured' : 'Partner' }}
                                    </span>
                                    @if ($c->website_url)
                                        <a href="{{ $c->website_url }}" target="_blank" rel="noopener"
                                            class="inline-flex items-center gap-1 text-sm text-indigo-600 hover:text-indigo-700">
                                            Kunjungi
                                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor">
                                                <path d="M7 17L17 7M8 7h9v9" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $collabs->onEachSide(1)->links() }}
                </div>
            </div>
        @else
            <div class="mt-10 text-center text-gray-500">
                Belum ada kolaborasi yang cocok dengan filter.
            </div>
        @endif
    </main>

    <footer class="border-t bg-white">
        <div class="max-w-7xl mx-auto px-6 py-6 text-sm text-gray-500 text-center">
            &copy; {{ date('Y') }} Gen Prime. All rights reserved.
        </div>
    </footer>
</body>

</html>
