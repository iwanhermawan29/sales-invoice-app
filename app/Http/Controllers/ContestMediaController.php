<?php

// app/Http/Controllers/ContestMediaController.php
namespace App\Http\Controllers;

use App\Models\Contest;
use App\Models\ContestMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContestMediaController extends Controller
{
    // Form & grid media per kontes (admin)
    public function index(Contest $contest)
    {
        $contest->load(['photos', 'logos']);
        return view('contests.media.index', compact('contest'));
    }

    // Upload banyak file (photo/logo)
    public function store(Request $request, Contest $contest)
    {
        $data = $request->validate([
            'type'   => ['required', 'in:photo,logo'],
            'files'  => ['required', 'array', 'min:1'],
            'files.*' => ['file', 'mimes:jpg,jpeg,png,webp,svg', 'max:3072'], // 3MB
        ], [], [
            'type' => 'tipe media',
            'files' => 'berkas',
        ]);

        foreach ($request->file('files') as $file) {
            $path = $file->store('contests/media', 'public');
            ContestMedia::create([
                'contest_id' => $contest->id,
                'type'       => $data['type'],
                'path'       => $path,
                'mime'       => $file->getClientMimeType(),
                'size'       => (int) round($file->getSize() / 1024),
                'sort_order' => 0,
            ]);
        }

        return back()->with('status', 'Media berhasil diunggah.');
    }

    // Tandai featured, ubah urutan
    public function update(Request $request, Contest $contest, ContestMedia $media)
    {
        $this->authorizeMedia($contest, $media);

        $data = $request->validate([
            'title'       => ['nullable', 'string', 'max:150'],
            'caption'     => ['nullable', 'string', 'max:255'],
            'is_featured' => ['nullable', 'boolean'],
            'sort_order'  => ['nullable', 'integer', 'min:0', 'max:100000'],
        ]);
        $media->update($data);
        return back()->with('status', 'Media diperbarui.');
    }

    // Hapus media
    public function destroy(Contest $contest, ContestMedia $media)
    {
        $this->authorizeMedia($contest, $media);

        if ($media->path && Storage::disk('public')->exists($media->path)) {
            Storage::disk('public')->delete($media->path);
        }
        $media->delete();
        return back()->with('status', 'Media dihapus.');
    }

    protected function authorizeMedia(Contest $contest, ContestMedia $media): void
    {
        abort_unless($media->contest_id === $contest->id, 404);
        // tambahkan gate/policy admin jika perlu
    }
}
