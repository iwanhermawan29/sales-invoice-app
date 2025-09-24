{{-- resources/views/public/demography.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gen Prime Demography</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.17/dist/tailwind.min.css" rel="stylesheet">
    @endif

    {{-- Leaflet CSS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <style>
        #map {
            height: 520px;
            border-radius: 1rem;
        }

        .leaflet-popup-content-wrapper {
            border-radius: .75rem;
        }

        .leaflet-control-attribution {
            display: none;
        }

        /* rapihin attribution */
        /* Siluet tepi halus */
        body::before {
            content: "";
            position: fixed;
            inset: 0;
            pointer-events: none;
            box-shadow: inset 0 0 200px rgba(0, 0, 0, .25);
        }
    </style>
</head>

<body class="antialiased text-gray-800">

    {{-- NAVBAR (centered, senada dengan halaman lain) --}}
    <nav class="w-full bg-white/10 backdrop-blur border-b border-white/20 fixed top-0 z-40">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="h-14 flex items-center justify-center gap-6 text-sm">
                <a href="{{ url('/') }}" class="text-white/90 hover:text-white">Home</a>
                <a href="{{ route('about') }}" class="text-white/90 hover:text-white">About</a>
                <a href="{{ route('demography') }}" class="text-white font-semibold">Demography</a>
                <a href="{{ url('/gallery') }}" class="text-white/90 hover:text-white">Galery</a>
                <a href="{{ url('/collaboration') }}" class="text-white/90 hover:text-white">Collaboration</a>
            </div>
        </div>
    </nav>

    {{-- BACKGROUND --}}
    <div class="min-h-screen bg-gradient-to-r from-[#3b0d7a] via-[#2b3e9a] to-[#1aa0ad] text-white pt-20 pb-16">
        <header class="max-w-5xl mx-auto px-6 text-center py-10">
            <h1 class="text-4xl sm:text-6xl font-extrabold tracking-wide drop-shadow-[0_0_12px_rgba(236,72,153,0.5)]">
                Gen Prime Demography
            </h1>
            <p class="mt-4 text-white/90 max-w-3xl mx-auto">
                Persebaran kota utama komunitas Gen Prime. Klik marker untuk melihat nama kota.
            </p>
        </header>

        <main class="max-w-6xl mx-auto px-6">
            {{-- PETA --}}
            <div id="map" class="bg-white/10"></div>

            {{-- Daftar kota --}}
            <div class="mt-6 grid gap-3 sm:grid-cols-3">
                @php
                    $cities = [
                        ['kota' => 'Jakarta', 'lat' => -6.2, 'lng' => 106.816666],
                        ['kota' => 'Medan', 'lat' => 3.595196, 'lng' => 98.672226],
                        ['kota' => 'Bandung', 'lat' => -6.917464, 'lng' => 107.619123],
                        ['kota' => 'Semarang', 'lat' => -6.966667, 'lng' => 110.416664],
                        ['kota' => 'Surabaya', 'lat' => -7.257472, 'lng' => 112.75209],
                        ['kota' => 'Denpasar (Bali)', 'lat' => -8.670458, 'lng' => 115.212629],
                        ['kota' => 'Manado', 'lat' => 1.47483, 'lng' => 124.842079],
                        ['kota' => 'Makassar', 'lat' => -5.147665, 'lng' => 119.432732],
                        ['kota' => 'Palembang', 'lat' => -2.990934, 'lng' => 104.756554],
                    ];
                @endphp
                @foreach ($cities as $c)
                    <div class="rounded-xl bg-white/10 p-4">
                        <div class="text-sm opacity-80">Kota</div>
                        <div class="text-lg font-semibold">{{ $c['kota'] }}</div>
                        <div class="text-xs opacity-70">Lat {{ $c['lat'] }}, Lng {{ $c['lng'] }}</div>
                    </div>
                @endforeach
            </div>
        </main>

        {{-- FOOTER --}}
        <footer class="text-center text-white/80 mt-12">
            &copy; {{ date('Y') }} Gen Prime • All rights reserved.
        </footer>
    </div>

    {{-- Leaflet JS --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        // Inisialisasi peta (tengah Indonesia)
        const map = L.map('map', {
                zoomControl: true,
                scrollWheelZoom: true
            })
            .setView([-2.5, 117], 4.8);

        // Tile layer OSM
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19
        }).addTo(map);

        // Data kota (sinkron dengan daftar di bawah peta)
        const cities = [{
                name: 'Jakarta',
                lat: -6.200000,
                lng: 106.816666
            },
            {
                name: 'Medan',
                lat: 3.595196,
                lng: 98.672226
            },
            {
                name: 'Bandung',
                lat: -6.917464,
                lng: 107.619123
            },
            {
                name: 'Semarang',
                lat: -6.966667,
                lng: 110.416664
            },
            {
                name: 'Surabaya',
                lat: -7.257472,
                lng: 112.752090
            },
            {
                name: 'Denpasar (Bali)',
                lat: -8.670458,
                lng: 115.212629
            },
            {
                name: 'Manado',
                lat: 1.474830,
                lng: 124.842079
            },
            {
                name: 'Makassar',
                lat: -5.147665,
                lng: 119.432732
            },
            {
                name: 'Palembang',
                lat: -2.990934,
                lng: 104.756554
            },
        ];

        // Icon ungu
        const purpleIcon = L.icon({
            iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -30],
            shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
            shadowSize: [41, 41]
        });

        // Tambahkan marker + fitBounds
        const bounds = [];
        cities.forEach(c => {
            const m = L.marker([c.lat, c.lng], {
                    icon: purpleIcon
                })
                .addTo(map)
                .bindPopup(`<b>${c.name}</b>`);
            bounds.push([c.lat, c.lng]);
        });
        if (bounds.length) map.fitBounds(bounds, {
            padding: [30, 30]
        });
    </script>
</body>

</html>
