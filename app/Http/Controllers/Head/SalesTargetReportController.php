<?php

namespace App\Http\Controllers\Head;

use App\Http\Controllers\Controller;
use App\Models\SalesTarget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesTargetReportController extends Controller
{
    public function index(Request $request)
    {
        // Filter periode via query string ?period=YYYY-MM
        $period = $request->input('period', now()->format('Y-m'));

        // Ambil semua SalesTarget di periode itu, plus closed_sum
        $targets = SalesTarget::withCount([
            'closings as closed_sum' => function ($q) {
                $q->select(DB::raw('coalesce(sum(premium_amount),0)'));
            }
        ])
            ->where('period', $period)
            ->with('user')
            ->get();

        // Urutkan berdasarkan closed_sum desc, kemudian reset keys
        $ranked = $targets
            ->sortByDesc('closed_sum')
            ->values();

        // Hitung total target & realisasi keseluruhan
        $totalTarget    = $targets->sum('target_amount');
        $totalRealisasi = $targets->sum('closed_sum');

        return view('head.reports.sales_targets', compact(
            'period',
            'ranked',
            'totalTarget',
            'totalRealisasi'
        ));
    }
}
