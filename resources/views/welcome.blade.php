{{-- resources/views/welcome.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>{{ config('app.name', 'AXA Leaderboard') }}</title>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.17/dist/tailwind.min.css" rel="stylesheet">
    @endif

    <style>
        /* ====== Animations & Utilities ====== */
        @keyframes floaty {
            0% {
                transform: translateY(0)
            }

            50% {
                transform: translateY(-14px)
            }

            100% {
                transform: translateY(0)
            }
        }

        @keyframes gradientShift {
            0% {
                background-position: 0% 50%
            }

            50% {
                background-position: 100% 50%
            }

            100% {
                background-position: 0% 50%
            }
        }

        .animated-gradient {
            background: linear-gradient(120deg, #5b21b6, #2563eb, #7c3aed, #1d4ed8);
            background-size: 300% 300%;
            animation: gradientShift 14s ease infinite;
        }

        .floaty {
            animation: floaty 8s ease-in-out infinite;
        }

        .floaty-2 {
            animation: floaty 10s ease-in-out infinite;
            animation-delay: .6s
        }

        .floaty-3 {
            animation: floaty 12s ease-in-out infinite;
            animation-delay: 1.2s
        }

        .glass {
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, .08);
            border: 1px solid rgba(255, 255, 255, .12);
        }

        /* Scroll reveal (simple) */
        .reveal {
            opacity: 0;
            transform: translateY(16px);
            transition: opacity .6s ease, transform .6s ease
        }

        .reveal.revealed {
            opacity: 1;
            transform: translateY(0)
        }

        /* Soft shadows */
        .soft-shadow {
            box-shadow: 0 10px 30px -10px rgba(67, 56, 202, .25)
        }

        .soft-shadow-2 {
            box-shadow: 0 15px 35px -12px rgba(37, 99, 235, .35)
        }
    </style>
</head>

<body class="antialiased bg-gray-50 text-gray-800 selection:bg-indigo-200">

    {{-- ===== NAVBAR ===== --}}
    <nav class="sticky top-0 z-50 bg-white/70 backdrop-blur border-b border-white/40">
        <div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between">
            <a href="/" class="flex items-center gap-3 group">
                {{-- LOGO SVG (tema ungu–biru) --}}
                <span class="inline-flex h-10 w-10 rounded-xl items-center justify-center text-white soft-shadow-2"
                    style="background: radial-gradient(120px 60px at 30% 30%, #7c3aed, #2563eb)">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M12 2l3.5 6.5L22 10l-5 4.5L18.5 22 12 18.5 5.5 22 7 14.5 2 10l6.5-1.5L12 2z" />
                    </svg>
                </span>
                <span
                    class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-blue-500">
                    {{ config('app.name', 'AXA Leaderboard') }}
                </span>
            </a>

            <div class="flex items-center gap-3">
                <a href="#features"
                    class="hidden sm:inline-block px-4 py-2 rounded-xl text-indigo-700 hover:bg-indigo-50">
                    {{ __('Fitur') }}
                </a>
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 soft-shadow">
                            {{ __('Dashboard') }}
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 soft-shadow">
                            {{ __('Masuk') }}
                        </a>
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    {{-- ===== HERO ===== --}}
    <header class="relative overflow-hidden">
        <div class="animated-gradient">
            <div class="max-w-7xl mx-auto px-6 pt-20 pb-32">
                <div class="text-center text-white">
                    <div class="inline-flex items-center gap-2 glass rounded-full px-4 py-1.5 text-sm mb-4">
                        <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                        <span>{{ __('Realtime Leaderboard & Smart Targeting') }}</span>
                    </div>

                    <h1 class="text-4xl sm:text-6xl font-extrabold tracking-tight leading-tight">
                        {{ __('Tingkatkan Performa Tim Penjualan') }}
                    </h1>
                    <p class="mt-5 text-lg sm:text-xl text-indigo-50/90 max-w-3xl mx-auto">
                        {{ __('Pantau target, catat closing, dan rayakan top performers—semua dalam satu platform elegan yang cepat & real-time.') }}
                    </p>

                    <div class="mt-8 flex items-center justify-center gap-3">
                        <a href="{{ route('register') }}"
                            class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-white text-indigo-700 font-semibold hover:bg-indigo-50 soft-shadow">
                            {{ __('Mulai Sekarang') }}
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M5 12a1 1 0 011-1h9.586l-3.293-3.293a1 1 0 111.414-1.414l5 5a.997.997 0 01.22.326.997.997 0 010 .764 1 1 0 01-.22.326l-5 5a1 1 0 11-1.414-1.414L15.586 13H6a1 1 0 01-1-1z" />
                            </svg>
                        </a>
                        <a href="#features"
                            class="inline-flex items-center gap-2 px-6 py-3 rounded-xl border border-white/70 text-white hover:bg-white/10">
                            {{ __('Pelajari Lebih Lanjut') }}
                        </a>
                    </div>
                </div>

                {{-- Dekorasi floating shapes --}}
                <div class="pointer-events-none relative">
                    <span class="absolute -top-16 -left-10 h-40 w-40 rounded-full blur-3xl opacity-30 floaty"
                        style="background: radial-gradient(120px 60px at 30% 30%, #a78bfa, #60a5fa)"></span>
                    <span class="absolute -bottom-14 -right-8 h-44 w-44 rounded-full blur-3xl opacity-30 floaty-2"
                        style="background: radial-gradient(120px 60px at 30% 30%, #60a5fa, #7c3aed)"></span>
                    <span class="absolute top-24 right-1/3 h-24 w-24 rounded-2xl blur-xl opacity-40 floaty-3"
                        style="background: linear-gradient(45deg,#8b5cf6,#3b82f6)"></span>
                </div>
            </div>
        </div>

        {{-- Curve separator --}}
        <div class="relative -mt-12">
            <svg class="w-full h-12 text-white" viewBox="0 0 1440 60" preserveAspectRatio="none">
                <path fill="currentColor" d="M0,0 C480,60 960,0 1440,60 L1440,00 L0,0 Z"></path>
            </svg>
        </div>
    </header>

    {{-- ===== TRUST (optional) ===== --}}
    <section class="py-8 bg-white">
        <div class="max-w-6xl mx-auto px-6">
            <p class="text-center text-sm text-gray-500 mb-4">{{ __('Dipercaya tim penjualan berorientasi target') }}
            </p>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 opacity-80">
                <div class="glass rounded-xl p-3 text-center">
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-blue-500 font-semibold">AXA</span>
                </div>
                <div class="glass rounded-xl p-3 text-center">
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-blue-500 font-semibold">Allianz</span>
                </div>
                <div class="glass rounded-xl p-3 text-center">
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-blue-500 font-semibold">Prudential</span>
                </div>
                <div class="glass rounded-xl p-3 text-center">
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-blue-500 font-semibold">Manulife</span>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== FEATURES ===== --}}
    <section id="features" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-3xl sm:text-4xl font-extrabold text-center mb-12 text-gray-900">
                {{ __('Fitur Unggulan') }}
            </h2>

            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @php
                    $features = [
                        [
                            'title' => 'Leaderboard Real-Time',
                            'desc' => 'Lihat peringkat agen dan skor terbaru dalam satu halaman.',
                            'icon' => 'M3 12l7-7v4h4v6h-4v4l-7-7z',
                        ],
                        [
                            'title' => 'Manajemen Target',
                            'desc' => 'Tetapkan dan pantau target penjualan per agen/bulan/tahun.',
                            'icon' => 'M3 5h18M6 10h12M6 15h12M6 20h12',
                        ],
                        [
                            'title' => 'Pencatatan Closing',
                            'desc' => 'Catat customer, produk, case, dan premi dengan cepat.',
                            'icon' => 'M5 13l4 4L19 7',
                        ],
                        [
                            'title' => 'Laporan & Rapor',
                            'desc' => 'Filter, sort, export Excel/PDF, dan lihat tren performa.',
                            'icon' => 'M11 17l-5-5m0 0l5-5m-5 5h12',
                        ],
                        [
                            'title' => 'Notifikasi & Reminder',
                            'desc' => 'Ingatkan agent saat target hampir tercapai.',
                            'icon' => 'M12 20l9-5-9-5-9 5 9 5z',
                        ],
                        [
                            'title' => 'Akses Mobile',
                            'desc' => 'Pantau leaderboard & target lewat ponsel kapan pun.',
                            'icon' => 'M3 7h18M3 12h18M3 17h18',
                        ],
                    ];
                @endphp

                @foreach ($features as $f)
                    <div
                        class="reveal group p-6 rounded-2xl border border-gray-100 bg-gradient-to-br from-white to-indigo-50/30 hover:from-indigo-50/40 hover:to-blue-50/40 transition soft-shadow">
                        <div
                            class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-xl text-indigo-600 bg-indigo-100 group-hover:bg-indigo-200 transition">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="{{ $f['icon'] }}" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold mb-1 text-gray-900">{{ __($f['title']) }}</h3>
                        <p class="text-gray-600">{{ __($f['desc']) }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== HOW IT WORKS ===== --}}
    <section class="py-16 bg-gray-50">
        <div class="max-w-6xl mx-auto px-6">
            <h2 class="text-3xl sm:text-4xl font-extrabold text-center mb-12 text-gray-900">
                {{ __('Cara Kerja') }}
            </h2>

            <div class="grid gap-10 sm:grid-cols-3">
                @php
                    $steps = [
                        ['n' => 1, 't' => 'Daftarkan Agen', 'd' => 'Admin menambahkan akun Agent & Head.'],
                        ['n' => 2, 't' => 'Tetapkan Target', 'd' => 'Tentukan target penjualan per periode.'],
                        ['n' => 3, 't' => 'Catat & Pantau', 'd' => 'Agen mencatat closing, Head memantau laporan.'],
                    ];
                @endphp

                @foreach ($steps as $s)
                    <div class="reveal text-center">
                        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full text-white soft-shadow-2"
                            style="background: linear-gradient(45deg,#7c3aed,#2563eb)">
                            <span class="text-lg font-bold">{{ $s['n'] }}</span>
                        </div>
                        <h4 class="text-xl font-semibold mb-1">{{ __($s['t']) }}</h4>
                        <p class="text-gray-600">{{ __($s['d']) }}</p>
                    </div>
                @endforeach
            </div>

            <div class="mt-10 text-center">
                <a href="{{ route('register') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 soft-shadow">
                    {{ __('Coba Gratis Sekarang') }}
                </a>
            </div>
        </div>
    </section>

    {{-- ===== FOOTER ===== --}}
    <footer class="bg-white">
        <div class="max-w-7xl mx-auto px-6 py-8">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <span class="inline-flex h-9 w-9 rounded-lg items-center justify-center text-white"
                        style="background: radial-gradient(120px 60px at 30% 30%, #7c3aed, #2563eb)">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2l3.5 6.5L22 10l-5 4.5L18.5 22 12 18.5 5.5 22 7 14.5 2 10l6.5-1.5L12 2z" />
                        </svg>
                    </span>
                    <span class="font-semibold">{{ config('app.name', 'AXA Leaderboard') }}</span>
                </div>
                <div class="text-sm text-gray-500">
                    &copy; {{ date('Y') }} {{ config('app.name', 'AXA Leaderboard') }}.
                    {{ __('All rights reserved.') }}
                </div>
            </div>
        </div>
    </footer>

    {{-- ===== Scroll Reveal Script (ringan) ===== --}}
    <script>
        const revealItems = document.querySelectorAll('.reveal');
        const io = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.classList.add('revealed');
                    io.unobserve(e.target);
                }
            });
        }, {
            threshold: .12
        });
        revealItems.forEach(el => io.observe(el));
    </script>
</body>

</html>
