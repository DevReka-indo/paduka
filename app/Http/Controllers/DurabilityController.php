<?php

namespace App\Http\Controllers;

use App\Models\Durability;
use App\Models\DurabilityProduk;
use App\Models\DurabilityTrainset;
use App\Models\DurabilityLokasi;
use App\Models\DurabilityKomponen;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DurabilityController extends Controller
{
    public function index(Request $request)
    {
        $selectedTahun = $request->tahun;
        $selectedProduk = $request->produk_id;
        $selectedTrainsetProduk = $request->trainset_produk_id;

        $trendFrom = $request->trend_from;
        $trendTo = $request->trend_to;

        $baseQuery = Durability::query()
            ->with([
                'proyek',
                'komponen.produk',
                'trainset',
                'lokasi',
            ]);

        if ($selectedTahun) {
            $baseQuery->where('tahun', $selectedTahun);
        }

        if ($selectedProduk) {
            $baseQuery->whereHas('komponen', function ($query) use ($selectedProduk) {
                $query->where('produk_id', $selectedProduk);
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Summary Cards
        |--------------------------------------------------------------------------
        */

        $totalPenggantian = (clone $baseQuery)
            ->sum('jumlah_penggantian');

        $komponenDurabilityTertinggi = (clone $baseQuery)
            ->join('durability_komponen', 'durability.komponen_id', '=', 'durability_komponen.id')
            ->select(
                'durability_komponen.nama_komponen',
                DB::raw('AVG(durability.rentang_penggantian) as rata_rentang')
            )
            ->whereNotNull('durability.rentang_penggantian')
            ->groupBy('durability_komponen.id', 'durability_komponen.nama_komponen')
            ->orderByDesc('rata_rentang')
            ->first();

        $komponenDurabilityTerendah = (clone $baseQuery)
            ->join('durability_komponen', 'durability.komponen_id', '=', 'durability_komponen.id')
            ->select(
                'durability_komponen.nama_komponen',
                DB::raw('AVG(durability.rentang_penggantian) as rata_rentang')
            )
            ->whereNotNull('durability.rentang_penggantian')
            ->groupBy('durability_komponen.id', 'durability_komponen.nama_komponen')
            ->orderBy('rata_rentang')
            ->first();

        $komponenPenggantianTerbanyak = (clone $baseQuery)
            ->join('durability_komponen', 'durability.komponen_id', '=', 'durability_komponen.id')
            ->select(
                'durability_komponen.nama_komponen',
                DB::raw('SUM(durability.jumlah_penggantian) as total_penggantian')
            )
            ->whereNotNull('durability.jumlah_penggantian')
            ->groupBy('durability_komponen.id', 'durability_komponen.nama_komponen')
            ->orderByDesc('total_penggantian')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Available Month Filter for Trend Chart
        |--------------------------------------------------------------------------
        */

        $availableTrendMonths = (clone $baseQuery)
            ->select(DB::raw("DATE_FORMAT(tgl_terbit_lppb, '%Y-%m') as bulan"))
            ->whereNotNull('tgl_terbit_lppb')
            ->distinct()
            ->orderBy('bulan')
            ->pluck('bulan')
            ->map(function ($bulan) {
                return [
                    'value' => $bulan,
                    'label' => Carbon::createFromFormat('Y-m', $bulan)->translatedFormat('M Y'),
                ];
            });

        /*
        |--------------------------------------------------------------------------
        | Chart: Trend Penggantian Per Bulan
        |--------------------------------------------------------------------------
        */

        $trendQuery = (clone $baseQuery)
            ->select(
                DB::raw("DATE_FORMAT(tgl_terbit_lppb, '%Y-%m') as bulan"),
                DB::raw('SUM(jumlah_penggantian) as total_penggantian')
            )
            ->whereNotNull('tgl_terbit_lppb')
            ->whereNotNull('jumlah_penggantian');

        if ($trendFrom) {
            $trendQuery->whereDate('tgl_terbit_lppb', '>=', $trendFrom . '-01');
        }

        if ($trendTo) {
            $trendQuery->whereDate(
                'tgl_terbit_lppb',
                '<=',
                Carbon::createFromFormat('Y-m', $trendTo)->endOfMonth()->format('Y-m-d')
            );
        }

        $trendPenggantian = $trendQuery
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $trendLabels = $trendPenggantian
            ->pluck('bulan')
            ->map(function ($bulan) {
                return Carbon::createFromFormat('Y-m', $bulan)->translatedFormat('M Y');
            })
            ->values();

        $trendValues = $trendPenggantian
            ->pluck('total_penggantian')
            ->map(fn ($value) => (int) $value)
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Chart: Top 10 Komponen Penggantian Terbanyak
        |--------------------------------------------------------------------------
        */

        $topKomponenPenggantian = (clone $baseQuery)
            ->join('durability_komponen', 'durability.komponen_id', '=', 'durability_komponen.id')
            ->select(
                'durability_komponen.nama_komponen',
                DB::raw('SUM(durability.jumlah_penggantian) as total_penggantian')
            )
            ->whereNotNull('durability.jumlah_penggantian')
            ->groupBy('durability_komponen.id', 'durability_komponen.nama_komponen')
            ->orderByDesc('total_penggantian')
            ->limit(10)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Chart/Table: Top 10 Trainset Penggantian Terbanyak
        |--------------------------------------------------------------------------
        */

        $topTrainsetQuery = (clone $baseQuery)
            ->join('durability_trainset', 'durability.trainset_id', '=', 'durability_trainset.id')
            ->join('durability_komponen', 'durability.komponen_id', '=', 'durability_komponen.id')
            ->join('durability_produk', 'durability_komponen.produk_id', '=', 'durability_produk.id')
            ->select(
                'durability_trainset.nomor_trainset',
                'durability_trainset.tipe_car',
                DB::raw('MAX(durability_produk.nama_produk) as nama_produk'),
                DB::raw('SUM(durability.jumlah_penggantian) as total_penggantian')
            )
            ->whereNotNull('durability.jumlah_penggantian');

        if ($selectedTrainsetProduk) {
            $topTrainsetQuery->where('durability_produk.id', $selectedTrainsetProduk);
        }

        $topTrainsetPenggantian = $topTrainsetQuery
            ->groupBy(
                'durability_trainset.id',
                'durability_trainset.nomor_trainset',
                'durability_trainset.tipe_car'
            )
            ->orderByDesc('total_penggantian')
            ->limit(10)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Chart: Top 10 Komponen Durability Tertinggi
        |--------------------------------------------------------------------------
        */

        $topKomponenDurability = (clone $baseQuery)
            ->join('durability_komponen', 'durability.komponen_id', '=', 'durability_komponen.id')
            ->select(
                'durability_komponen.nama_komponen',
                DB::raw('AVG(durability.rentang_penggantian) as rata_rentang')
            )
            ->whereNotNull('durability.rentang_penggantian')
            ->groupBy('durability_komponen.id', 'durability_komponen.nama_komponen')
            ->orderByDesc('rata_rentang')
            ->limit(10)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Table Data
        |--------------------------------------------------------------------------
        */

        $durability = (clone $baseQuery)
            ->latest('tgl_terbit_lppb')
            ->paginate(15)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Filter Data
        |--------------------------------------------------------------------------
        */

        $tahunList = Durability::query()
            ->select('tahun')
            ->whereNotNull('tahun')
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun');

        $produkList = DurabilityProduk::query()
            ->orderBy('nama_produk')
            ->get();

        $view = $request->routeIs('durability.tabel-detail')
            ? 'durability.tabel-detail'
            : 'durability.index';

        return view($view, compact(
            'durability',
            'tahunList',
            'produkList',
            'selectedTahun',
            'selectedProduk',
            'selectedTrainsetProduk',
            'trendFrom',
            'trendTo',
            'availableTrendMonths',
            'totalPenggantian',
            'komponenDurabilityTertinggi',
            'komponenDurabilityTerendah',
            'komponenPenggantianTerbanyak',
            'trendLabels',
            'trendValues',
            'topKomponenPenggantian',
            'topTrainsetPenggantian',
            'topKomponenDurability'
        ));
    }

    public function penggantianKomponen(Request $request)
    {
        $dateFrom = $request->date_from;
        $dateTo = $request->date_to;
        $produkId = $request->produk_id;
        $trainsetId = $request->trainset_id;
        $lokasiId = $request->lokasi_id;

        $baseQuery = Durability::query()
            ->with([
                'proyek',
                'komponen.produk',
                'trainset',
                'lokasi',
            ]);

        if ($dateFrom) {
            $baseQuery->whereDate('tgl_terbit_lppb', '>=', $dateFrom);
        }

        if ($dateTo) {
            $baseQuery->whereDate('tgl_terbit_lppb', '<=', $dateTo);
        }

        if ($produkId) {
            $baseQuery->whereHas('komponen', function ($query) use ($produkId) {
                $query->where('produk_id', $produkId);
            });
        }

        if ($trainsetId) {
            $baseQuery->where('trainset_id', $trainsetId);
        }

        if ($lokasiId) {
            $baseQuery->where('lokasi_id', $lokasiId);
        }

        /*
        |--------------------------------------------------------------------------
        | Chart Total Penggantian Setiap Komponen + Pagination Chart
        |--------------------------------------------------------------------------
        */

        $chartPerPage = 40;
        $chartPage = max((int) $request->get('chart_page', 1), 1);

        $komponenPenggantianQuery = (clone $baseQuery)
            ->join('durability_komponen', 'durability.komponen_id', '=', 'durability_komponen.id')
            ->select(
                'durability_komponen.id',
                'durability_komponen.nama_komponen',
                DB::raw('SUM(durability.jumlah_penggantian) as total_penggantian'),
                DB::raw('COUNT(durability.id) as total_record'),
                DB::raw('AVG(durability.rentang_penggantian) as rata_rentang')
            )
            ->whereNotNull('durability.jumlah_penggantian')
            ->groupBy('durability_komponen.id', 'durability_komponen.nama_komponen')
            ->orderByDesc('total_penggantian');

        $chartTotalItems = (clone $komponenPenggantianQuery)->get()->count();
        $chartTotalPages = max((int) ceil($chartTotalItems / $chartPerPage), 1);

        if ($chartPage > $chartTotalPages) {
            $chartPage = $chartTotalPages;
        }

        $komponenPenggantian = $komponenPenggantianQuery
            ->offset(($chartPage - 1) * $chartPerPage)
            ->limit($chartPerPage)
            ->get();

        $chartFromItem = $chartTotalItems > 0
            ? (($chartPage - 1) * $chartPerPage) + 1
            : 0;

        $chartToItem = min($chartPage * $chartPerPage, $chartTotalItems);

        $chartLabelsFull = $komponenPenggantian
            ->pluck('nama_komponen')
            ->values();

        $chartLabels = $chartLabelsFull
            ->map(function ($label) {
                return strlen($label) > 22 ? substr($label, 0, 22) . '...' : $label;
            })
            ->values();

        $chartValues = $komponenPenggantian
            ->pluck('total_penggantian')
            ->map(fn ($value) => (int) $value)
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Table Detail
        |--------------------------------------------------------------------------
        */

        $detailKomponen = (clone $baseQuery)
            ->join('durability_komponen', 'durability.komponen_id', '=', 'durability_komponen.id')
            ->join('durability_produk', 'durability_komponen.produk_id', '=', 'durability_produk.id')
            ->select(
                'durability_komponen.id',
                'durability_komponen.nama_komponen',
                'durability_produk.nama_produk',
                DB::raw('SUM(durability.jumlah_penggantian) as total_penggantian'),
                DB::raw('COUNT(durability.id) as total_record'),
                DB::raw('AVG(durability.rentang_penggantian) as rata_rentang'),
                DB::raw('MIN(durability.tgl_terbit_lppb) as tanggal_awal'),
                DB::raw('MAX(durability.tgl_terbit_lppb) as tanggal_akhir')
            )
            ->whereNotNull('durability.jumlah_penggantian')
            ->groupBy(
                'durability_komponen.id',
                'durability_komponen.nama_komponen',
                'durability_produk.nama_produk'
            )
            ->orderByDesc('total_penggantian')
            ->paginate(15)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Filter Options
        |--------------------------------------------------------------------------
        */

        $produkList = DurabilityProduk::query()
            ->orderBy('nama_produk')
            ->get();

        $trainsetList = DurabilityTrainset::query()
            ->orderBy('nomor_trainset')
            ->orderBy('tipe_car')
            ->get();

        $lokasiList = DurabilityLokasi::query()
            ->orderBy('nama_lokasi')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Periode Label
        |--------------------------------------------------------------------------
        */

        $periodeLabel = null;

        if ($dateFrom && $dateTo) {
            $periodeLabel = Carbon::parse($dateFrom)->translatedFormat('d M Y')
                . ' - '
                . Carbon::parse($dateTo)->translatedFormat('d M Y');
        } elseif ($dateFrom) {
            $periodeLabel = 'Mulai ' . Carbon::parse($dateFrom)->translatedFormat('d M Y');
        } elseif ($dateTo) {
            $periodeLabel = 'Sampai ' . Carbon::parse($dateTo)->translatedFormat('d M Y');
        }

        return view('durability.penggantian-komponen', compact(
            'dateFrom',
            'dateTo',
            'produkId',
            'trainsetId',
            'lokasiId',
            'produkList',
            'trainsetList',
            'lokasiList',
            'periodeLabel',
            'komponenPenggantian',
            'chartLabels',
            'chartLabelsFull',
            'chartValues',
            'chartPage',
            'chartPerPage',
            'chartTotalItems',
            'chartTotalPages',
            'chartFromItem',
            'chartToItem',
            'detailKomponen'
        ));
    }

    public function durabilityKomponen(Request $request)
    {
        $dateFrom = $request->date_from;
        $dateTo = $request->date_to;
        $produkId = $request->produk_id;
        $trainsetId = $request->trainset_id;
        $lokasiId = $request->lokasi_id;

        $baseQuery = Durability::query()
            ->with([
                'proyek',
                'komponen.produk',
                'trainset',
                'lokasi',
            ]);

        if ($dateFrom) {
            $baseQuery->whereDate('tgl_terbit_lppb', '>=', $dateFrom);
        }

        if ($dateTo) {
            $baseQuery->whereDate('tgl_terbit_lppb', '<=', $dateTo);
        }

        if ($produkId) {
            $baseQuery->whereHas('komponen', function ($query) use ($produkId) {
                $query->where('produk_id', $produkId);
            });
        }

        if ($trainsetId) {
            $baseQuery->where('trainset_id', $trainsetId);
        }

        if ($lokasiId) {
            $baseQuery->where('lokasi_id', $lokasiId);
        }

        /*
        |--------------------------------------------------------------------------
        | Chart: Top 10 Rata-rata Durability Komponen
        |--------------------------------------------------------------------------
        */

        $durabilityKomponen = (clone $baseQuery)
            ->join('durability_komponen', 'durability.komponen_id', '=', 'durability_komponen.id')
            ->join('durability_produk', 'durability_komponen.produk_id', '=', 'durability_produk.id')
            ->select(
                'durability_komponen.id',
                'durability_komponen.nama_komponen',
                DB::raw('MAX(durability_produk.nama_produk) as nama_produk'),
                DB::raw('AVG(durability.rentang_penggantian) as rata_durability'),
                DB::raw('SUM(durability.jumlah_penggantian) as total_penggantian'),
                DB::raw('COUNT(durability.id) as total_record')
            )
            ->whereNotNull('durability.rentang_penggantian')
            ->groupBy(
                'durability_komponen.id',
                'durability_komponen.nama_komponen'
            )
            ->orderByDesc('rata_durability')
            ->limit(10)
            ->get();

        $chartLabelsFull = $durabilityKomponen
            ->pluck('nama_komponen')
            ->values();

        $chartLabels = $chartLabelsFull
            ->map(function ($label) {
                return strlen($label) > 36 ? substr($label, 0, 36) . '...' : $label;
            })
            ->values();

        $chartValues = $durabilityKomponen
            ->pluck('rata_durability')
            ->map(fn ($value) => round((float) $value))
            ->values();

        $chartColors = $chartValues
            ->map(function ($value) {
                if ($value > 90) {
                    return 'rgba(16, 185, 129, 0.95)';
                }

                if ($value >= 31) {
                    return 'rgba(245, 158, 11, 0.95)';
                }

                return 'rgba(239, 68, 68, 0.95)';
            })
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Filter Options
        |--------------------------------------------------------------------------
        */

        $produkList = DurabilityProduk::query()
            ->orderBy('nama_produk')
            ->get();

        $trainsetList = DurabilityTrainset::query()
            ->orderBy('nomor_trainset')
            ->orderBy('tipe_car')
            ->get();

        $lokasiList = DurabilityLokasi::query()
            ->orderBy('nama_lokasi')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Periode Label
        |--------------------------------------------------------------------------
        */

        $periodeLabel = null;

        if ($dateFrom && $dateTo) {
            $periodeLabel = Carbon::parse($dateFrom)->translatedFormat('d M Y')
                . ' - '
                . Carbon::parse($dateTo)->translatedFormat('d M Y');
        } elseif ($dateFrom) {
            $periodeLabel = 'Mulai ' . Carbon::parse($dateFrom)->translatedFormat('d M Y');
        } elseif ($dateTo) {
            $periodeLabel = 'Sampai ' . Carbon::parse($dateTo)->translatedFormat('d M Y');
        }

        return view('durability.durability-komponen', compact(
            'dateFrom',
            'dateTo',
            'produkId',
            'trainsetId',
            'lokasiId',
            'produkList',
            'trainsetList',
            'lokasiList',
            'periodeLabel',
            'durabilityKomponen',
            'chartLabels',
            'chartLabelsFull',
            'chartValues',
            'chartColors'
        ));
    }

    public function lokasi(Request $request)
    {
        $dateFrom = $request->date_from;
        $dateTo = $request->date_to;

        $produkId = $request->produk_id;
        $komponenId = $request->komponen_id;
        $lokasiId = $request->lokasi_id;
        $sort = $request->sort ?? 'desc';

        $baseQuery = Durability::query()
            ->with([
                'proyek',
                'komponen.produk',
                'trainset',
                'lokasi',
            ]);

        if ($dateFrom) {
            $baseQuery->whereDate('tgl_terbit_lppb', '>=', $dateFrom);
        }

        if ($dateTo) {
            $baseQuery->whereDate('tgl_terbit_lppb', '<=', $dateTo);
        }

        /*
        |--------------------------------------------------------------------------
        | Top 5 Lokasi
        |--------------------------------------------------------------------------
        */

        $topLokasi = (clone $baseQuery)
            ->join('durability_lokasi', 'durability.lokasi_id', '=', 'durability_lokasi.id')
            ->join('durability_komponen', 'durability.komponen_id', '=', 'durability_komponen.id')
            ->join('durability_produk', 'durability_komponen.produk_id', '=', 'durability_produk.id')
            ->select(
                'durability_lokasi.id',
                'durability_lokasi.nama_lokasi',
                DB::raw('SUM(durability.jumlah_penggantian) as total_penggantian'),
                DB::raw('COUNT(DISTINCT durability_produk.id) as total_produk')
            )
            ->whereNotNull('durability.jumlah_penggantian')
            ->groupBy('durability_lokasi.id', 'durability_lokasi.nama_lokasi')
            ->orderByDesc('total_penggantian')
            ->limit(5)
            ->get();

        $maxTopLokasiValue = max((int) ($topLokasi->max('total_penggantian') ?? 0), 1);

        /*
        |--------------------------------------------------------------------------
        | Tabel Lokasi
        |--------------------------------------------------------------------------
        */

        $lokasiSummary = (clone $baseQuery)
            ->join('durability_lokasi', 'durability.lokasi_id', '=', 'durability_lokasi.id')
            ->join('durability_komponen', 'durability.komponen_id', '=', 'durability_komponen.id')
            ->join('durability_produk', 'durability_komponen.produk_id', '=', 'durability_produk.id')
            ->select(
                'durability_lokasi.id',
                'durability_lokasi.nama_lokasi',
                DB::raw('SUM(durability.jumlah_penggantian) as total_penggantian'),
                DB::raw('COUNT(DISTINCT durability_produk.id) as total_produk')
            )
            ->whereNotNull('durability.jumlah_penggantian')
            ->groupBy('durability_lokasi.id', 'durability_lokasi.nama_lokasi')
            ->orderByDesc('total_penggantian')
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Chart Data Trainset Dengan Penggantian
        |--------------------------------------------------------------------------
        */

        $trainsetQuery = (clone $baseQuery)
            ->join('durability_trainset', 'durability.trainset_id', '=', 'durability_trainset.id')
            ->join('durability_lokasi', 'durability.lokasi_id', '=', 'durability_lokasi.id')
            ->join('durability_komponen', 'durability.komponen_id', '=', 'durability_komponen.id')
            ->join('durability_produk', 'durability_komponen.produk_id', '=', 'durability_produk.id')
            ->select(
                'durability_trainset.id',
                'durability_trainset.nomor_trainset',
                'durability_trainset.tipe_car',
                DB::raw('SUM(durability.jumlah_penggantian) as total_penggantian')
            )
            ->whereNotNull('durability.jumlah_penggantian');

        if ($produkId) {
            $trainsetQuery->where('durability_produk.id', $produkId);
        }

        if ($komponenId) {
            $trainsetQuery->where('durability_komponen.id', $komponenId);
        }

        if ($lokasiId) {
            $trainsetQuery->where('durability_lokasi.id', $lokasiId);
        }

        $trainsetPenggantian = $trainsetQuery
            ->groupBy(
                'durability_trainset.id',
                'durability_trainset.nomor_trainset',
                'durability_trainset.tipe_car'
            )
            ->when($sort === 'asc', function ($query) {
                $query->orderBy('total_penggantian');
            }, function ($query) {
                $query->orderByDesc('total_penggantian');
            })
            ->limit(10)
            ->get();

        $chartTrainsetLabels = $trainsetPenggantian
            ->map(function ($item) {
                $trainset = $item->nomor_trainset ? 'TS-' . $item->nomor_trainset : '-';

                if ($item->tipe_car) {
                    return $trainset . ' / ' . $item->tipe_car;
                }

                return $trainset;
            })
            ->values();

        $chartTrainsetValues = $trainsetPenggantian
            ->pluck('total_penggantian')
            ->map(fn ($value) => (int) $value)
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Filter Options
        |--------------------------------------------------------------------------
        */

        $produkList = DurabilityProduk::query()
            ->orderBy('nama_produk')
            ->get();

        $komponenList = DurabilityKomponen::query()
            ->orderBy('nama_komponen')
            ->get();

        $lokasiList = DurabilityLokasi::query()
            ->orderBy('nama_lokasi')
            ->get();

        $periodeLabel = null;

        if ($dateFrom && $dateTo) {
            $periodeLabel = Carbon::parse($dateFrom)->translatedFormat('d M Y')
                . ' - '
                . Carbon::parse($dateTo)->translatedFormat('d M Y');
        } elseif ($dateFrom) {
            $periodeLabel = 'Mulai ' . Carbon::parse($dateFrom)->translatedFormat('d M Y');
        } elseif ($dateTo) {
            $periodeLabel = 'Sampai ' . Carbon::parse($dateTo)->translatedFormat('d M Y');
        }

        return view('durability.lokasi', compact(
            'dateFrom',
            'dateTo',
            'produkId',
            'komponenId',
            'lokasiId',
            'sort',
            'periodeLabel',
            'topLokasi',
            'maxTopLokasiValue',
            'lokasiSummary',
            'produkList',
            'komponenList',
            'lokasiList',
            'chartTrainsetLabels',
            'chartTrainsetValues'
        ));
    }


}
