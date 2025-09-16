{{-- resources/views/welcome.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>{{ config('app.name', 'AXA Leaderboard') }}</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.17/dist/tailwind.min.css" rel="stylesheet">
    @endif
</head>

<body class="antialiased bg-gray-50 text-gray-800">

    {{-- Navigation --}}
    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <a href="/" class="text-2xl font-bold text-indigo-600 hover:text-indigo-800">
                {{ config('app.name', 'AXA Leaderboard') }}
            </a>
            <div class="space-x-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                            Log in
                        </a>
                        {{-- @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="px-4 py-2 border border-indigo-600 text-indigo-600 rounded-lg hover:bg-indigo-50">
                                Register
                            </a>
                        @endif --}}
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    {{-- Hero --}}
    <header class="relative bg-gradient-to-r from-indigo-600 to-blue-500 text-white">
        <div class="max-w-3xl mx-auto py-32 px-6 text-center">
            <h1 class="text-5xl sm:text-6xl font-extrabold tracking-tight mb-6">
                {{ __('Tingkatkan Kinerja Agen Anda') }}
            </h1>
            <p class="text-lg sm:text-xl mb-8">
                {{ __('Pantau target & closing secara real‑time, berikan penghargaan bagi top performer, dan dorong tim Anda mencapai puncak.') }}
            </p>
            <div class="space-x-4">
                <a href="{{ route('register') }}"
                    class="inline-block px-8 py-3 bg-white text-indigo-600 font-semibold rounded-lg hover:bg-gray-100">
                    {{ __('Mulai Sekarang') }}
                </a>
                <a href="#features"
                    class="inline-block px-8 py-3 border border-white text-white rounded-lg hover:bg-white hover:text-indigo-600 transition">
                    {{ __('Pelajari Lebih Lanjut') }}
                </a>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-16 bg-white"></div>
    </header>

    {{-- Features --}}
    <section id="features" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-3xl font-bold text-center mb-12">{{ __('Fitur Unggulan') }}</h2>
            <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                <div class="p-6 bg-gray-50 rounded-lg shadow hover:shadow-lg transition">
                    <div class="mb-4">
                        <svg class="w-10 h-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M12 8v4m0 0v4m0-4h4m-4 0H8" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">{{ __('Leaderboard Real‑Time') }}</h3>
                    <p class="text-gray-600">{{ __('Lihat peringkat agen dan skor terbaru dalam satu halaman.') }}</p>
                </div>
                <div class="p-6 bg-gray-50 rounded-lg shadow hover:shadow-lg transition">
                    <div class="mb-4">
                        <svg class="w-10 h-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M3 5h18M9 3v2m6-2v2M6 11h12M6 15h12M6 19h12" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">{{ __('Manajemen Target') }}</h3>
                    <p class="text-gray-600">
                        {{ __('Tetapkan dan pantau target penjualan bulanan/tahunan untuk setiap agen.') }}</p>
                </div>
                <div class="p-6 bg-gray-50 rounded-lg shadow hover:shadow-lg transition">
                    <div class="mb-4">
                        <svg class="w-10 h-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M5 13l4 4L19 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">{{ __('Pencatatan Closing') }}</h3>
                    <p class="text-gray-600">
                        {{ __('Agent dapat memasukkan detail closing: customer, polis, nilai premi.') }}</p>
                </div>
                <div class="p-6 bg-gray-50 rounded-lg shadow hover:shadow-lg transition">
                    <div class="mb-4">
                        <svg class="w-10 h-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M11 17l-5-5m0 0l5-5m-5 5h12" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">{{ __('Laporan & Rapor') }}</h3>
                    <p class="text-gray-600">
                        {{ __('Head dapat melihat laporan lengkap dan memberikan peringkat agen.') }}</p>
                </div>
                <div class="p-6 bg-gray-50 rounded-lg shadow hover:shadow-lg transition">
                    <div class="mb-4">
                        <svg class="w-10 h-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M12 20l9-5-9-5-9 5 9 5z" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">{{ __('Notifikasi & Reminder') }}</h3>
                    <p class="text-gray-600">{{ __('Dapatkan notifikasi otomatis ketika target hampir tercapai.') }}
                    </p>
                </div>
                <div class="p-6 bg-gray-50 rounded-lg shadow hover:shadow-lg transition">
                    <div class="mb-4">
                        <svg class="w-10 h-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M3 7h18M3 12h18M3 17h18" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">{{ __('Akses Mobile') }}</h3>
                    <p class="text-gray-600">{{ __('Pantau leaderboard dan target kapan saja lewat ponsel.') }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- How It Works --}}
    <section class="py-16 bg-gray-100">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold mb-8">{{ __('Cara Kerja') }}</h2>
            <div class="grid gap-12 sm:grid-cols-3">
                <div>
                    <div
                        class="mb-4 flex items-center justify-center w-16 h-16 bg-indigo-600 text-white rounded-full mx-auto">
                        1
                    </div>
                    <h4 class="text-xl font-semibold mb-2">{{ __('Daftarkan Agen') }}</h4>
                    <p class="text-gray-600">{{ __('Admin menambahkan akun Agent dan Head.') }}</p>
                </div>
                <div>
                    <div
                        class="mb-4 flex items-center justify-center w-16 h-16 bg-indigo-600 text-white rounded-full mx-auto">
                        2
                    </div>
                    <h4 class="text-xl font-semibold mb-2">{{ __('Tetapkan Target') }}</h4>
                    <p class="text-gray-600">{{ __('Admin menentukan target penjualan tiap periode.') }}</p>
                </div>
                <div>
                    <div
                        class="mb-4 flex items-center justify-center w-16 h-16 bg-indigo-600 text-white rounded-full mx-auto">
                        3
                    </div>
                    <h4 class="text-xl font-semibold mb-2">{{ __('Catat & Pantau') }}</h4>
                    <p class="text-gray-600">{{ __('Agent mencatat closing, Head memantau laporan & peringkat.') }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-white text-gray-500 text-center py-6">
        &copy; {{ date('Y') }} {{ config('app.name', 'AXA Leaderboard') }}. {{ __('All rights reserved.') }}
    </footer>
</body>

</html>
