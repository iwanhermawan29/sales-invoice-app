<?php

namespace App\Http\Controllers;

use App\Models\Collaboration;
use Illuminate\Http\Request;

class PublicCollaborationController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        // basis query tanpa kolom city
        $base = Collaboration::query()
            ->active()
            ->when($q !== '', fn($qr) => $qr->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            }))
            ->select(['id', 'name', 'description', 'image_path', 'image_mime', 'website_url', 'is_featured']);

        // featured section
        $featured = (clone $base)
            ->where('is_featured', true)
            ->orderBy('name')
            ->limit(8)
            ->get();

        // daftar utama (hindari duplikasi featured)
        $collabs = (clone $base)
            ->when($featured->isNotEmpty(), fn($qr) => $qr->whereNotIn('id', $featured->pluck('id')))
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        // tidak ada filter kota
        return view('public.collaboration', [
            'q'        => $q,
            'featured' => $featured,
            'collabs'  => $collabs,
        ]);
    }
}
