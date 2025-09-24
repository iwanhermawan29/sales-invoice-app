<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

// Export (pastikan paket sudah terpasang)
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class HeadReportController extends Controller
{
    public function index(Request $request)
    {
        // Filter
        $q         = trim((string) $request->query('q', ''));             // cari customer
        $productId = $request->query('product_id');                       // id produk
        $kota      = trim((string) $request->query('kota', ''));          // users.kota
        $agency    = trim((string) $request->query('agency', ''));        // users.agency_name
        $month     = trim((string) $request->query('month', ''));         // YYYY-MM
        $sort      = (string) $request->query('sort', 'sale_date');
        $dir       = strtolower((string) $request->query('dir', 'desc'));

        $sortable = ['sale_date', 'customer_name', 'premium', 'case_level', 'product_name', 'agent_name', 'kota', 'agency_name'];
        if (!in_array($sort, $sortable, true)) $sort = 'sale_date';
        if (!in_array($dir, ['asc', 'desc'], true)) $dir = 'desc';

        // Query dasar: approved only + join users & products
        $sales = Sale::query()
            ->select([
                'sales.*',
                DB::raw("COALESCE(NULLIF(TRIM(users.kota),''),'Tidak diketahui') as kota"),
                DB::raw("COALESCE(NULLIF(TRIM(users.agency_name),''),'(Tanpa Agency)') as agency_name"),
                DB::raw("COALESCE(products.name,'(Tanpa Produk)') as product_name"),
                DB::raw("users.name as agent_name"),
            ])
            ->leftJoin('users', 'users.id', '=', 'sales.user_id')
            ->leftJoin('products', 'products.id', '=', 'sales.product_id')
            ->where('sales.status', \App\Models\Sale::STATUS_APPROVED)
            ->when($q !== '', fn($qr) => $qr->where('sales.customer_name', 'like', "%{$q}%"))
            ->when(!empty($productId), fn($qr) => $qr->where('sales.product_id', $productId))
            ->when($kota !== '', fn($qr) => $qr->whereRaw("COALESCE(NULLIF(TRIM(users.kota),''),'Tidak diketahui') = ?", [$kota]))
            ->when($agency !== '', fn($qr) => $qr->whereRaw("COALESCE(NULLIF(TRIM(users.agency_name),''),'(Tanpa Agency)') = ?", [$agency]))
            ->when($month !== '', fn($qr) => $qr->whereRaw("DATE_FORMAT(sales.sale_date, '%Y-%m') = ?", [$month]))
            ->orderBy($sort, $dir)
            ->orderByDesc('sales.id')
            ->paginate(20)
            ->withQueryString();

        // Dropdown sumber
        $products  = Product::orderBy('name')->get(['id', 'name']);
        $cities    = Sale::query()
            ->leftJoin('users', 'users.id', '=', 'sales.user_id')
            ->selectRaw("COALESCE(NULLIF(TRIM(users.kota),''),'Tidak diketahui') as kota")
            ->where('sales.status', \App\Models\Sale::STATUS_APPROVED)
            ->groupBy('kota')->orderBy('kota')->pluck('kota');
        $agencies  = Sale::query()
            ->leftJoin('users', 'users.id', '=', 'sales.user_id')
            ->selectRaw("COALESCE(NULLIF(TRIM(users.agency_name),''),'(Tanpa Agency)') as agency_name")
            ->where('sales.status', \App\Models\Sale::STATUS_APPROVED)
            ->groupBy('agency_name')->orderBy('agency_name')->pluck('agency_name');

        // Ringkasan Total halaman (berdasarkan data current page)
        $pageTotal = $sales->sum('premium');

        return view('head.reports.index', compact(
            'sales',
            'products',
            'cities',
            'agencies',
            'q',
            'productId',
            'kota',
            'agency',
            'month',
            'sort',
            'dir',
            'pageTotal'
        ));
    }

    public function exportExcel(Request $request)
    {
        // Ambil data yang sama persis dengan index (tanpa paginate)
        $rows = $this->baseQuery($request)->orderBy('sale_date', 'desc')->orderByDesc('sales.id')->get();

        $file = 'report-sales-' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new class($rows, $request) implements \Maatwebsite\Excel\Concerns\FromArray, \Maatwebsite\Excel\Concerns\WithHeadings {
            public function __construct(public $rows, public $request) {}
            public function array(): array
            {
                $out = [];
                foreach ($this->rows as $r) {
                    $out[] = [
                        'Tanggal'  => optional($r->sale_date)->format('Y-m-d'),
                        'Nasabah'  => $r->customer_name,
                        'Produk'   => $r->product_name,
                        'Case'     => $r->case_level,
                        'Premi'    => (float)$r->premium,
                        'Agent'    => $r->agent_name,
                        'Kota'     => $r->kota,
                        'Agency'   => $r->agency_name,
                    ];
                }
                return $out;
            }
            public function headings(): array
            {
                return ['Tanggal', 'Nasabah', 'Produk', 'Case', 'Premi', 'Agent', 'Kota', 'Agency'];
            }
        }, $file);
    }

    public function exportPdf(Request $request)
    {
        $rows = $this->baseQuery($request)->orderBy('sale_date', 'desc')->orderByDesc('sales.id')->get();

        $filters = [
            'q'         => $request->query('q'),
            'product'   => optional(Product::find($request->query('product_id')))->name,
            'kota'      => $request->query('kota'),
            'agency'    => $request->query('agency'),
            'month'     => $request->query('month'),
        ];

        $pdf = Pdf::loadView('head.reports.print', [
            'rows'        => $rows,
            'generatedAt' => now()->format('d M Y H:i'),
            'filters'     => array_filter($filters),
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('report-sales-' . now()->format('Ymd_His') . '.pdf');
    }

    /** Base query agar index/excel/pdf konsisten */
    private function baseQuery(Request $request)
    {
        $q         = trim((string) $request->query('q', ''));
        $productId = $request->query('product_id');
        $kota      = trim((string) $request->query('kota', ''));
        $agency    = trim((string) $request->query('agency', ''));
        $month     = trim((string) $request->query('month', ''));

        return Sale::query()
            ->select([
                'sales.*',
                DB::raw("COALESCE(NULLIF(TRIM(users.kota),''),'Tidak diketahui') as kota"),
                DB::raw("COALESCE(NULLIF(TRIM(users.agency_name),''),'(Tanpa Agency)') as agency_name"),
                DB::raw("COALESCE(products.name,'(Tanpa Produk)') as product_name"),
                DB::raw("users.name as agent_name"),
            ])
            ->leftJoin('users', 'users.id', '=', 'sales.user_id')
            ->leftJoin('products', 'products.id', '=', 'sales.product_id')
            ->where('sales.status', \App\Models\Sale::STATUS_APPROVED)
            ->when($q !== '', fn($qr) => $qr->where('sales.customer_name', 'like', "%{$q}%"))
            ->when(!empty($productId), fn($qr) => $qr->where('sales.product_id', $productId))
            ->when($kota !== '', fn($qr) => $qr->whereRaw("COALESCE(NULLIF(TRIM(users.kota),''),'Tidak diketahui') = ?", [$kota]))
            ->when($agency !== '', fn($qr) => $qr->whereRaw("COALESCE(NULLIF(TRIM(users.agency_name),''),'(Tanpa Agency)') = ?", [$agency]))
            ->when($month !== '', fn($qr) => $qr->whereRaw("DATE_FORMAT(sales.sale_date, '%Y-%m') = ?", [$month]));
    }
}
