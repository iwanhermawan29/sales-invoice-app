<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    // Tampilkan daftar dengan filter & pagination
    public function index(Request $request)
    {
        $query = Item::query();

        // filter kode/nama
        if ($request->filled('q')) {
            $query->where('code', 'like', "%{$request->q}%")
                ->orWhere('name', 'like', "%{$request->q}%");
        }
        // filter status aktif
        if ($request->filled('active')) {
            $query->where('is_active', $request->active);
        }

        $items = $query->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('items.index', compact('items'));
    }

    // Form tambah
    public function create()
    {
        // ambil item terakhir berdasarkan kode
        $last = Item::orderBy('code', 'desc')->first();

        if ($last) {
            // misal kodenya ITM0001 → ambil angka setelah prefix
            $num = intval(substr($last->code, 3)) + 1;
        } else {
            $num = 1;
        }

        // format prefix + 4 digit
        $code = 'ITM' . str_pad($num, 4, '0', STR_PAD_LEFT);

        return view('items.create', compact('code'));
    }

    // Simpan baru
    public function store(Request $request)
    {
        // Validasi hanya field yang benar‐benar dikirim seperti code, name, unit, price
        $data = $request->validate([
            'code'  => 'required|unique:items,code',
            'name'  => 'required|string',
            'unit'  => 'nullable|string',
            'price' => 'required|numeric|min:0',
        ]);

        // Tangani checkbox ACTIVE secara manual
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        Item::create($data);

        return redirect()
            ->route('items.index')
            ->with('success', 'Item berhasil dibuat.');
    }

    // Detail (opsional)
    public function show(Item $item)
    {
        return view('items.show', compact('item'));
    }

    // Form edit
    public function edit(Item $item)
    {
        // Tampilkan form dengan $item
        return view('items.edit', compact('item'));
    }

    public function update(Request $request, Item $item)
    {
        // Validasi hanya untuk field yang user bisa ubah
        $data = $request->validate([
            'code'  => 'required|unique:items,code,' . $item->id,
            'name'  => 'required|string',
            'unit'  => 'nullable|string',
            'price' => 'required|numeric|min:0',
        ]);

        // Tangani checkbox Aktif
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        // Update
        $item->update($data);

        return redirect()
            ->route('items.index')
            ->with('success', 'Item berhasil diperbarui.');
    }

    // Hapus
    public function destroy(Item $item)
    {
        $item->delete();
        return redirect()->route('items.index')
            ->with('success', 'Item berhasil dihapus.');
    }

    // Cetak (print view)
    public function print(Request $request)
    {
        // gunakan filter sama seperti index
        $query = Item::query();
        if ($request->filled('q')) {
            $query->where('code', 'like', "%{$request->q}%")
                ->orWhere('name', 'like', "%{$request->q}%");
        }
        if ($request->filled('active')) {
            $query->where('is_active', $request->active);
        }
        $items = $query->orderBy('name')->get();
        return view('items.print', compact('items'));
    }
}
