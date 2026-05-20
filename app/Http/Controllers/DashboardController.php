<?php

namespace App\Http\Controllers;

use App\Models\Ncr;
use App\Models\User;
use App\Models\Project;
use Carbon\Carbon;
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

        $baseQuery = $this->baseNcrQuery($authUser, $isAdmin);

        // Statistik utama total keseluruhan
        $totalNcr = (clone $baseQuery)->count();
        $totalOpen = (clone $baseQuery)->where('keterangan', 'open')->count();
        $totalProses = (clone $baseQuery)->where('keterangan', 'process')->count();
        $totalClose = (clone $baseQuery)->whereIn('keterangan', ['close', 'closed'])->count();

        // Default: 12 bulan terakhir dari bulan ini
        $defaultFrom = now()->subMonths(11)->format('Y-m');
        $defaultTo = now()->format('Y-m');

        $from = request('from', $defaultFrom);
        $to = request('to', $defaultTo);

        $startDate = Carbon::createFromFormat('Y-m', $from)->startOfMonth();
        $endDate = Carbon::createFromFormat('Y-m', $to)->endOfMonth();

        if ($startDate->greaterThan($endDate)) {
            [$startDate, $endDate] = [$endDate, $startDate];
            [$from, $to] = [$to, $from];
        }

        // Dropdown bulan-tahun, dibuat 24 bulan terakhir agar lengkap meski tidak ada NCR
        $availableMonths = collect();
        $monthCursor = now()->subMonths(23)->startOfMonth();

        while ($monthCursor->lte(now()->startOfMonth())) {
            $availableMonths->push([
                'value' => $monthCursor->format('Y-m'),
                'label' => $monthCursor->translatedFormat('F Y'),
            ]);

            $monthCursor->addMonth();
        }

        // Query khusus filter chart
        $filteredQuery = (clone $baseQuery)
            ->whereBetween('tgl_masuk', [$startDate, $endDate]);

        $filteredTotalNcr = (clone $filteredQuery)->count();
        $filteredTotalOpen = (clone $filteredQuery)->where('keterangan', 'open')->count();
        $filteredTotalProses = (clone $filteredQuery)->where('keterangan', 'process')->count();
        $filteredTotalClose = (clone $filteredQuery)
            ->whereIn('keterangan', ['close', 'closed'])
            ->count();

        // Bar chart sesuai range filter
        $bulanLabel = [];
        $dataOpen = [];
        $dataProses = [];
        $dataClose = [];

        $current = $startDate->copy();

        while ($current->lte($endDate)) {
            $bulanLabel[] = $current->translatedFormat('M Y');

            $dataOpen[] = (clone $baseQuery)
                ->where('keterangan', 'open')
                ->whereYear('tgl_masuk', $current->year)
                ->whereMonth('tgl_masuk', $current->month)
                ->count();

            $dataProses[] = (clone $baseQuery)
                ->where('keterangan', 'process')
                ->whereYear('tgl_masuk', $current->year)
                ->whereMonth('tgl_masuk', $current->month)
                ->count();

            $dataClose[] = (clone $baseQuery)
                ->whereIn('keterangan', ['close', 'closed'])
                ->whereYear('tgl_masuk', $current->year)
                ->whereMonth('tgl_masuk', $current->month)
                ->count();

            $current->addMonth();
        }

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

        $totalProject = $isAdmin ? Project::count() : null;
        $totalUser = $isAdmin ? User::where('keterangan', true)->count() : null;

        return view('dashboard', compact(
            'totalNcr',
            'totalOpen',
            'totalProses',
            'totalClose',
            'filteredTotalNcr',
            'filteredTotalOpen',
            'filteredTotalProses',
            'filteredTotalClose',
            'bulanLabel',
            'dataOpen',
            'dataProses',
            'dataClose',
            'availableMonths',
            'defaultFrom',
            'defaultTo',
            'from',
            'to',
            'ncrMendekatiTarget',
            'totalProject',
            'totalUser',
            'isAdmin',
            'level'
        ));
    }

    private function baseNcrQuery(User $authUser, bool $isAdmin)
    {
        $query = Ncr::query();

        if (!$isAdmin && in_array($authUser->level, ['user', 'manager'])) {
            $unitKerjaIds = $authUser->unitKerja()->pluck('unit_kerja.id')->toArray();
            $unitKerjaNames = $authUser->unitKerja()->pluck('nama_unit')->toArray();

            $query->where(function ($q) use ($authUser, $unitKerjaIds, $unitKerjaNames) {
                $q->where('user_id', $authUser->id);

                $q->orWhere('penanggung_jawab', $authUser->id);

                if (!empty($unitKerjaIds)) {
                    $q->orWhereHas('user.unitKerja', function ($uq) use ($unitKerjaIds) {
                        $uq->whereIn('unit_kerja.id', $unitKerjaIds);
                    });

                    $q->orWhereIn('unit_kerja_id', $unitKerjaIds);
                }

                if (!empty($unitKerjaNames)) {
                    $q->orWhereIn('unit_tujuan', $unitKerjaNames);
                }
            });
        }

        return $query;
    }
}
