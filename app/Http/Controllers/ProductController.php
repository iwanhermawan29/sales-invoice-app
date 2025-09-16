<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    //     $this->middleware('role:admin');
    // }

    /** Display a listing of products. */
    public function index()
    {
        $products = Product::paginate(10);
        return view('products.index', compact('products'));
    }

    /** Show the form for creating a new product. */
    public function create()
    {
        return view('products.create');
    }

    /** Store a newly created product in storage. */
    public function store(Request $request)
    {
        $request->validate([
            'type'        => 'required|in:health,life',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Product::create($request->only('type', 'name', 'description'));

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil dibuat.');
    }

    /** Show the form for editing the specified product. */
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    /** Update the specified product in storage. */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'type'        => 'required|in:health,life',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $product->update($request->only('type', 'name', 'description'));

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    /** Remove the specified product from storage. */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
