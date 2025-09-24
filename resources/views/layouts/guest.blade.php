<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">
    <!-- Full-screen gradient background -->
    <div class="min-h-screen bg-gradient-to-r from-[#3b0d7a] via-[#2b3e9a] to-[#1aa0ad] flex flex-col">
        <!-- NAVBAR -->
        <nav class="w-full bg-white/10 backdrop-blur border-b border-white/20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <!-- gunakan grid agar mudah center -->
                <div class="h-14 grid grid-cols-3 items-center">
                    <!-- Brand (kiri) -->
                    <a href="{{ url('/') }}" class="justify-self-start flex items-center gap-2">

                    </a>

                    <!-- Links (tengah) -->
                    <div class="hidden md:flex justify-self-center items-center gap-6 text-sm">
                        <a href="{{ url('/') }}" class="text-white/90 hover:text-white">Home</a>
                        <a href="{{ url('/about') }}" class="text-white/90 hover:text-white">About</a>
                        <a href="{{ url('/demography') }}" class="text-white/90 hover:text-white">
                            Demography</a>
                        <a href="{{ url('/gallery') }}" class="text-white/90 hover:text-white">Galery</a>
                        <a href="{{ route('collaboration') }}" class="text-white/90 hover:text-white">Collaboration</a>

                    </div>

                    <!-- Kolom kanan (opsional, biarkan kosong atau isi tombol) -->
                    <div class="justify-self-end"></div>
                </div>
            </div>
        </nav>


        <!-- HERO TITLE -->
        <!-- HERO TITLE -->
        <header class="relative text-center py-8 sm:py-10">
            <!-- blobs dekoratif kiri/kanan -->
            <div class="pointer-events-none absolute inset-0 -z-10">
                <div
                    class="absolute -left-10 top-0 w-40 h-40 rounded-full bg-gradient-to-br from-purple-600/50 via-fuchsia-500/40 to-pink-500/30 blur-2xl">
                </div>
                <div
                    class="absolute -right-10 bottom-0 w-44 h-44 rounded-full bg-gradient-to-tr from-pink-500/40 via-fuchsia-500/35 to-purple-600/40 blur-2xl">
                </div>
            </div>

            <!-- judul dengan gradient + glow outline -->
            <h1
                class="relative inline-block text-3xl sm:text-5xl font-extrabold tracking-wide
           text-transparent bg-clip-text bg-gradient-to-r from-purple-200 via-fuchsia-200 to-pink-200
           neon-outline">
                GEN PRIME LEADERBOARD SISTEM
            </h1>
        </header>

        <!-- style kecil untuk efek outline/glow di pinggir huruf -->
        <style>
            /* Siluet/outline halus pakai text-shadow berlapis (ungu → pink) */
            .neon-outline {
                text-shadow:
                    0 0 6px rgba(168, 85, 247, 0.55),
                    /* purple glow */
                    0 0 12px rgba(217, 70, 239, 0.45),
                    /* fuchsia glow */
                    0 0 22px rgba(236, 72, 153, 0.35);
                /* pink glow */
            }
        </style>


        <!-- CARD (Breeze slot) -->
        <div class="flex-1 flex items-start sm:items-center justify-center pb-10">
            <div class="w-full sm:max-w-md mx-4 sm:mx-0">

                <div class="px-6 py-4 bg-white/90 backdrop-blur shadow-md overflow-hidden sm:rounded-lg">
                    <div class="flex justify-center mb-6">
                        <a href="/">
                            <x-application-logo class="w-20 h-20 fill-current text-gray-100" />
                        </a>
                    </div>
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</body>



</html>
