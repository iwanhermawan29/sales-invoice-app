{{-- resources/views/public/about.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>About Gen Prime</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.17/dist/tailwind.min.css" rel="stylesheet">
    @endif
</head>

<body class="antialiased text-gray-800">

    {{-- NAVBAR (centered) --}}
    <nav class="w-full bg-white/10 backdrop-blur border-b border-white/20 fixed top-0 z-40">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="h-14 flex items-center justify-center gap-6 text-sm">
                <a href="{{ url('/') }}" class="text-white/90 hover:text-white">Home</a>
                <a href="{{ route('about') }}" class="text-white font-semibold">About</a>
                <a href="{{ url('/demography') }}" class="text-white/90 hover:text-white">Demography</a>
                <a href="{{ url('/gallery') }}" class="text-white/90 hover:text-white">Galery</a>
                <a href="{{ url('/collaboration') }}" class="text-white/90 hover:text-white">Collaboration</a>
            </div>
        </div>
    </nav>

    {{-- BACKGROUND GRADIENT --}}
    <div class="min-h-screen bg-gradient-to-r from-[#3b0d7a] via-[#2b3e9a] to-[#1aa0ad] text-white pt-20">

        {{-- HERO --}}
        <header class="max-w-5xl mx-auto px-6 text-center py-12">
            <h1 class="text-4xl sm:text-6xl font-extrabold tracking-wide drop-shadow-[0_0_12px_rgba(236,72,153,0.5)]">
                GEN PRIME
            </h1>
            <p class="mt-3 text-2xl sm:text-3xl font-semibold opacity-90">
                Komunitas Muda, Berdaya, Berkarakter, Berpengaruh
            </p>
            <p class="mt-5 text-white/90 max-w-3xl mx-auto">
                Lahir November 2024 di bawah naungan AXA Financial Indonesia, Gen Prime adalah komunitas eksklusif
                bagi business partner & leaders muda (18–35 tahun) yang berenergi, positif, dan ingin bertumbuh bersama.
            </p>
            <div class="mt-6 flex items-center justify-center gap-3">
                <span class="px-3 py-1 text-xs rounded-full bg-white/15">AXA Financial Indonesia</span>
                <span class="px-3 py-1 text-xs rounded-full bg-white/15">Sejak Nov 2024</span>
                <span class="px-3 py-1 text-xs rounded-full bg-white/15">18–35 Tahun</span>
            </div>
        </header>

        {{-- ABOUT COPY --}}
        <section class="max-w-5xl mx-auto px-6">
            <div class="grid gap-6 md:grid-cols-3">
                <div class="md:col-span-2 bg-white/10 rounded-2xl p-6 backdrop-blur">
                    <h2 class="text-2xl font-bold mb-3">Tentang Gen Prime</h2>
                    <p class="text-white/90 leading-relaxed">
                        Gen Prime bukan sekadar komunitas, melainkan wadah pengembangan diri yang memberikan ruang untuk
                        belajar, berkolaborasi, berjejaring, dan saling memotivasi. Melalui program pengembangan diri,
                        aktivitas seru, hingga kegiatan leadership yang kreatif & relevan, setiap anggota dapat
                        bertumbuh
                        secara pribadi maupun profesional.
                    </p>
                    <p class="mt-4 text-white/90 leading-relaxed">
                        Di dalamnya, anggota mendapatkan kesempatan untuk:
                    </p>
                    <ul class="mt-3 space-y-2 text-white/90">
                        <li>• Mengembangkan skill komunikasi, leadership, bisnis, dan keuangan.</li>
                        <li>• Berjejaring dengan anak muda berprestasi dari berbagai daerah.</li>
                        <li>• Mengikuti training, workshop, dan kompetisi untuk membangun mental pemenang.</li>
                        <li>• Menjaga motivasi lewat dukungan komunitas yang solid dan positif.</li>
                    </ul>
                    <p class="mt-4 text-white/90 leading-relaxed">
                        Dengan semangat kebersamaan, Gen Prime menjadi rumah kedua bagi anak muda visioner untuk
                        membangun masa depan lebih cerah—bagi diri sendiri maupun masyarakat luas.
                    </p>
                </div>

                <aside class="space-y-4">
                    <div class="bg-white/10 rounded-2xl p-5">
                        <h3 class="font-semibold mb-2">Nilai Inti</h3>
                        <ul class="space-y-1 text-white/90 text-sm">
                            <li>• Growth Mindset</li>
                            <li>• Integrity & Trust</li>
                            <li>• Collaboration</li>
                            <li>• Impact Driven</li>
                        </ul>
                    </div>
                    <div class="bg-white/10 rounded-2xl p-5">
                        <h3 class="font-semibold mb-2">Program Unggulan</h3>
                        <ul class="space-y-1 text-white/90 text-sm">
                            <li>• Prime Talks & Mentoring</li>
                            <li>• Leadership Bootcamp</li>
                            <li>• Business & Financial Clinic</li>
                            <li>• Challenge & Recognition</li>
                        </ul>
                    </div>
                    <div class="bg-white/10 rounded-2xl p-5">
                        <h3 class="font-semibold mb-2">Arah & Misi</h3>
                        <p class="text-sm text-white/90">
                            Mencetak generasi muda yang berdaya, berkarakter, dan berpengaruh melalui ekosistem
                            pembelajaran,
                            kolaborasi, dan apresiasi yang berkelanjutan.
                        </p>
                    </div>
                </aside>
            </div>
        </section>

        {{-- STATS --}}
        <section class="max-w-5xl mx-auto px-6 mt-10">

        </section>

        {{-- CTA --}}
        <section class="max-w-5xl mx-auto px-6 my-12">
            <div
                class="rounded-2xl bg-white/10 p-6 md:p-8 flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="mb-4 md:mb-0">
                    <h3 class="text-xl font-semibold">Siap tumbuh bersama Gen Prime?</h3>
                    <p class="text-white/90 text-sm mt-1">Gabung dan rasakan ekosistem yang mendorongmu jadi versi
                        terbaik.</p>
                </div>
                @auth
                    <a href="{{ url('/dashboard') }}"
                        class="inline-flex items-center justify-center rounded-xl bg-white text-indigo-700 font-semibold px-5 py-3 hover:bg-gray-100">
                        Buka Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center justify-center rounded-xl bg-white text-indigo-700 font-semibold px-5 py-3 hover:bg-gray-100">
                        Log in
                    </a>
                @endauth
            </div>
        </section>

        {{-- FOOTER --}}
        <footer class="text-center text-white/80 py-8">
            &copy; {{ date('Y') }} Gen Prime • All rights reserved.
        </footer>
    </div>

    {{-- Subtle vignette/siluet efek di tepi --}}
    <style>
        body::before {
            content: "";
            position: fixed;
            inset: 0;
            pointer-events: none;
            box-shadow: inset 0 0 200px rgba(0, 0, 0, .25);
        }
    </style>
</body>

</html>
