<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SalesTarget;
use Illuminate\Http\Request;

class SalesTargetController extends Controller
{
    public function index()
    {
        $targets = SalesTarget::with('user')->paginate(10);
        return view('admin.sales_targets.index', compact('targets'));
    }

    public function edit(SalesTarget $salesTarget)
    {
        return view('admin.sales_targets.edit', compact('salesTarget'));
    }

    public function update(Request $request, SalesTarget $salesTarget)
    {
        $request->validate([
            'period'        => 'required|string|max:7',
            'target_amount' => 'required|numeric|min:0',
            'notes'         => 'nullable|string',
        ]);

        $salesTarget->update($request->only(['period', 'target_amount', 'notes']));

        return redirect()->route('admin.sales-targets.index')
            ->with('success', 'Target berhasil diperbarui.');
    }

    public function destroy(SalesTarget $salesTarget)
    {
        $salesTarget->delete();
        return redirect()->route('admin.sales-targets.index')
            ->with('success', 'Target berhasil dihapus.');
    }

    // detail closing
    public function closings(SalesTarget $salesTarget)
    {
        $closings = $salesTarget->closings()->get();
        return view('admin.sales_targets.closings', compact('salesTarget', 'closings'));
    }
}
