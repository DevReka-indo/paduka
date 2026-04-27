<?php

namespace App\Http\Controllers;

use App\Models\FeedbackPelanggan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class FeedbackPelangganController extends Controller
{
    public function index(Request $request)
    {
        $query = FeedbackPelanggan::query()->latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->search . '%')
                    ->orWhere('perusahaan', 'like', '%' . $request->search . '%')
                    ->orWhere('proyek', 'like', '%' . $request->search . '%')
                    ->orWhere('identitas_barang', 'like', '%' . $request->search . '%');
            });
        }

        $feedbacks = $query->paginate(10);

        $avgScoreSql = "
            (
                q1_pengiriman_tepat_waktu +
                q2_kemudahan_pengoperasian_produk +
                q3_kemudahan_perawatan +
                q4_pendampingan_support_trial +
                q5_responsif_penanganan_complain +
                q6_teknisi_ramah_sopan +
                q7_penanganan_complain_tepat_cepat +
                q8_media_complain_mudah_diakses +
                q9_produk_sesuai_standar_po
            ) / 9
        ";

        $chartProyek = FeedbackPelanggan::select(
                'proyek',
                DB::raw("ROUND(AVG($avgScoreSql), 2) as rata_rata_skor")
            )
            ->whereNotNull('proyek')
            ->where('proyek', '!=', '')
            ->groupBy('proyek')
            ->orderByDesc('rata_rata_skor')
            ->limit(10)
            ->get();

        $chartProduk = FeedbackPelanggan::select(
                'identitas_barang',
                DB::raw("ROUND(AVG($avgScoreSql), 2) as rata_rata_skor")
            )
            ->whereNotNull('identitas_barang')
            ->where('identitas_barang', '!=', '')
            ->groupBy('identitas_barang')
            ->orderByDesc('rata_rata_skor')
            ->limit(10)
            ->get();

        return view('feedback.index', compact(
            'feedbacks',
            'chartProyek',
            'chartProduk'
        ));
    }

    public function form()
    {
        return view('feedback.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'nullable|string|max:255',
            'perusahaan' => 'nullable|string|max:255',
            'jabatan_unit_kerja' => 'nullable|string|max:255',

            'proyek' => 'required|string|max:255',
            'identitas_barang' => 'required|string|max:255',

            'q1_pengiriman_tepat_waktu' => 'required|integer|min:1|max:4',
            'q2_kemudahan_pengoperasian_produk' => 'required|integer|min:1|max:4',
            'q3_kemudahan_perawatan' => 'required|integer|min:1|max:4',
            'q4_pendampingan_support_trial' => 'required|integer|min:1|max:4',
            'q5_responsif_penanganan_complain' => 'required|integer|min:1|max:4',
            'q6_teknisi_ramah_sopan' => 'required|integer|min:1|max:4',
            'q7_penanganan_complain_tepat_cepat' => 'required|integer|min:1|max:4',
            'q8_media_complain_mudah_diakses' => 'required|integer|min:1|max:4',
            'q9_produk_sesuai_standar_po' => 'required|integer|min:1|max:4',

            'saran_masukan' => 'nullable|string',
            'tanda_tangan' => 'nullable|string',
        ]);

        FeedbackPelanggan::create($validated);

        return redirect()->route('feedback.success');
    }

    public function success()
    {
        return view('feedback.success');
    }

    public function show($id)
    {
        $feedback = FeedbackPelanggan::findOrFail($id);

        return view('feedback.show', compact('feedback'));
    }

    public function pdf($id)
    {
        $feedback = FeedbackPelanggan::findOrFail($id);

        $pdf = Pdf::loadView('feedback.pdf', compact('feedback'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('feedback-pelanggan-' . $feedback->id . '.pdf');
    }

    public function destroy($id)
    {
        $feedback = FeedbackPelanggan::findOrFail($id);
        $feedback->delete();

        return redirect()
            ->route('feedback.index')
            ->with('success', 'Data feedback pelanggan berhasil dihapus.');
    }
}
