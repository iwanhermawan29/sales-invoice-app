<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SalesTarget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TargetController extends Controller
{
    /**
     * Display a listing of sales targets (Admin only).
     */
    public function index()
    {
        $targets = SalesTarget::with('user')->orderByDesc('created_at')->paginate(10);
        return view('sales_targets.index', compact('targets'));
    }

    /**
     * Show the form for creating a new sales target (Agent only).
     */
    public function create()
    {
        return view('sales_targets.create');
    }

    /**
     * Store a newly created sales target in storage (Agent only).
     */
    public function store(Request $request)
    {
        $request->validate([
            'period'        => 'required|string|max:7', // e.g. YYYY-MM
            'target_amount' => 'required|numeric|min:0',
            'notes'         => 'nullable|string',
        ]);

        SalesTarget::create([
            'user_id'       => Auth::id(),
            'period'        => $request->period,
            'target_amount' => $request->target_amount,
            'notes'         => $request->notes,
        ]);

        return redirect()->route('sales-targets.index')
            ->with('success', 'Target penjualan berhasil dibuat.');
    }

    /**
     * Show the form for editing the specified sales target (Admin only).
     */
    public function edit(SalesTarget $salesTarget)
    {
        return view('sales_targets.edit', compact('salesTarget'));
    }

    /**
     * Update the specified sales target in storage (Admin only).
     */
    public function update(Request $request, SalesTarget $salesTarget)
    {
        $request->validate([
            'period'        => 'required|string|max:7',
            'target_amount' => 'required|numeric|min:0',
            'notes'         => 'nullable|string',
        ]);

        $salesTarget->update($request->only(['period', 'target_amount', 'notes']));

        return redirect()->route('sales-targets.index')
            ->with('success', 'Target penjualan berhasil diperbarui.');
    }

    /**
     * Remove the specified sales target from storage (Admin only).
     */
    public function destroy(SalesTarget $salesTarget)
    {
        $salesTarget->delete();

        return redirect()->route('sales-targets.index')
            ->with('success', 'Target penjualan berhasil dihapus.');
    }
}
