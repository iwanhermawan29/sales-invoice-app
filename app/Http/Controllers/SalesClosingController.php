<?php

namespace App\Http\Controllers;

use App\Models\SalesTarget;
use App\Models\SalesClosing;
use Illuminate\Http\Request;

class SalesClosingController extends Controller
{
    public function create(SalesTarget $sales_target)
    {
        // Pastikan Agent hanya untuk target miliknya
        abort_unless($sales_target->user_id === auth()->id(), 403);

        return view('sales_closings.create', compact('sales_target'));
    }

    public function store(Request $request, SalesTarget $sales_target)
    {
        $data = $request->validate([
            'product_id'    => 'required|exists:products,id',
            'customer'      => 'required|string|max:255',
            'policy_number' => 'required|string|max:100',
            'premium_amount' => 'required|numeric|min:0',
            'closing_date'  => 'required|date',
            'notes'         => 'nullable|string',
        ]);

        // Buat SalesClosing via relasi, termasuk product_id
        $sales_target->closings()->create($data);

        return redirect()
            ->route('sales-targets.closings.index', $sales_target)
            ->with('success', 'Closing berhasil dicatat.');
    }

    public function index(SalesTarget $sales_target)
    {
        $closings = $sales_target->closings()->orderByDesc('closing_date')->get();
        return view('sales_closings.index', compact('sales_target', 'closings'));
    }
}
