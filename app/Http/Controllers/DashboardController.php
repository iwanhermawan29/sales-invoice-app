<?php

namespace App\Http\Controllers;             // ← tambahkan ini
use App\Models\SalesOrder;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Receivable;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;    // ← impor Controller

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->isRole('head')) {
            return $this->sales();
        }

        if ($user->isRole('admin')) {
            return $this->admin();
        }
        if ($user->isRole('agent')) {
            return $this->keuangan();
        }

        abort(403);
    }

    public function sales()
    {
        // Hitung total penjualan hari ini (sum subtotal dari items)
        $todaySales = DB::table('sales_orders')
            ->join('sales_order_items', 'sales_orders.id', '=', 'sales_order_items.sales_order_id')
            ->whereDate('sales_orders.order_date', today())
            ->sum('sales_order_items.subtotal');

        // Hitung total penjualan bulan ini
        $monthSales = DB::table('sales_orders')
            ->join('sales_order_items', 'sales_orders.id', '=', 'sales_order_items.sales_order_id')
            ->whereMonth('sales_orders.order_date', now()->month)
            ->sum('sales_order_items.subtotal');

        // Jumlah pelanggan aktif
        $activeCustomers = Customer::where('is_active', 1)->count();

        // Label bulan (untuk Chart.js)
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];

        // Data penjualan per bulan
        $monthlySales = [];
        foreach (range(1, 12) as $m) {
            $monthlySales[] = DB::table('sales_orders')
                ->join('sales_order_items', 'sales_orders.id', '=', 'sales_order_items.sales_order_id')
                ->whereMonth('sales_orders.order_date', $m)
                ->sum('sales_order_items.subtotal');
        }

        // 5 Produk terlaris berdasarkan total subtotal
        $topProducts = Item::withSum('salesOrderItems as total', 'subtotal')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return view('dashboard.sales', compact(
            'todaySales',
            'monthSales',
            'activeCustomers',
            'months',
            'monthlySales',
            'topProducts'
        ));
    }

    public function admin()
    {
        $totalCustomers       = Customer::count();
        $activeCustomers      = Customer::where('is_active', 1)->count();
        $inactiveCustomers    = $totalCustomers - $activeCustomers;
        $totalItems           = Item::count();
        $pendingSalesOrders   = SalesOrder::where('status', 'draft')->count();
        $months               = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];
        $monthlySOCounts      = collect($months)
            ->map(fn($m, $i) => SalesOrder::whereMonth('order_date', $i + 1)->count())
            ->toArray();

        return view('dashboard.admin', compact(
            'totalCustomers',
            'activeCustomers',
            'inactiveCustomers',
            'totalItems',
            'pendingSalesOrders',
            'months',
            'monthlySOCounts'
        ));
    }

    public function keuangan()
    {
        // Total outstanding (amount_due - amount_paid)
        $totalOutstanding = Receivable::sum('amount_due') - Receivable::sum('amount_paid');

        // Total paid ever
        $totalPaid = Receivable::sum('amount_paid');

        // Count of overdue (due_date past & not fully paid)
        $overdueCount = Receivable::where('due_date', '<', today())
            ->whereColumn('amount_paid', '<', 'amount_due')
            ->count();

        // Monthly data for this year
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];
        $monthlyDue   = [];
        $monthlyPaid  = [];
        foreach (range(1, 12) as $m) {
            $monthlyDue[]  = Receivable::whereYear('due_date', now()->year)
                ->whereMonth('due_date', $m)
                ->sum('amount_due');
            $monthlyPaid[] = Receivable::whereYear('due_date', now()->year)
                ->whereMonth('due_date', $m)
                ->sum('amount_paid');
        }

        return view('dashboard.keuangan', compact(
            'totalOutstanding',
            'totalPaid',
            'overdueCount',
            'months',
            'monthlyDue',
            'monthlyPaid'
        ));
    }

    public function piutangMonitoring(Request $request)
    {
        // parse inputs or default to this month
        $from = $request->input('from', now()->startOfMonth()->toDateString());
        $to   = $request->input('to',   now()->endOfMonth()->toDateString());

        // all receivables due in the period
        $query = Receivable::with('invoice.deliveryOrder.salesOrder.customer')
            ->whereBetween('due_date', [$from, $to]);

        // optionally filter only outstanding (unpaid or partial)
        if ($request->filled('outstanding_only')) {
            $query->whereColumn('amount_paid', '<', 'amount_due');
        }

        $receivables = $query->orderBy('due_date')->get();

        // summary:
        $totalDue         = $receivables->sum('amount_due');
        $totalPaid        = $receivables->sum('amount_paid');
        $totalOutstanding = $receivables->sum(fn($r) => $r->amount_due - $r->amount_paid);

        return view('dashboard.piutang-monitoring', compact(
            'receivables',
            'from',
            'to',
            'totalDue',
            'totalPaid',
            'totalOutstanding'
        ));
    }
}
