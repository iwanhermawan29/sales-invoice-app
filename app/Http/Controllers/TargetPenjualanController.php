<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\TargetPenjualan;
use App\Models\User;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TargetPenjualanController extends Controller
{
    // Opsional: batasi akses


    /**
     * LIST + filter
     */
    public function index(Request $request)
    {
        $q         = trim((string) $request->input('q', ''));
        $agentId   = $request->input('agent_id');
        $productId = $request->input('product_id');
        $period    = $request->input('period');
        $from      = $request->input('from'); // YYYY-MM-DD
        $to        = $request->input('to');   // YYYY-MM-DD

        $targets = TargetPenjualan::query()
            ->with(['agent:id,name', 'product:id,name'])
            ->when(
                $q !== '',
                fn($qr) =>
                $qr->where(function ($w) use ($q) {
                    $w->where('title', 'like', "%{$q}%")
                        ->orWhere('notes', 'like', "%{$q}%");
                })
            )
            ->when($agentId, fn($qr) => $qr->where('agent_id', $agentId))
            ->when($productId, fn($qr) => $qr->where('product_id', $productId))
            ->when(
                in_array($period ?? '', ['monthly', 'quarterly', 'annual'], true),
                fn($qr) => $qr->where('period', $period)
            )
            ->when($from, fn($qr) => $qr->whereDate('start_date', '>=', $from))
            ->when($to,   fn($qr) => $qr->whereDate('end_date',   '<=', $to))
            ->orderByDesc('start_date')
            ->orderBy('agent_id')
            ->paginate(12)
            ->withQueryString();

        $agents   = User::query()->orderBy('name')->get(['id', 'name']);
        $products = Product::query()->orderBy('name')->get(['id', 'name']);

        return view('targets_penjualan.index', compact(
            'targets',
            'agents',
            'products',
            'q',
            'agentId',
            'productId',
            'period',
            'from',
            'to'
        ));
    }

    // app/Http/Controllers/TargetsPenjualanController.php

    // app/Http/Controllers/TargetPenjualanController.php

    public function agentIndex(Request $request)
    {
        $agentId = $request->user()->id;

        $q      = trim((string) $request->query('q', ''));
        // gunakan string() -> toString() agar tidak jadi Stringable
        $period = $request->string('period', '')->toString(); // '', monthly, quarterly, annual

        // (opsional) filter rentang tanggal
        $from = $request->date('from'); // YYYY-MM-DD
        $to   = $request->date('to');

        $targets = \App\Models\TargetPenjualan::query()
            ->with(['product', 'agent'])
            ->where('agent_id', $agentId)
            ->when($q !== '', fn($qr) => $qr->where('title', 'like', "%{$q}%"))
            ->when(
                in_array($period, ['monthly', 'quarterly', 'annual'], true),
                fn($qr) => $qr->where('period', $period)
            )
            // jika user isi from/to: tampilkan target yang “overlap” dengan rentang tsb
            ->when($from, fn($qr) => $qr->whereDate('end_date', '>=', $from))
            ->when($to,   fn($qr) => $qr->whereDate('start_date', '<=', $to))
            ->orderByDesc('is_active')
            ->orderByDesc('id')
            ->paginate(9)
            ->withQueryString();

        // === Hitung progress per target (premi & case) ===
        $progress = [];
        foreach ($targets as $t) {
            $salesQ = \App\Models\Sale::query()
                ->where('status', \App\Models\Sale::STATUS_APPROVED)
                ->where('user_id', $agentId)
                ->when($t->product_id, fn($qr) => $qr->where('product_id', $t->product_id))
                ->when($t->start_date, fn($qr) => $qr->whereDate('sale_date', '>=', $t->start_date))
                ->when($t->end_date,   fn($qr) => $qr->whereDate('sale_date', '<=', $t->end_date));

            $premiTerjual = (float) $salesQ->clone()->sum('premium');
            $caseTerjual  = (int)   $salesQ->clone()->count();

            $targetPremi = (float) $t->target_premium;
            $targetCase  = (int)   $t->target_case;

            $premiPct = $targetPremi > 0 ? round(($premiTerjual / $targetPremi) * 100, 1) : null;
            $casePct  = $targetCase  > 0 ? round(($caseTerjual  / $targetCase)  * 100, 1) : null;

            $progress[$t->id] = [
                'premi'     => $premiTerjual,
                'cases'     => $caseTerjual,
                'premi_pct' => $premiPct,
                'case_pct'  => $casePct,
            ];
        }

        return view('targets.agent', compact('targets', 'progress', 'q', 'period', 'from', 'to'));
    }

    /**
     * FORM CREATE
     */
    public function create()
    {
        $agents   = User::query()->orderBy('name')->get(['id', 'name']);
        $products = Product::query()->orderBy('name')->get(['id', 'name']);

        return view('targets_penjualan.create', compact('agents', 'products'));
    }

    /**
     * STORE
     */
    public function store(Request $request)
    {
        $data = $this->validateData($request);

        TargetPenjualan::create($data);

        return redirect()
            ->route('targets-penjualan.index')
            ->with('success', 'Target penjualan berhasil dibuat.');
    }

    /**
     * FORM EDIT
     */
    public function edit(TargetPenjualan $targets_penjualan)
    {
        $target   = $targets_penjualan;
        $agents   = User::query()->orderBy('name')->get(['id', 'name']);
        $products = Product::query()->orderBy('name')->get(['id', 'name']);

        return view('targets_penjualan.edit', compact('target', 'agents', 'products'));
    }

    /**
     * UPDATE
     */
    public function update(Request $request, TargetPenjualan $targets_penjualan)
    {
        $data = $this->validateData($request);

        $targets_penjualan->update($data);

        return redirect()
            ->route('targets-penjualan.index')
            ->with('success', 'Target penjualan diperbarui.');
    }

    /**
     * DELETE (soft delete)
     */
    public function destroy(TargetPenjualan $targets_penjualan)
    {
        $targets_penjualan->delete();

        return redirect()
            ->route('targets-penjualan.index')
            ->with('success', 'Target penjualan dihapus.');
    }

    /**
     * Validasi terpusat
     */
    protected function validateData(Request $request): array
    {
        return $request->validate([
            'agent_id'       => ['required', Rule::exists('users', 'id')],
            'product_id'     => ['nullable', Rule::exists('products', 'id')],
            'period'         => ['required', Rule::in(['monthly', 'quarterly', 'annual'])],
            'start_date'     => ['required', 'date'],
            'end_date'       => ['required', 'date', 'after_or_equal:start_date'],
            'target_premium' => ['required', 'numeric', 'min:0'],
            'target_case'    => ['required', 'integer', 'min:0'],
            'title'          => ['nullable', 'string', 'max:120'],
            'notes'          => ['nullable', 'string', 'max:2000'],
            'is_active'      => ['sometimes', 'boolean'],
        ]);
    }
}
