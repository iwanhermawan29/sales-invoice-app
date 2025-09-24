<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Barryvdh\DomPDF\Facade\Pdf; // pastikan paket dompdf terpasang

class SaleExportController extends Controller
{
    protected function query(Request $request)
    {
        $q         = $request->string('q');
        $productId = $request->integer('product_id');
        $month     = $request->string('month');
        $sort      = $request->string('sort', 'sale_date');
        $dir       = $request->string('dir', 'desc');

        $sortable = ['sale_date', 'customer_name', 'premium', 'case_level'];
        if (!in_array($sort, $sortable)) $sort = 'sale_date';
        if (!in_array(strtolower($dir), ['asc', 'desc'])) $dir = 'desc';

        return Sale::with('product')
            ->when($q, fn($qr) => $qr->where('customer_name', 'like', "%{$q}%"))
            ->when($productId, fn($qr) => $qr->where('product_id', $productId))
            ->when($month, fn($qr) => $qr->whereRaw("DATE_FORMAT(sale_date, '%Y-%m') = ?", [$month]))
            ->orderBy($sort, $dir);
    }

    public function excel(Request $request): StreamedResponse
    {
        // Tanpa paket Excel, kita kirim CSV (langsung bisa dibuka Excel)
        $filename = 'sales_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $rows = $this->query($request)->get();

        return response()->stream(function () use ($rows) {
            $out = fopen('php://output', 'w');
            // BOM UTF-8 agar Excel membaca karakter Asia/IDN dengan benar
            fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($out, ['Tanggal', 'Nasabah', 'Produk', 'Case', 'Premi']);
            foreach ($rows as $r) {
                fputcsv($out, [
                    $r->sale_date?->format('Y-m-d'),
                    $r->customer_name,
                    $r->product->name ?? '-',
                    "Case {$r->case_level}",
                    number_format($r->premium, 2, ',', '.'),
                ]);
            }
            fclose($out);
        }, 200, $headers);
    }

    public function pdf(Request $request)
    {
        $rows = $this->query($request)->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('sales.export-pdf', [
            'rows'         => $this->query($request)->get(),
            'generatedAt'  => now()->format('d/m/Y H:i'),
            'filters'      => $request->only(['q', 'product_id', 'month', 'sort', 'dir']),
            'companyName'  => 'PT Pro Energi',                 // ganti
            'companyAddress' => 'Jl. Contoh No. 123, Jakarta', // opsional
            'logoPath'     => public_path('img/logo-proenergi.png'), // pastikan file ada
        ])->setPaper('a4', 'portrait');

        return $pdf->download('sales_' . now()->format('Ymd_His') . '.pdf');
    }
}
