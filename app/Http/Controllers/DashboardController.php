<?php

namespace App\Http\Controllers;

use App\Models\Ncr;
use App\Models\User;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        /** @var User $authUser */
        $authUser = Auth::user();
        $level = strtolower($authUser->level ?? '');
        $isAdmin = in_array($level, ['admin', 'superadmin']);

        // ── Query dasar berdasarkan role ──────────────────────────
        $baseQuery = $this->baseNcrQuery($authUser, $isAdmin);

        // ── Statistik utama ───────────────────────────────────────
        $totalNcr = (clone $baseQuery)->count();
        $totalOpen = (clone $baseQuery)->where('keterangan', 'open')->count();
        $totalProses = (clone $baseQuery)->where('keterangan', 'process')->count();
        $totalClose = (clone $baseQuery)->whereIn('keterangan', ['close', 'closed'])->count();

        // ── Grafik 12 bulan terakhir ──────────────────────────────
        $bulanLabel = [];
        $dataOpen = [];
        $dataProses = [];
        $dataClose = [];

        for ($i = 11; $i >= 0; $i--) {
            $bulan = Carbon::now()->subMonths($i);
            $bulanLabel[] = $bulan->translatedFormat('M Y');

            $dataOpen[] = (clone $baseQuery)->where('keterangan', 'open')->whereYear('tgl_masuk', $bulan->year)->whereMonth('tgl_masuk', $bulan->month)->count();

            $dataProses[] = (clone $baseQuery)->where('keterangan', 'process')->whereYear('tgl_masuk', $bulan->year)->whereMonth('tgl_masuk', $bulan->month)->count();

            $dataClose[] = (clone $baseQuery)
                ->whereIn('keterangan', ['close', 'closed'])
                ->whereYear('tgl_masuk', $bulan->year)
                ->whereMonth('tgl_masuk', $bulan->month)
                ->count();
        }

        // ── NCR mendekati target ──────────────────────────────────
        $ncrMendekatiTarget = (clone $baseQuery)
            ->with(['project', 'penanggungJawab'])
            ->whereNotIn('keterangan', ['close', 'closed'])
            ->whereNotNull('tgl_target')
            ->where('tgl_target', '<=', Carbon::now()->addDays(7))
            ->orderBy('tgl_target', 'asc')
            ->limit(10)
            ->get()
            ->map(function ($ncr) {
                $ncr->sisa_hari = Carbon::now()
                    ->startOfDay()
                    ->diffInDays(Carbon::parse($ncr->tgl_target)->startOfDay(), false);
                return $ncr;
            });

        // ── Info tambahan (hanya admin/superadmin) ────────────────
        $totalProject = $isAdmin ? Project::count() : null;
        $totalUser = $isAdmin ? User::where('keterangan', true)->count() : null;

        return view('dashboard', compact('totalNcr', 'totalOpen', 'totalProses', 'totalClose', 'bulanLabel', 'dataOpen', 'dataProses', 'dataClose', 'ncrMendekatiTarget', 'totalProject', 'totalUser', 'isAdmin', 'level'));
    }

    /**
     * Query dasar NCR sesuai role — sama persis dengan logika di NCRController::index()
     */
    private function baseNcrQuery(User $authUser, bool $isAdmin)
    {
        $query = Ncr::query();

        if (!$isAdmin && in_array($authUser->level, ['user', 'manager'])) {
            $unitKerjaIds = $authUser->unitKerja()->pluck('unit_kerja.id')->toArray();
            $unitKerjaNames = $authUser->unitKerja()->pluck('nama_unit')->toArray();

            $query->where(function ($q) use ($authUser, $unitKerjaIds, $unitKerjaNames) {
                // 1. NCR dibuat sendiri
                $q->where('user_id', $authUser->id);

                // 2. NCR sebagai PIC
                $q->orWhere('penanggung_jawab', $authUser->id);

                // 3. NCR dari user yang unit kerjanya sama (INI YANG KURANG)
                if (!empty($unitKerjaIds)) {
                    $q->orWhereHas('user.unitKerja', function ($uq) use ($unitKerjaIds) {
                        $uq->whereIn('unit_kerja.id', $unitKerjaIds);
                    });
                }

                // 4. NCR berdasarkan unit tujuan (relasi)
                if (!empty($unitKerjaIds)) {
                    $q->orWhereIn('unit_kerja_id', $unitKerjaIds);
                }

                // 5. NCR lama (string)
                if (!empty($unitKerjaNames)) {
                    $q->orWhereIn('unit_tujuan', $unitKerjaNames);
                }
            });
        }

        return $query;
    }
}
