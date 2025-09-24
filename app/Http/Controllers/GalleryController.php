<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Contest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    /**
     * Halaman ADMIN – daftar & filter galeri.
     * Route: GET /galleries
     */
    public function index(Request $request)
    {
        $q     = trim((string) $request->query('q', ''));
        $city  = (string) $request->query('city', '');
        $sort  = (string) $request->query('sort', 'taken_at');
        $dir   = strtolower((string) $request->query('dir', 'desc'));

        if (! in_array($sort, ['taken_at', 'created_at', 'city', 'title'], true)) $sort = 'taken_at';
        if (! in_array($dir, ['asc', 'desc'], true)) $dir = 'desc';

        $galleries = Gallery::query()
            ->with('contest')
            ->when($q !== '', function ($qr) use ($q) {
                $qr->where(function ($w) use ($q) {
                    $w->where('title', 'like', "%{$q}%")
                        ->orWhere('caption', 'like', "%{$q}%");
                });
            })
            ->when($city !== '', fn($qr) => $qr->where('city', $city))
            ->orderBy($sort, $dir)
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        // Dropdown kota (kalau tak disuplai dari DB)
        $cities = ['Jakarta', 'Medan', 'Bandung', 'Semarang', 'Surabaya', 'Bali', 'Manado', 'Makassar', 'Palembang'];

        return view('galleries.index', [
            'galleries' => $galleries,
            'q'         => $q,
            'city'      => $city,
            'cities'    => $cities,
        ]);
    }

    /**
     * Halaman form tambah – ADMIN.
     * Route: GET /galleries/create
     */
    public function create()
    {
        $contests = Contest::query()->orderBy('nama_kontes')->get(['id', 'nama_kontes']);
        return view('galleries.create', compact('contests'));
    }

    /**
     * Simpan data – ADMIN.
     * Route: POST /galleries
     */
    public function store(Request $request)
    {
        $cities = ['Jakarta', 'Medan', 'Bandung', 'Semarang', 'Surabaya', 'Bali', 'Manado', 'Makassar', 'Palembang'];

        $data = $request->validate([
            'city'         => ['required', 'in:' . implode(',', $cities)],
            'taken_at'     => ['nullable', 'date'],
            'title'        => ['nullable', 'string', 'max:100'],
            'caption'      => ['nullable', 'string', 'max:1000'],
            'contest_id'   => ['nullable', 'exists:contests,id'],
            'is_published' => ['nullable', 'boolean'],
            'photo'        => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'], // 4MB
        ], [], [
            'city' => 'kota',
        ]);

        // Upload foto
        $path = $request->file('photo')->store('galleries', 'public');

        $gallery = new Gallery();
        $gallery->city         = $data['city'];
        $gallery->taken_at     = $data['taken_at'] ?? null;
        $gallery->title        = $data['title'] ?? null;
        $gallery->caption      = $data['caption'] ?? null;
        $gallery->contest_id   = $data['contest_id'] ?? null;
        $gallery->is_published = (int)($data['is_published'] ?? 1);
        $gallery->photo_path   = $path;
        $gallery->photo_mime   = $request->file('photo')->getMimeType();
        $gallery->save();

        return redirect()->route('galleries.index')->with('success', 'Foto berhasil ditambahkan.');
    }

    /**
     * Halaman form edit – ADMIN.
     * Route: GET /galleries/{gallery}/edit
     */
    public function edit(Gallery $gallery)
    {
        $contests = Contest::query()->orderBy('nama_kontes')->get(['id', 'nama_kontes']);
        return view('galleries.edit', compact('gallery', 'contests'));
    }

    /**
     * Update data – ADMIN.
     * Route: PUT/PATCH /galleries/{gallery}
     */
    public function update(Request $request, Gallery $gallery)
    {
        $cities = ['Jakarta', 'Medan', 'Bandung', 'Semarang', 'Surabaya', 'Bali', 'Manado', 'Makassar', 'Palembang'];

        $data = $request->validate([
            'city'         => ['required', 'in:' . implode(',', $cities)],
            'taken_at'     => ['nullable', 'date'],
            'title'        => ['nullable', 'string', 'max:100'],
            'caption'      => ['nullable', 'string', 'max:1000'],
            'contest_id'   => ['nullable', 'exists:contests,id'],
            'is_published' => ['nullable', 'boolean'],
            'photo'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ], [], [
            'city' => 'kota',
        ]);

        $gallery->city         = $data['city'];
        $gallery->taken_at     = $data['taken_at'] ?? null;
        $gallery->title        = $data['title'] ?? null;
        $gallery->caption      = $data['caption'] ?? null;
        $gallery->contest_id   = $data['contest_id'] ?? null;
        $gallery->is_published = (int)($data['is_published'] ?? $gallery->is_published);

        if ($request->hasFile('photo')) {
            // hapus foto lama kalau ada
            if ($gallery->photo_path && Storage::disk('public')->exists($gallery->photo_path)) {
                Storage::disk('public')->delete($gallery->photo_path);
            }
            $path = $request->file('photo')->store('galleries', 'public');
            $gallery->photo_path = $path;
            $gallery->photo_mime = $request->file('photo')->getMimeType();
        }

        $gallery->save();

        return redirect()->route('galleries.index')->with('success', 'Foto berhasil diperbarui.');
    }

    /**
     * Hapus (soft delete) – ADMIN.
     * Route: DELETE /galleries/{gallery}
     */
    public function destroy(Gallery $gallery)
    {
        // (opsional) hapus file fisik juga, atau biarkan jika ingin arsip.
        if ($gallery->photo_path && Storage::disk('public')->exists($gallery->photo_path)) {
            Storage::disk('public')->delete($gallery->photo_path);
        }

        $gallery->delete();
        return redirect()->route('galleries.index')->with('success', 'Foto dihapus.');
    }

    /**
     * Halaman publik /gallery – grid galeri by kota + search (published saja).
     * Route: GET /gallery  -> name: gallery.public
     */
    public function public(Request $request)
    {
        $q    = trim((string) $request->query('q', ''));
        $city = (string) $request->query('city', '');

        $galleries = Gallery::query()
            ->where('is_published', 1)
            ->when($q !== '', fn($qr) => $qr->where(
                fn($w) =>
                $w->where('title', 'like', "%{$q}%")->orWhere('caption', 'like', "%{$q}%")
            ))
            ->when($city !== '', fn($qr) => $qr->where('city', $city))
            ->orderByDesc('taken_at')
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        $cities = ['Jakarta', 'Medan', 'Bandung', 'Semarang', 'Surabaya', 'Bali', 'Manado', 'Makassar', 'Palembang'];

        // Buat halaman publik sendiri kalau mau (mis. resources/views/gallery/public.blade.php)
        return view('gallery.public', compact('galleries', 'q', 'city', 'cities'));
    }
}
