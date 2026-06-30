<?php

namespace App\Http\Controllers;

use App\Models\Durability;
use App\Models\DurabilityProduk;
use App\Models\DurabilityProyek;
use App\Models\DurabilityTrainset;
use App\Models\DurabilityLokasi;
use App\Models\DurabilityKomponen;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Imports\DurabilityImport;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;
use Illuminate\Validation\ValidationException;

class DurabilityController extends Controller
{
    public function index(Request $request)
    {
        $selectedTahun = $request->tahun;
        $selectedProduk = $request->produk_id;
        $selectedTrainsetProduk = $request->trainset_produk_id;
        $selectedProyek = $request->proyek_id;

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

        if ($selectedProyek) {
            $baseQuery->whereHas('proyek', function ($query) use ($selectedProyek) {
                $query->where('nama_proyek', $selectedProyek);
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
            ->select(DB::raw("DATE_FORMAT(tgl_kerusakan, '%Y-%m') as bulan"))
            ->whereNotNull('tgl_kerusakan')
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
                DB::raw("DATE_FORMAT(tgl_kerusakan, '%Y-%m') as bulan"),
                DB::raw('SUM(jumlah_penggantian) as total_penggantian')
            )
            ->whereNotNull('tgl_kerusakan')
            ->whereNotNull('jumlah_penggantian');

        if ($trendFrom) {
            $trendQuery->whereDate('tgl_kerusakan', '>=', $trendFrom . '-01');
        }

        if ($trendTo) {
            $trendQuery->whereDate(
                'tgl_kerusakan',
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

        // $proyekList = DurabilityProyek::query()
        //     ->orderBy('nama_proyek')
        //     ->distinct()
        //     ->orderBy('nama_proyek')
        //     ->get();

        $proyekList = DurabilityProyek::query()
            ->selectRaw('DISTINCT TRIM(nama_proyek) as nama_proyek')
            ->orderBy('nama_proyek')
            ->get();

        $view = $request->routeIs('durability.tabel-detail')
            ? 'durability.tabel-detail'
            : 'durability.index';

        return view($view, compact(
            'durability',
            'tahunList',
            'produkList',
            'proyekList',
            'selectedProyek',
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

    public function create()
    {
        $produkList = DurabilityProduk::query()
            ->orderBy('nama_produk')
            ->get();

        $komponenList = DurabilityKomponen::query()
            ->with('produk')
            ->orderBy('nama_komponen')
            ->get();

        $trainsetList = DurabilityTrainset::query()
            ->orderBy('nomor_trainset')
            ->orderBy('tipe_car')
            ->get();

        $lokasiList = DurabilityLokasi::query()
            ->orderBy('nama_lokasi')
            ->get();

        return view('durability.create', compact(
            'produkList',
            'komponenList',
            'trainsetList',
            'lokasiList'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun' => ['required', 'integer', 'min:2000', 'max:2100'],

            'nomor_po' => ['nullable', 'string', 'max:255'],
            'customer' => ['nullable', 'string', 'max:255'],
            'nama_proyek' => ['nullable', 'string', 'max:255'],

            'nama_produk' => ['required', 'string', 'max:255'],
            'nama_komponen' => ['required', 'string', 'max:255'],
            'nomor_trainset' => ['nullable', 'string', 'max:255'],
            'tipe_car' => ['nullable', 'string', 'max:255'],
            'nama_lokasi' => ['nullable', 'string', 'max:255'],

            'tgl_kerusakan' => ['nullable', 'date'],
            'tgl_terbit_lppb' => ['nullable', 'date'],
            'case_keterangan' => ['nullable', 'string'],
            'rentang_penggantian' => ['nullable', 'integer', 'min:0'],
            'jumlah_penggantian' => ['required', 'integer', 'min:0'],
        ]);

        $proyek = $this->resolveDurabilityProyek($request);
        $produk = $this->resolveDurabilityProduk($request);
        $komponen = $this->resolveDurabilityKomponen($request, $produk);
        $trainset = $this->resolveDurabilityTrainset($request);
        $lokasi = $this->resolveDurabilityLokasi($request);

        Durability::create([
            'tahun' => $validated['tahun'],
            'proyek_id' => $proyek->id,
            'komponen_id' => $komponen->id,
            'trainset_id' => $trainset?->id,
            'lokasi_id' => $lokasi?->id,
            'tgl_kerusakan' => $validated['tgl_kerusakan'] ?? null,
            'tgl_terbit_lppb' => $validated['tgl_terbit_lppb'] ?? null,
            'case_keterangan' => $validated['case_keterangan'] ?? null,
            'rentang_penggantian' => $validated['rentang_penggantian'] ?? null,
            'jumlah_penggantian' => $validated['jumlah_penggantian'],
        ]);

        return redirect()
            ->route('durability.tabel-detail')
            ->with('success', 'Data durability berhasil ditambahkan.');
    }

    public function edit(Durability $durability)
    {
        $durability->load([
            'proyek',
            'komponen.produk',
            'trainset',
            'lokasi',
        ]);

        $produkList = DurabilityProduk::query()
            ->orderBy('nama_produk')
            ->get();

        $komponenList = DurabilityKomponen::query()
            ->with('produk')
            ->orderBy('nama_komponen')
            ->get();

        $trainsetList = DurabilityTrainset::query()
            ->orderBy('nomor_trainset')
            ->orderBy('tipe_car')
            ->get();

        $lokasiList = DurabilityLokasi::query()
            ->orderBy('nama_lokasi')
            ->get();

        return view('durability.edit', compact(
            'durability',
            'produkList',
            'komponenList',
            'trainsetList',
            'lokasiList'
        ));
    }

    public function update(Request $request, Durability $durability)
    {
        $validated = $request->validate([
            'tahun' => ['required', 'integer', 'min:2000', 'max:2100'],

            'nomor_po' => ['nullable', 'string', 'max:255'],
            'customer' => ['nullable', 'string', 'max:255'],
            'nama_proyek' => ['nullable', 'string', 'max:255'],

            'nama_produk' => ['required', 'string', 'max:255'],
            'nama_komponen' => ['required', 'string', 'max:255'],
            'nomor_trainset' => ['nullable', 'string', 'max:255'],
            'tipe_car' => ['nullable', 'string', 'max:255'],
            'nama_lokasi' => ['nullable', 'string', 'max:255'],

            'tgl_kerusakan' => ['nullable', 'date'],
            'tgl_terbit_lppb' => ['nullable', 'date'],
            'case_keterangan' => ['nullable', 'string'],
            'rentang_penggantian' => ['nullable', 'integer', 'min:0'],
            'jumlah_penggantian' => ['required', 'integer', 'min:0'],
        ]);

        $proyek = $this->resolveDurabilityProyek($request);
        $produk = $this->resolveDurabilityProduk($request);
        $komponen = $this->resolveDurabilityKomponen($request, $produk);
        $trainset = $this->resolveDurabilityTrainset($request);
        $lokasi = $this->resolveDurabilityLokasi($request);

        $durability->update([
            'tahun' => $validated['tahun'],
            'proyek_id' => $proyek->id,
            'komponen_id' => $komponen->id,
            'trainset_id' => $trainset?->id,
            'lokasi_id' => $lokasi?->id,
            'tgl_kerusakan' => $validated['tgl_kerusakan'] ?? null,
            'tgl_terbit_lppb' => $validated['tgl_terbit_lppb'] ?? null,
            'case_keterangan' => $validated['case_keterangan'] ?? null,
            'rentang_penggantian' => $validated['rentang_penggantian'] ?? null,
            'jumlah_penggantian' => $validated['jumlah_penggantian'],
        ]);

        return redirect()
            ->route('durability.tabel-detail')
            ->with('success', 'Data durability berhasil diperbarui.');
    }

    public function destroy(Durability $durability)
    {
        $durability->delete();

        return redirect()
            ->route('durability.tabel-detail')
            ->with('success', 'Data durability berhasil dihapus.');
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
                if ($value >= 12) {
                    return 'rgba(16, 185, 129, 0.95)';
                }

                if ($value >= 6) {
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

    public function importForm()
    {
        return view('durability.import');
    }

    private function normalizeImportKey($value): string
    {
        $value = strtolower(trim((string) $value));

        return preg_replace('/\s+/', ' ', $value);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv'],
        ]);

        try {
            $import = new DurabilityImport();

            Excel::import($import, $request->file('file'));

            $rows = $import->rows();

            DB::transaction(function () use ($rows) {
                /*
                |--------------------------------------------------------------------------
                | Delete old data
                |--------------------------------------------------------------------------
                | Hapus tabel utama dulu karena tabel ini menyimpan FK ke master.
                | Setelah itu hapus master.
                */
                DB::table('durability')->delete();
                DB::table('durability_komponen')->delete();
                DB::table('durability_produk')->delete();
                DB::table('durability_trainset')->delete();
                DB::table('durability_lokasi')->delete();
                DB::table('durability_proyek')->delete();

                /*
                |--------------------------------------------------------------------------
                | Rebuild master data
                |--------------------------------------------------------------------------
                */
                $produkMap = [];
                $komponenMap = [];
                $lokasiMap = [];
                $trainsetMap = [];
                $proyekMap = [];

                foreach ($rows as $row) {
                    $now = now();

                    /*
                    |--------------------------------------------------------------------------
                    | Produk
                    |--------------------------------------------------------------------------
                    */
                    $produkKey = $this->normalizeImportKey($row['nama_produk']);

                    if (!isset($produkMap[$produkKey])) {
                        $produkId = DB::table('durability_produk')->insertGetId([
                            'nama_produk' => $row['nama_produk'],
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]);

                        $produkMap[$produkKey] = $produkId;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Komponen
                    |--------------------------------------------------------------------------
                    */
                    $komponenKey = $produkKey . '|' . $this->normalizeImportKey($row['detail_komponen']);

                    if (!isset($komponenMap[$komponenKey])) {
                        $komponenId = DB::table('durability_komponen')->insertGetId([
                            'produk_id' => $produkMap[$produkKey],
                            'nama_komponen' => $row['detail_komponen'],
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]);

                        $komponenMap[$komponenKey] = $komponenId;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Lokasi
                    |--------------------------------------------------------------------------
                    */
                    $lokasiKey = $this->normalizeImportKey($row['lokasi']);

                    if (!isset($lokasiMap[$lokasiKey])) {
                        $lokasiId = DB::table('durability_lokasi')->insertGetId([
                            'nama_lokasi' => $row['lokasi'],
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]);

                        $lokasiMap[$lokasiKey] = $lokasiId;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Trainset
                    |--------------------------------------------------------------------------
                    */
                    $trainsetKey = $this->normalizeImportKey($row['trainset'] . '|' . $row['car']);

                    if (!isset($trainsetMap[$trainsetKey])) {
                        $trainsetId = DB::table('durability_trainset')->insertGetId([
                            'nomor_trainset' => $row['trainset'],
                            'tipe_car' => $row['car'],
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]);

                        $trainsetMap[$trainsetKey] = $trainsetId;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Proyek
                    |--------------------------------------------------------------------------
                    */
                    $nomorPo = $row['nomor_po'] ?: null;
                    $customer = $row['customer'] ?: '-';
                    $namaProyek = $row['proyek'] ?: '-';

                    $proyekKey = $nomorPo
                        ? 'po:' . $this->normalizeImportKey($nomorPo)
                        : 'no-po:' . $this->normalizeImportKey($customer . '|' . $namaProyek);

                    if (!isset($proyekMap[$proyekKey])) {
                        $proyekId = DB::table('durability_proyek')->insertGetId([
                            'nomor_po' => $nomorPo,
                            'customer' => $customer,
                            'nama_proyek' => $namaProyek,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]);

                        $proyekMap[$proyekKey] = $proyekId;
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | Insert durability rows
                |--------------------------------------------------------------------------
                */
                $durabilityRows = [];

                foreach ($rows as $row) {
                    $produkKey = $this->normalizeImportKey($row['nama_produk']);
                    $komponenKey = $produkKey . '|' . $this->normalizeImportKey($row['detail_komponen']);
                    $lokasiKey = $this->normalizeImportKey($row['lokasi']);
                    $trainsetKey = $this->normalizeImportKey($row['trainset'] . '|' . $row['car']);
                    $nomorPo = $row['nomor_po'] ?: null;
                    $customer = $row['customer'] ?: '-';
                    $namaProyek = $row['proyek'] ?: '-';

                    $proyekKey = $nomorPo
                        ? 'po:' . $this->normalizeImportKey($nomorPo)
                        : 'no-po:' . $this->normalizeImportKey($customer . '|' . $namaProyek);

                    $durabilityRows[] = [
                        'tahun' => $row['tahun'],
                        'proyek_id' => $proyekMap[$proyekKey],
                        'komponen_id' => $komponenMap[$komponenKey],
                        'trainset_id' => $trainsetMap[$trainsetKey],
                        'lokasi_id' => $lokasiMap[$lokasiKey],
                        'tgl_kerusakan' => $row['tgl_kerusakan'],
                        'tgl_terbit_lppb' => $row['tgl_terbit_lppb'],
                        'case_keterangan' => $row['case_keterangan'],
                        'rentang_penggantian' => $row['rentang_penggantian'],
                        'jumlah_penggantian' => $row['jumlah_penggantian'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                foreach (array_chunk($durabilityRows, 500) as $chunk) {
                    DB::table('durability')->insert($chunk);
                }
            });

            return back()->with(
                'success',
                count($rows) . ' data durability berhasil di-import. Data master durability dan data utama sudah dibuat ulang dari Excel.'
            );
        } catch (ValidationException $e) {
            $messages = collect($e->errors())
                ->flatten()
                ->take(10)
                ->implode(' | ');

            return back()
                ->withInput()
                ->with('error', 'Import gagal. Data lama tidak diubah. Detail: ' . $messages);
        } catch (Throwable $e) {
            return back()
                ->withInput()
                ->with('error', 'Import gagal. Data lama tidak diubah. Pesan error: ' . $e->getMessage());
        }
    }

    private function resolveDurabilityProyek(Request $request): DurabilityProyek
    {
        $nomorPo = $request->filled('nomor_po')
            ? trim($request->nomor_po)
            : null;

        $customer = $request->filled('customer')
            ? trim($request->customer)
            : '-';

        $namaProyek = $request->filled('nama_proyek')
            ? trim($request->nama_proyek)
            : '-';

        if ($nomorPo) {
            return DurabilityProyek::query()->updateOrCreate(
                [
                    'nomor_po' => $nomorPo,
                ],
                [
                    'customer' => $customer,
                    'nama_proyek' => $namaProyek,
                ]
            );
        }

        return DurabilityProyek::query()->firstOrCreate(
            [
                'nomor_po' => null,
                'customer' => $customer,
                'nama_proyek' => $namaProyek,
            ]
        );
    }

    private function resolveDurabilityProduk(Request $request): DurabilityProduk
    {
        $namaProduk = trim((string) $request->nama_produk);

        return DurabilityProduk::query()->firstOrCreate(
            [
                'nama_produk' => $namaProduk,
            ],
            [
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    private function resolveDurabilityKomponen(Request $request, DurabilityProduk $produk): DurabilityKomponen
    {
        $namaKomponen = trim((string) $request->nama_komponen);

        return DurabilityKomponen::query()->firstOrCreate(
            [
                'produk_id' => $produk->id,
                'nama_komponen' => $namaKomponen,
            ],
            [
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    private function resolveDurabilityTrainset(Request $request): ?DurabilityTrainset
    {
        $nomorTrainset = $request->filled('nomor_trainset')
            ? trim((string) $request->nomor_trainset)
            : null;

        $tipeCar = $request->filled('tipe_car')
            ? trim((string) $request->tipe_car)
            : null;

        if (!$nomorTrainset && !$tipeCar) {
            return null;
        }

        return DurabilityTrainset::query()->firstOrCreate(
            [
                'nomor_trainset' => $nomorTrainset,
                'tipe_car' => $tipeCar,
            ],
            [
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    private function resolveDurabilityLokasi(Request $request): ?DurabilityLokasi
    {
        if (!$request->filled('nama_lokasi')) {
            return null;
        }

        $namaLokasi = trim((string) $request->nama_lokasi);

        return DurabilityLokasi::query()->firstOrCreate(
            [
                'nama_lokasi' => $namaLokasi,
            ],
            [
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }


}
