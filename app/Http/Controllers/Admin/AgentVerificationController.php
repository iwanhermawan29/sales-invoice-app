<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AgentVerificationController extends Controller
{
    /**
     * List verifikasi agent (admin).
     * Filter:
     *  - status: pending|approved|rejected|all (default: pending)
     *  - q: cari nama/email/agency
     */
    public function index(Request $request)
    {
        // pastikan string biasa (bukan Stringable)
        $status = (string) $request->input('status', 'pending');
        $q      = trim((string) $request->input('q', ''));

        $statusMap = [
            'pending'  => User::PROFILE_PENDING,
            'approved' => User::PROFILE_APPROVED,
            'rejected' => User::PROFILE_REJECTED,
            'all'      => 'all',
        ];

        // fallback bila nilai status tidak valid
        if (!array_key_exists($status, $statusMap)) {
            $status = 'pending';
        }

        $users = User::query()
            ->when($status !== 'all', fn($qr) => $qr->where('profile_status', $statusMap[$status]))
            ->when($q !== '', function ($qr) use ($q) {
                $qr->where(function ($w) use ($q) {
                    $w->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('agency_name', 'like', "%{$q}%");
                });
            })
            ->orderBy('profile_status')  // pending duluan
            ->orderByDesc('id')          // terbaru di atas
            ->paginate(15)
            ->withQueryString();

        return view('admin.agents.index', [
            'users'  => $users,
            'status' => $status,
            'q'      => $q,
        ]);
    }

    /**
     * Admin menyetujui profil agent:
     *  - Set profile_status = APPROVED
     *  - Set kode_agent = 5 digit berurutan (00001, 00002, ...)
     *  - Catat approved_by/approved_at dan optional approval_note
     */
    public function approve(Request $request, User $user)
    {
        abort_if($user->profile_status !== User::PROFILE_PENDING, 400, 'Hanya profil pending yang dapat disetujui.');

        DB::transaction(function () use ($request, $user) {
            // Kunci baris users saat menghitung nomor berikutnya (hindari bentrok)
            $max = DB::table('users')
                ->whereNotNull('kode_agent')
                ->lockForUpdate()
                ->max(DB::raw('CAST(kode_agent AS UNSIGNED)'));

            $nextNumber = ((int) $max) + 1; // jika $max null -> jadi 1
            $newCode = str_pad((string) $nextNumber, 5, '0', STR_PAD_LEFT); // 00001

            $user->update([
                'profile_status'        => User::PROFILE_APPROVED,
                'kode_agent'            => $newCode,
                'profile_approved_by'   => $request->user()->id,
                'profile_approved_at'   => now(),
                'profile_approval_note' => $request->string('approval_note') ?: null,
            ]);
        });

        return back()->with('status', "Profil disetujui. Kode agent: {$user->kode_agent}");
    }

    /**
     * Admin menolak profil agent (opsional dengan catatan).
     */
    public function reject(Request $request, User $user)
    {
        abort_if($user->profile_status !== User::PROFILE_PENDING, 400, 'Hanya profil pending yang dapat ditolak.');

        $data = $request->validate([
            'approval_note' => ['nullable', 'string', 'max:255'],
        ]);

        $user->update([
            'profile_status'        => User::PROFILE_REJECTED,
            'profile_approved_by'   => $request->user()->id,
            'profile_approved_at'   => now(),
            'profile_approval_note' => $data['approval_note'] ?? null,
        ]);

        return back()->with('status', 'Profil ditolak.');
    }
}
