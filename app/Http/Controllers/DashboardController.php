<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Sale;
use App\Models\User;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        if ($user->isRole('head')) {
            return $this->head($request);
        }
        if ($user->isRole('admin')) {
            return $this->admin($request);
        }
        if ($user->isRole('agent')) {
            // PENTING: teruskan $request agar agent() tidak error "Expected 1 argument"
            return $this->agent($request);
        }

        abort(403);
    }

    public function head(Request $request)
    {
        // ===== Filter global (periode & dimension) =====
        $baseInput = $request->input('date');
        $base      = $baseInput ? Carbon::parse($baseInput) : Carbon::now();

        $period = (string) $request->input('period', 'month'); // month|quarter|year
        if (!in_array($period, ['month', 'quarter', 'year'], true)) $period = 'month';

        if ($period === 'year') {
            $start = (clone $base)->startOfYear();
            $end   = (clone $base)->endOfYear();
            $groupFmt    = '%Y-%m'; // per-bulan
            $labelFmtPhp = 'M Y';
        } elseif ($period === 'quarter') {
            $start = (clone $base)->firstOfQuarter();
            $end   = (clone $base)->lastOfQuarter();
            $groupFmt    = '%x-%v'; // ISO week
            $labelFmtPhp = '\Wk W, Y';
        } else { // month
            $start = (clone $base)->startOfMonth();
            $end   = (clone $base)->endOfMonth();
            $groupFmt    = '%Y-%m-%d'; // harian
            $labelFmtPhp = 'd M';
        }

        // Filter dimensi
        $kota      = trim((string) $request->query('kota', ''));
        $productId = $request->query('product_id');
        $agency    = trim((string) $request->query('agency', ''));

        // ===== Base query: sales APPROVED di periode, + optional filters =====
        $baseQ = Sale::query()
            ->leftJoin('users', 'users.id', '=', 'sales.user_id')
            ->leftJoin('products', 'products.id', '=', 'sales.product_id')
            ->where('sales.status', Sale::STATUS_APPROVED)
            ->whereBetween('sales.sale_date', [$start, $end])
            ->when($kota !== '', fn($q) => $q->whereRaw("COALESCE(NULLIF(TRIM(users.kota),''),'Tidak diketahui') = ?", [$kota]))
            ->when(!empty($productId), fn($q) => $q->where('sales.product_id', $productId))
            ->when($agency !== '', fn($q) => $q->whereRaw("COALESCE(NULLIF(TRIM(users.agency_name),''),'(Tanpa Agency)') = ?", [$agency]));

        // ===== KPI ringkas =====
        $summary = (clone $baseQ)->selectRaw('COUNT(*) as total_cases, COALESCE(SUM(sales.premium),0) as total_premium')->first();
        $avgPremium = ($summary->total_cases ?? 0) > 0
            ? (float)$summary->total_premium / (int)$summary->total_cases
            : 0.0;

        // ===== Tren premi (timeseries) =====
        $timeseries = (clone $baseQ)
            ->selectRaw("DATE_FORMAT(sales.sale_date, '{$groupFmt}') as g, COALESCE(SUM(sales.premium),0) as total")
            ->groupBy('g')->orderBy('g')->get();

        $tsLabels = [];
        $tsValues = [];
        foreach ($timeseries as $row) {
            if ($period === 'year') {
                $date = Carbon::createFromFormat('Y-m', $row->g)->startOfMonth();
            } elseif ($period === 'quarter') {
                [$isoYear, $isoWeek] = explode('-', $row->g);
                $date = Carbon::now()->setISODate((int)$isoYear, (int)$isoWeek);
            } else {
                $date = Carbon::createFromFormat('Y-m-d', $row->g);
            }
            $tsLabels[] = $date->format($labelFmtPhp);
            $tsValues[] = (float)$row->total;
        }

        // ===== Distribusi case =====
        $byCase = (clone $baseQ)
            ->selectRaw('sales.case_level, COUNT(*) as cnt, COALESCE(SUM(sales.premium),0) as total')
            ->groupBy('sales.case_level')->orderBy('sales.case_level')->get();

        // ===== Top produk / kota / agency =====
        $byProduct = (clone $baseQ)
            ->selectRaw("COALESCE(NULLIF(TRIM(products.name),''),'(Tanpa Produk)') as product, COALESCE(SUM(sales.premium),0) as total")
            ->groupBy('product')->orderByDesc('total')->limit(8)->get();

        $byCity = (clone $baseQ)
            ->selectRaw("COALESCE(NULLIF(TRIM(users.kota),''),'Tidak diketahui') as city, COALESCE(SUM(sales.premium),0) as total")
            ->groupBy('city')->orderByDesc('total')->limit(8)->get();

        $byAgency = (clone $baseQ)
            ->selectRaw("COALESCE(NULLIF(TRIM(users.agency_name),''),'(Tanpa Agency)') as agency, COALESCE(SUM(sales.premium),0) as total")
            ->groupBy('agency')->orderByDesc('total')->limit(8)->get();

        // ===== Top 3 agent (bar vertikal) =====
        $topAgents = (clone $baseQ)
            ->selectRaw("users.id as uid, COALESCE(NULLIF(TRIM(users.name),''),'(Tanpa User)') as name, COALESCE(SUM(sales.premium),0) as total")
            ->groupBy('uid', 'name')->orderByDesc('total')->limit(3)->get();

        // Sumber dropdown filter
        $products = Product::orderBy('name')->get(['id', 'name']);
        $cities   = Sale::query()
            ->leftJoin('users', 'users.id', '=', 'sales.user_id')
            ->selectRaw("COALESCE(NULLIF(TRIM(users.kota),''),'Tidak diketahui') as city")
            ->where('sales.status', Sale::STATUS_APPROVED)
            ->groupBy('city')->orderBy('city')->pluck('city');
        $agencies = Sale::query()
            ->leftJoin('users', 'users.id', '=', 'sales.user_id')
            ->selectRaw("COALESCE(NULLIF(TRIM(users.agency_name),''),'(Tanpa Agency)') as agency")
            ->where('sales.status', Sale::STATUS_APPROVED)
            ->groupBy('agency')->orderBy('agency')->pluck('agency');

        return view('dashboard.sales', compact(
            'period',
            'base',
            'start',
            'end',
            'summary',
            'avgPremium',
            'tsLabels',
            'tsValues',
            'byCase',
            'byProduct',
            'byCity',
            'byAgency',
            'topAgents',
            'products',
            'cities',
            'agencies',
            'kota',
            'productId',
            'agency'
        ));
    }


    public function agent(Request $request)
    {
        // ---------- Filter Periode ----------
        $baseInput = $request->input('date');
        $base      = $baseInput ? Carbon::parse($baseInput) : Carbon::now();

        $period = (string) $request->input('period', 'month'); // month|quarter|year
        if (!in_array($period, ['month', 'quarter', 'year'], true)) {
            $period = 'month';
        }

        if ($period === 'year') {
            $start = (clone $base)->startOfYear();
            $end   = (clone $base)->endOfYear();
            $groupFmt    = '%Y-%m';
            $labelFmtPhp = 'M Y';
        } elseif ($period === 'quarter') {
            $start = (clone $base)->firstOfQuarter();
            $end   = (clone $base)->lastOfQuarter();
            $groupFmt    = '%x-%v';
            $labelFmtPhp = '\Wk W, Y';
        } else { // month
            $start = (clone $base)->startOfMonth();
            $end   = (clone $base)->endOfMonth();
            $groupFmt    = '%Y-%m-%d';
            $labelFmtPhp = 'd M';
        }

        // ---------- Query Dasar ----------
        $salesQ = Sale::query()
            ->with(['product', 'user'])
            ->where('status', Sale::STATUS_APPROVED)
            ->whereBetween('sale_date', [$start, $end])
            // ⬇️ Agent hanya melihat datanya sendiri
            ->where('user_id', $request->user()->id);


        // ---------- Ringkasan ----------
        $summary = (clone $salesQ)
            ->selectRaw('COUNT(*) as total_cases, COALESCE(SUM(premium),0) as total_premium, COUNT(DISTINCT product_id) as total_products')
            ->first();

        // ---------- Timeseries ----------
        $timeseries = (clone $salesQ)
            ->selectRaw("DATE_FORMAT(sale_date, '{$groupFmt}') as g, COALESCE(SUM(premium),0) as total")
            ->groupBy('g')
            ->orderBy('g')
            ->get();

        $tsLabels = [];
        $tsValues = [];
        foreach ($timeseries as $row) {
            if ($period === 'year') {
                $date = Carbon::createFromFormat('Y-m', $row->g)->startOfMonth();
            } elseif ($period === 'quarter') {
                [$isoYear, $isoWeek] = explode('-', $row->g);
                $date = Carbon::now()->setISODate((int) $isoYear, (int) $isoWeek);
            } else {
                $date = Carbon::createFromFormat('Y-m-d', $row->g);
            }
            $tsLabels[] = $date->format($labelFmtPhp);
            $tsValues[] = (float) $row->total;
        }

        // ---------- Top Produk ----------
        $byProduct = (clone $salesQ)
            ->leftJoin('products', 'products.id', '=', 'sales.product_id')
            ->selectRaw("
            COALESCE(products.id, 0) AS pid,
            COALESCE(NULLIF(TRIM(products.name),''),'(Tanpa Produk)') AS product,
            COALESCE(SUM(sales.premium),0) AS total
        ")
            ->groupBy('pid', 'product')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        // ---------- Top Kota ----------
        $byCity = (clone $salesQ)
            ->join('users', 'users.id', '=', 'sales.user_id')
            ->selectRaw("COALESCE(NULLIF(TRIM(users.kota),''),'Tidak diketahui') as city, COALESCE(SUM(premium),0) as total")
            ->groupBy('city')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        // ---------- Distribusi Case ----------
        $byCase = (clone $salesQ)
            ->selectRaw('case_level, COUNT(*) as cnt, COALESCE(SUM(premium),0) as total')
            ->groupBy('case_level')
            ->orderBy('case_level')
            ->get();

        // ---------- Leaderboard ----------
        $leaderAgents = (clone $salesQ)
            ->join('users', 'users.id', '=', 'sales.user_id')
            ->selectRaw('users.id, users.name, COALESCE(SUM(premium),0) as total, COUNT(*) as cases, COALESCE(NULLIF(TRIM(users.kota),""),"Tidak diketahui") as city')
            ->groupBy('users.id', 'users.name', 'users.kota')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $leaderCities = (clone $salesQ)
            ->join('users', 'users.id', '=', 'sales.user_id')
            ->selectRaw('COALESCE(NULLIF(TRIM(users.kota),""),"Tidak diketahui") as city, COALESCE(SUM(premium),0) as total, COUNT(*) as cases')
            ->groupBy('city')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $leaderCases = (clone $salesQ)
            ->join('users', 'users.id', '=', 'sales.user_id')
            ->selectRaw('users.id, users.name, COUNT(*) as cases, COALESCE(SUM(premium),0) as total')
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('cases')
            ->limit(10)
            ->get();

        return view('dashboard.agent', compact(
            'period',
            'base',
            'start',
            'end',
            'summary',
            'tsLabels',
            'tsValues',
            'byProduct',
            'byCity',
            'byCase',
            'leaderAgents',
            'leaderCities',
            'leaderCases'
        ));
    }

    public function admin(Request $request)
    {
        // ===== Filter periode =====
        $baseInput = $request->input('date');
        $base      = $baseInput ? Carbon::parse($baseInput) : Carbon::now();

        $period = (string) $request->input('period', 'month'); // month|quarter|year
        if (!in_array($period, ['month', 'quarter', 'year'], true)) $period = 'month';

        if ($period === 'year') {
            $start = (clone $base)->startOfYear();
            $end   = (clone $base)->endOfYear();
        } elseif ($period === 'quarter') {
            $start = (clone $base)->firstOfQuarter();
            $end   = (clone $base)->lastOfQuarter();
        } else { // month
            $start = (clone $base)->startOfMonth();
            $end   = (clone $base)->endOfMonth();
        }

        // Query dasar: hanya penjualan (sales APPPROVED) dalam periode
        $salesQ = Sale::query()
            ->where('status', Sale::STATUS_APPROVED)
            ->whereBetween('sale_date', [$start, $end]);

        // ===== Top 3 Agent (total premi) =====
        $topAgents = (clone $salesQ)
            ->leftJoin('users', 'users.id', '=', 'sales.user_id')
            ->selectRaw("
            COALESCE(users.id, 0)                                           AS uid,
            COALESCE(NULLIF(TRIM(users.name),''),'(Tanpa User)')             AS name,
            COALESCE(SUM(sales.premium),0)                                   AS total
        ")
            ->groupBy('uid', 'name')
            ->orderByDesc('total')
            ->limit(3)
            ->get();

        // ===== Top Kota (total premi) =====
        $topCities = (clone $salesQ)
            ->leftJoin('users', 'users.id', '=', 'sales.user_id')
            ->selectRaw("
            COALESCE(NULLIF(TRIM(users.kota),''),'Tidak diketahui')          AS city,
            COALESCE(SUM(sales.premium),0)                                   AS total
        ")
            ->groupBy('city')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // ===== Top Agency (total premi) =====
        $topAgencies = (clone $salesQ)
            ->leftJoin('users', 'users.id', '=', 'sales.user_id')
            ->selectRaw("
            COALESCE(NULLIF(TRIM(users.agency_name),''),'(Tanpa Agency)')    AS agency,
            COALESCE(SUM(sales.premium),0)                                   AS total
        ")
            ->groupBy('agency')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Ringkas untuk header
        $summary = (clone $salesQ)->selectRaw('COUNT(*) as cases, COALESCE(SUM(premium),0) as premium')->first();

        return view('dashboard.admin', [
            'period'      => $period,
            'base'        => $base,
            'start'       => $start,
            'end'         => $end,
            'summary'     => $summary,
            'topAgents'   => $topAgents,
            'topCities'   => $topCities,
            'topAgencies' => $topAgencies,
        ]);
    }
}
