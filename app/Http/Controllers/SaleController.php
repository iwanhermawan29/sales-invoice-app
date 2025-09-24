<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSaleRequest;
use App\Http\Requests\UpdateSaleRequest;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class SaleController extends Controller
{
    /**
     * Daftar penjualan (umum). Default urut terbaru tanpa filter.
     */
    public function index(Request $request)
    {
        $user      = $request->user();
        $q         = trim((string) $request->query('q', ''));
        $productId = $request->query('product_id');
        $month     = trim((string) $request->query('month', ''));
        $sort      = (string) $request->query('sort', 'sale_date');
        $dir       = strtolower((string) $request->query('dir', 'desc'));

        $sortable = ['sale_date', 'customer_name', 'premium', 'case_level'];
        if (!in_array($sort, $sortable, true)) $sort = 'sale_date';
        if (!in_array($dir, ['asc', 'desc'], true)) $dir = 'desc';

        $sales = \App\Models\Sale::query()
            ->with('product')
            // Agent hanya lihat data miliknya
            ->when(
                \Illuminate\Support\Facades\Gate::denies('approve-sales'),
                fn($qr) => $qr->where('user_id', $user->id)
            )
            ->when($q !== '', fn($qr) => $qr->where('customer_name', 'like', "%{$q}%"))
            ->when(!empty($productId), fn($qr) => $qr->where('product_id', $productId))
            ->when($month !== '', fn($qr) => $qr->whereRaw("DATE_FORMAT(sale_date, '%Y-%m') = ?", [$month]))
            ->orderBy($sort, $dir)
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        $products = \App\Models\Product::query()->orderBy('name')->get(['id', 'name']);

        return view('sales.index', compact('sales', 'products', 'q', 'productId', 'month', 'sort', 'dir'));
    }

    /**
     * Halaman admin untuk verifikasi (with status filter).
     */
    public function adminIndex(Request $request)
    {
        // CAST ke string biasa agar tidak jadi Stringable
        $status = $request->string('status', 'pending')->toString(); // <= penting!
        $q         = trim((string) $request->query('q', ''));
        $productId = $request->query('product_id');
        $month     = trim((string) $request->query('month', ''));
        $sort      = $request->string('sort', 'sale_date')->toString();
        $dir       = $request->string('dir', 'desc')->toString();

        $sortable = ['sale_date', 'customer_name', 'premium', 'case_level', 'status', 'approved_at'];
        if (!in_array($sort, $sortable, true)) $sort = 'sale_date';
        if (!in_array(strtolower($dir), ['asc', 'desc'], true)) $dir = 'desc';

        $statusMap = [
            'pending'  => Sale::STATUS_PENDING,
            'approved' => Sale::STATUS_APPROVED,
            'rejected' => Sale::STATUS_REJECTED,
            'all'      => 'all', // sentinel
        ];

        // fallback aman kalau user kirim nilai aneh
        $status = array_key_exists($status, $statusMap) ? $status : 'pending';

        $sales = Sale::query()
            ->with(['product', 'approver'])
            ->when($status !== 'all', fn($qr) => $qr->where('status', $statusMap[$status]))
            ->when($q !== '', fn($qr) => $qr->where('customer_name', 'like', "%{$q}%"))
            ->when(!empty($productId), fn($qr) => $qr->where('product_id', $productId))
            ->when($month !== '', fn($qr) => $qr->whereRaw("DATE_FORMAT(sale_date, '%Y-%m') = ?", [$month]))
            ->orderBy($sort, $dir)
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        $products = Product::orderBy('name')->get(['id', 'name']);

        return view('sales.admin.index', compact('sales', 'products', 'q', 'productId', 'month', 'sort', 'dir', 'status'));
    }

    public function create()
    {
        $products = Product::query()->orderBy('name')->get(['id', 'name']);
        return view('sales.create', compact('products'));
    }

    public function store(StoreSaleRequest $request)
    {
        // gabungkan data terverifikasi + user_id dari auth
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;

        Sale::create($data);

        return redirect()
            ->route('sales.index')
            ->with('status', 'Penjualan berhasil ditambahkan.');
    }

    public function show(Sale $sale)
    {
        $sale->load(['product', 'approver']);
        return view('sales.show', compact('sale'));
    }

    public function edit(Sale $sale)
    {
        // Larang edit jika sudah approved/rejected, kecuali admin (approve-sales)
        if ($sale->status !== \App\Models\Sale::STATUS_PENDING && Gate::denies('approve-sales')) {
            return redirect()
                ->route('sales.index')
                ->with('error', 'Data sudah disetujui/ditolak dan tidak bisa diedit.');
        }

        $products = \App\Models\Product::query()->orderBy('name')->get(['id', 'name']);
        return view('sales.edit', compact('sale', 'products'));
    }

    public function update(\App\Http\Requests\UpdateSaleRequest $request, \App\Models\Sale $sale)
    {
        // Larang update jika sudah approved/rejected, kecuali admin (approve-sales)
        if ($sale->status !== \App\Models\Sale::STATUS_PENDING && Gate::denies('approve-sales')) {
            return redirect()
                ->route('sales.index')
                ->with('error', 'Data sudah disetujui/ditolak dan tidak bisa diubah.');
        }

        $sale->update($request->validated());
        return redirect()->route('sales.index')->with('status', 'Penjualan berhasil diperbarui.');
    }

    public function destroy(\App\Models\Sale $sale)
    {
        // Larang hapus jika sudah approved/rejected, kecuali admin (approve-sales)
        if ($sale->status !== \App\Models\Sale::STATUS_PENDING && Gate::denies('approve-sales')) {
            return redirect()
                ->route('sales.index')
                ->with('error', 'Data sudah disetujui/ditolak dan tidak bisa dihapus.');
        }

        $sale->delete();
        return redirect()->route('sales.index')->with('status', 'Penjualan dihapus.');
    }

    /**
     * Setujui penjualan (Admin).
     */
    public function approve(Request $request, Sale $sale)
    {
        abort_if($sale->status !== Sale::STATUS_PENDING, 400, 'Hanya data pending yang dapat disetujui');

        DB::transaction(function () use ($sale, $request) {
            $sale->update([
                'status'       => Sale::STATUS_APPROVED,
                'approved_by'  => $request->user()->id,
                'approved_at'  => now(),
                'approval_note' => $request->string('approval_note') ?: null,
            ]);
        });

        return back()->with('status', 'Penjualan disetujui.');
    }

    /**
     * Tolak penjualan (Admin) dengan alasan opsional.
     */
    public function reject(Request $request, Sale $sale)
    {
        abort_if($sale->status !== Sale::STATUS_PENDING, 400, 'Hanya data pending yang dapat ditolak');

        $data = $request->validate([
            'approval_note' => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($sale, $request, $data) {
            $sale->update([
                'status'       => Sale::STATUS_REJECTED,
                'approved_by'  => $request->user()->id,
                'approved_at'  => now(),
                'approval_note' => $data['approval_note'] ?? null,
            ]);
        });

        return back()->with('status', 'Penjualan ditolak.');
    }
}
