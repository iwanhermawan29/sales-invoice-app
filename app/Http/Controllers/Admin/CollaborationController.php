<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Collaboration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CollaborationController extends Controller
{

    public function index(Request $request)
    {
        $q       = trim((string)$request->query('q', ''));
        $active  = $request->query('active', ''); // '', '1', '0'

        $collabs = Collaboration::query()
            ->when($q !== '', fn($qr) => $qr->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            }))
            ->when(
                $active !== '' && in_array($active, ['0', '1'], true),
                fn($qr) => $qr->where('is_active', (bool)$active)
            )
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.collaborations.index', compact('collabs', 'q', 'active'));
    }

    public function create()
    {
        $collab = new Collaboration();
        return view('admin.collaborations.create', compact('collab'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:2000'],
            'website_url' => ['nullable', 'url', 'max:255'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active'   => ['nullable', 'boolean'],
            'image'       => ['nullable', 'file', 'mimes:jpg,jpeg,png,svg,pdf', 'max:2048'], // 2MB
        ]);

        // normalisasi boolean
        $data['is_featured'] = (bool)($data['is_featured'] ?? false);
        $data['is_active']   = (bool)($data['is_active'] ?? true);

        // upload
        if ($request->hasFile('image')) {
            $file   = $request->file('image');
            $ext    = $file->getClientOriginalExtension();
            $path   = $file->storeAs('collaborations', Str::uuid() . '.' . $ext, 'public');

            $data['image_path'] = $path;
            $data['image_mime'] = $file->getClientMimeType();
            $data['image_size'] = $file->getSize();
        }

        Collaboration::create($data);

        return redirect()->route('collaborations.index')
            ->with('success', 'Kolaborasi berhasil ditambahkan.');
    }

    public function edit(Collaboration $collaboration)
    {
        $collab = $collaboration;
        return view('admin.collaborations.edit', compact('collab'));
    }

    public function update(Request $request, Collaboration $collaboration)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:2000'],
            'website_url' => ['nullable', 'url', 'max:255'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active'   => ['nullable', 'boolean'],
            'image'       => ['nullable', 'file', 'mimes:jpg,jpeg,png,svg,pdf', 'max:2048'],
        ]);

        $data['is_featured'] = (bool)($data['is_featured'] ?? false);
        $data['is_active']   = (bool)($data['is_active'] ?? true);

        if ($request->hasFile('image')) {
            // hapus lama
            if ($collaboration->image_path) {
                Storage::disk('public')->delete($collaboration->image_path);
            }
            $file   = $request->file('image');
            $ext    = $file->getClientOriginalExtension();
            $path   = $file->storeAs('collaborations', Str::uuid() . '.' . $ext, 'public');

            $data['image_path'] = $path;
            $data['image_mime'] = $file->getClientMimeType();
            $data['image_size'] = $file->getSize();
        }

        $collaboration->update($data);

        return redirect()->route('collaborations.index')
            ->with('success', 'Kolaborasi berhasil diperbarui.');
    }

    public function destroy(Collaboration $collaboration)
    {
        if ($collaboration->image_path) {
            Storage::disk('public')->delete($collaboration->image_path);
        }
        $collaboration->delete();

        return redirect()->route('collaborations.index')
            ->with('success', 'Kolaborasi dihapus.');
    }
}
