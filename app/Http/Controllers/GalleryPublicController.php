<?php
// app/Http/Controllers/GalleryPublicController.php
namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryPublicController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $city = trim((string) $request->query('city', ''));

        $query = Gallery::query()->latest();

        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('title', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }
        if ($city !== '') {
            $query->where('city', $city);
        }

        $galleries = $query->paginate(12)->withQueryString();
        $cities = Gallery::query()->select('city')->whereNotNull('city')->distinct()->orderBy('city')->pluck('city')->all();

        return view('gallery.public', compact('galleries', 'cities', 'q', 'city'));
    }
}
