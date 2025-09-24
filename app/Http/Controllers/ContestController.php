<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContestRequest;
use App\Http\Requests\UpdateContestRequest;
use App\Models\Contest;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContestController extends Controller
{
    // LIST + FILTER
    public function index(Request $request)
    {
        $q        = trim((string) $request->input('q', ''));
        $periode  = (string) $request->input('periode', ''); // monthly|quarterly|annual|'' (semua)
        $start    = $request->date('start');                  // YYYY-MM-DD (opsional)
        $end      = $request->date('end');                    // YYYY-MM-DD (opsional)

        $contests = Contest::query()
            ->when($q !== '', fn($qr) =>
            $qr->where('nama_kontes', 'like', "%{$q}%"))
            ->when($periode !== '', fn($qr) =>
            $qr->where('periode', $periode))
            // overlap: mulai <= end AND (selesai IS NULL OR selesai >= start)
            ->when($start && $end, function ($qr) use ($start, $end) {
                $qr->whereDate('tanggal_mulai', '<=', $end)
                    ->where(function ($w) use ($start) {
                        $w->whereNull('tanggal_selesai')
                            ->orWhereDate('tanggal_selesai', '>=', $start);
                    });
            })
            ->latest('id')
            ->paginate(12)
            ->withQueryString();

        return view('contests.index', compact('contests', 'q', 'periode', 'start', 'end'));
    }

    public function agentIndex(Request $request)
    {
        $q        = trim((string) $request->input('q', ''));
        $periode  = (string) $request->input('periode', ''); // monthly|quarterly|annual|''

        $contests = Contest::query()
            ->when($q !== '', fn($qr) => $qr->where('nama_kontes', 'like', "%{$q}%"))
            ->when($periode !== '', fn($qr) => $qr->where('periode', $periode))
            ->latest('id')
            ->paginate(6)
            ->withQueryString();

        // Hitung progress per kontes untuk agent yang login
        $agentId  = $request->user()->id;
        $progress = []; // [contest_id => ['premi','cases','premi_pct','case_pct']]

        foreach ($contests as $c) {
            // tentukan rentang tanggal: jika null, anggap open-ended
            $start = $c->tanggal_mulai ?: '1900-01-01';
            $end   = $c->tanggal_selesai ?: '2099-12-31';

            $agg = Sale::query()
                ->where('user_id', $agentId)
                ->where('status', \App\Models\Sale::STATUS_APPROVED)
                ->whereBetween('sale_date', [$start, $end])
                ->selectRaw('COUNT(*) as cases, COALESCE(SUM(premium),0) as premium')
                ->first();

            $premi      = (float) ($agg->premium ?? 0);
            $cases      = (int)   ($agg->cases   ?? 0);
            $premiPct   = ($c->target_premi > 0) ? min(100, round(($premi / $c->target_premi) * 100, 1)) : null;
            $casePct    = ($c->target_case  > 0) ? min(100, round(($cases / $c->target_case)   * 100, 1)) : null;

            $progress[$c->id] = [
                'premi'     => $premi,
                'cases'     => $cases,
                'premi_pct' => $premiPct,
                'case_pct'  => $casePct,
            ];
        }

        // eager untuk relasi media jika ada (opsional)
        $contests->loadMissing(['photos', 'logos']);

        return view('contests.agent', [
            'contests' => $contests,
            'q'        => $q,
            'periode'  => $periode,
            'progress' => $progress, // <— kirim ke blade
        ]);
    }

    // CREATE
    public function create()
    {
        return view('contests.create');
    }

    // STORE
    public function store(StoreContestRequest $request)
    {
        $data = $request->validated();

        // handle flyer (opsional)
        if ($request->hasFile('flyer')) {
            $path = $request->file('flyer')->store('contests', 'public');
            $data['flyer_path'] = $path;
            $data['flyer_mime'] = $request->file('flyer')->getMimeType();
        }

        $contest = Contest::create($data);

        return redirect()
            ->route('contests.index')
            ->with('status', 'Kontes berhasil dibuat.');
    }

    // SHOW (opsional)
    public function show(Contest $contest)
    {
        return view('contests.show', compact('contest'));
    }

    // EDIT
    public function edit(Contest $contest)
    {
        return view('contests.edit', compact('contest'));
    }

    // UPDATE
    public function update(UpdateContestRequest $request, Contest $contest)
    {
        $data = $request->validated();

        if ($request->hasFile('flyer')) {
            // hapus yang lama (jika ada)
            if ($contest->flyer_path && Storage::disk('public')->exists($contest->flyer_path)) {
                Storage::disk('public')->delete($contest->flyer_path);
            }
            $path = $request->file('flyer')->store('contests', 'public');
            $data['flyer_path'] = $path;
            $data['flyer_mime'] = $request->file('flyer')->getMimeType();
        }

        $contest->update($data);

        return redirect()
            ->route('contests.index')
            ->with('status', 'Kontes berhasil diperbarui.');
    }

    // DESTROY
    public function destroy(Contest $contest)
    {
        if ($contest->flyer_path && Storage::disk('public')->exists($contest->flyer_path)) {
            Storage::disk('public')->delete($contest->flyer_path);
        }
        $contest->delete();

        return redirect()
            ->route('contests.index')
            ->with('status', 'Kontes berhasil dihapus.');
    }
}
