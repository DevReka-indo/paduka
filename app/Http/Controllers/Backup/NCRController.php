<?php

namespace App\Http\Controllers;

use App\Models\Ncr;
use App\Models\User;
use App\Models\Project;
use App\Models\Temuan;
use App\Models\UnitKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Notifications\NcrPerluDitanggapiNotification;
use App\Notifications\NcrPerluVerifikasiNotification;
use App\Exports\NcrReportExport;
use Maatwebsite\Excel\Facades\Excel;

class NCRController extends Controller
{
    protected $modelNCR;
    protected $modelPengguna;
    protected $modelProyek;
    protected $modelTemuan;
    protected $modelUnitKerja;

    public function __construct()
    {
        $this->modelNCR = new Ncr();
        $this->modelPengguna = new User();
        $this->modelProyek = new Project();
        $this->modelTemuan = new Temuan();
        $this->modelUnitKerja = new UnitKerja();
    }

    // Halaman daftar registrasi NCR
    public function index(Request $request)
    {
        $authUser = Auth::user();

        $query = Ncr::with(['project', 'penanggungJawab', 'unitKerja', 'user']);

        // Batasi data untuk user & manager
        if (in_array($authUser->level, ['user', 'manager'])) {
            $unitKerjaIds = $authUser->unitKerja()->pluck('unit_kerja.id')->toArray();
            $unitKerjaNames = $authUser->unitKerja()->pluck('nama_unit')->toArray();

            $query->where(function ($q) use ($authUser, $unitKerjaIds, $unitKerjaNames) {
                // 1. NCR dibuat sendiri
                $q->where('user_id', $authUser->id);

                // 2. NCR dia sebagai PIC
                $q->orWhere('penanggung_jawab', $authUser->id);

                // 3. NCR dibuat oleh user yang unit kerjanya sama
                if (!empty($unitKerjaIds)) {
                    $q->orWhereHas('user.unitKerja', function ($uq) use ($unitKerjaIds) {
                        $uq->whereIn('unit_kerja.id', $unitKerjaIds);
                    });
                }

                // 4. NCR berdasarkan unit tujuan (relasi baru)
                if (!empty($unitKerjaIds)) {
                    $q->orWhereIn('unit_kerja_id', $unitKerjaIds);
                }

                // 5. NCR lama (fallback string)
                if (!empty($unitKerjaNames)) {
                    $q->orWhereIn('unit_tujuan', $unitKerjaNames);
                }
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_ncr', 'like', "%{$search}%")
                    ->orWhere('status_temuan', 'like', "%{$search}%")
                    ->orWhere('nama_proses', 'like', "%{$search}%")
                    ->orWhere('unit_tujuan', 'like', "%{$search}%")
                    ->orWhereHas('project', fn($p) => $p->where('nama_proyek', 'like', "%{$search}%"))
                    ->orWhereHas('unitKerja', fn($u) => $u->where('nama_unit', 'like', "%{$search}%")->orWhere('kode_unit', 'like', "%{$search}%"))
                    ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('tgl_dari')) {
            $query->whereDate('tgl_masuk', '>=', $request->tgl_dari);
        }

        if ($request->filled('tgl_sampai')) {
            $query->whereDate('tgl_masuk', '<=', $request->tgl_sampai);
        }

        if ($request->filled('status')) {
            $query->where('keterangan', $request->status);
        }

        $ncr = $query->latest('tgl_masuk')->paginate(20)->withQueryString();

        return view('ncr.index', compact('ncr'));
    }

    // Halaman daftar NCR yang perlu diverifikasi
    public function verifikasincr(Request $request)
    {
        $authUser = Auth::user();

        $query = Ncr::with(['project', 'penanggungJawab', 'unitKerja'])->where('keterangan', 'process');

        // Admin bisa lihat semua NCR proses
        if (!in_array($authUser->level, ['admin', 'superadmin'])) {
            $unitKerjaIds = $authUser->unitKerja()->pluck('unit_kerja.id')->toArray();
            $unitKerjaNames = $authUser->unitKerja()->pluck('nama_unit')->toArray();

            $query->where(function ($q) use ($authUser, $unitKerjaIds, $unitKerjaNames) {
                // 1. Pembuat NCR
                $q->where('user_id', $authUser->id);

                // 2. User dalam unit kerja tujuan (relasi baru)
                if (!empty($unitKerjaIds)) {
                    $q->orWhereIn('unit_kerja_id', $unitKerjaIds);
                }

                // 3. Fallback NCR lama berbasis string unit_tujuan
                if (!empty($unitKerjaNames)) {
                    $q->orWhereIn('unit_tujuan', $unitKerjaNames);
                }
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_ncr', 'like', "%{$search}%")
                    ->orWhere('nama_proses', 'like', "%{$search}%")
                    ->orWhere('status_temuan', 'like', "%{$search}%")
                    ->orWhere('unit_tujuan', 'like', "%{$search}%")
                    ->orWhereHas('project', function ($p) use ($search) {
                        $p->where('nama_proyek', 'like', "%{$search}%");
                    })
                    ->orWhereHas('unitKerja', function ($u) use ($search) {
                        $u->where('nama_unit', 'like', "%{$search}%")->orWhere('kode_unit', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('tgl_dari')) {
            $query->whereDate('tgl_masuk', '>=', $request->tgl_dari);
        }

        if ($request->filled('tgl_sampai')) {
            $query->whereDate('tgl_masuk', '<=', $request->tgl_sampai);
        }

        $ncr = $query->latest('tgl_masuk')->paginate(20)->withQueryString();

        return view('ncr.verifikasi_index', compact('ncr'));
    }

    // Detail NCR
    public function show($slug)
    {
        // $ncr = $this->modelNCR->GetNCR($slug);
        $ncr = Ncr::with(['user', 'project', 'penanggungJawab', 'unitKerja'])
            ->where('nomor_ncr', $slug)
            ->first();

        if (!$ncr) {
            abort(404, 'Data NCR ' . $slug . ' Tidak Ditemukan');
        }

        $authUser = Auth::user();

        $data = [
            'pengguna' => $this->modelPengguna->all(),
            'proyek' => $this->modelProyek->all(),
            'temuan' => $this->modelTemuan->all(),
            'ncr' => $ncr,
            'canTanggapi' => $authUser ? $this->canTanggapiNcr($ncr, $authUser) : false,
        ];

        return view('ncr.show', $data);
    }

    // Form tambah NCR
    public function create(Request $request)
    {
        $ncrLama = null;

        if ($request->filled('from_ncr')) {
            $ncrLama = Ncr::with(['project', 'penanggungJawab', 'unitKerja'])
                ->where('nomor_ncr', $request->from_ncr)
                ->first();

            if (!$ncrLama) {
                abort(404, 'NCR asal tidak ditemukan.');
            }

            // hanya pembuat NCR yang boleh membuat NCR baru dari NCR lama
            if ((int) $ncrLama->user_id !== (int) Auth::id()) {
                abort(403, 'Anda tidak memiliki akses untuk membuat NCR baru dari data ini.');
            }

            // hanya dari NCR close + hasil verifikasi Tidak Efektif
            // if ($ncrLama->keterangan !== 'close' || $ncrLama->hasil_verifikasi !== 'Tidak Efektif') {
            //     abort(403, 'NCR baru hanya bisa dibuat dari NCR yang close dengan hasil verifikasi Tidak Efektif.');
            // }

            // cegah bikin NCR baru lebih dari sekali dari NCR yang sama
            if (!empty($ncrLama->ncr_baru)) {
                return redirect()
                    ->route('ncr.show', $ncrLama->nomor_ncr)
                    ->with('error', 'NCR baru dari data ini sudah pernah dibuat.');
            }
        }
        $data = [
            'proyek' => $this->modelProyek->all(),
            'temuan' => $this->modelTemuan->all(),
            'pengguna' => $this->modelPengguna->all(),
            'nomorNCR' => $this->modelNCR->CekNomor(),
            'nomorUrut' => $this->modelNCR->CekNomorUrut(),
            'unitKerja' => UnitKerja::where('keterangan', 1)->orderBy('nama_unit')->get(),
            'ncrLama' => $ncrLama,
            // 'unitKerja' => $this->modelPengguna->select('unit_kerja')
            //     ->whereNotNull('unit_kerja')
            //     ->where('unit_kerja', '!=', '')
            //     ->distinct()
            //     ->orderBy('unit_kerja')
            //     ->pluck('unit_kerja'),
        ];

        return view('ncr.create', $data);
    }

    // Simpan NCR baru
    public function simpan(Request $request)
    {
        $request->validate(
            [
                'tgl_masuk' => 'required|date',
                'nama_proses' => 'required|string|max:255',
                'kode_proyek' => 'required',
                'status_temuan' => 'required',
                'acuan_periksa' => 'nullable|string',
                'surat_jalan' => 'nullable|string|max:255',
                'uraian' => 'nullable|string',
                'uraian_masalah' => 'nullable|string',
                'kategori_masalah' => 'nullable|string|max:255',
                'penanggung_jawab' => 'nullable',
                'tgl_target' => 'nullable|date',
                'disposisi_inspektor' => 'nullable|string',
                'doc_pendukung' => 'nullable|string|max:255',
                'unit_tujuan' => 'nullable|string|max:255',
                'unit_kerja_id' => 'nullable|exists:unit_kerja,id',
                'up_file' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
                'from_ncr' => 'nullable|string',
            ],
            [
                'tgl_masuk.required' => 'Tanggal masuk wajib diisi.',
                'nama_proses.required' => 'Nama proses wajib diisi.',
                'kode_proyek.required' => 'Proyek wajib dipilih.',
                'status_temuan.required' => 'Lokasi/status temuan wajib dipilih.',
                'unit_kerja_id.exists' => 'Unit kerja yang dipilih tidak valid.',
                'up_file.image' => 'Dokumen yang anda pilih bukan gambar!',
                'up_file.mimes' => 'Dokumen yang anda pilih harus jpg, jpeg, atau png!',
                'up_file.max' => 'Ukuran gambar terlalu besar!',
            ],
        );

        $ncrLama = null;

        if ($request->filled('from_ncr')) {
            $ncrLama = Ncr::where('nomor_ncr', $request->from_ncr)->first();

            if (!$ncrLama) {
                abort(404, 'NCR asal tidak ditemukan.');
            }

            if ((int) $ncrLama->user_id !== (int) Auth::id()) {
                abort(403, 'Anda tidak memiliki akses untuk membuat NCR baru dari data ini.');
            }

            if ($ncrLama->keterangan !== 'close' || $ncrLama->hasil_verifikasi !== 'Tidak Efektif') {
                abort(403, 'NCR baru hanya bisa dibuat dari NCR yang close dengan hasil verifikasi Tidak Efektif.');
            }

            if (!empty($ncrLama->ncr_baru)) {
                return redirect()
                    ->route('ncr.show', $ncrLama->nomor_ncr)
                    ->with('error', 'NCR baru dari data ini sudah pernah dibuat.');
            }
        }

        $pathGambar = null;

        if ($request->hasFile('up_file')) {
            $pathGambar = $request->file('up_file')->store('ncr', 'public');
        }

        $unitKerja = UnitKerja::find($request->input('unit_kerja_id'));

        $ncr = $this->modelNCR->create([
            'nomor_ncr' => $request->input('nomor_ncr'),
            'user_id' => Auth::id(),
            'tgl_masuk' => $request->input('tgl_masuk'),
            'nama_proses' => strip_tags($request->input('nama_proses')),
            'kode_proyek' => $request->input('kode_proyek'),
            'status_temuan' => strip_tags($request->input('status_temuan')),
            'acuan_periksa' => strip_tags($request->input('acuan_periksa')),
            'surat_jalan' => strip_tags($request->input('surat_jalan')),
            'uraian' => strip_tags($request->input('uraian')),
            'uraian_masalah' => strip_tags($request->input('uraian_masalah')),
            'kategori_masalah' => strip_tags($request->input('kategori_masalah')),
            'penanggung_jawab' => $request->input('penanggung_jawab'),
            'tgl_target' => $request->input('tgl_target'),
            'disposisi_inspektor' => strip_tags($request->input('disposisi_inspektor')),
            'doc_pendukung' => strip_tags($request->input('doc_pendukung')),
            'unit_tujuan' => $unitKerja?->nama_unit,
            'unit_kerja_id' => $unitKerja?->id,
            'up_file' => $pathGambar,
            'keterangan' => 'open',
            'uraian_perbaikan' => null,
            'uraian_pencegahan' => null,
        ]);

        // hubungkan NCR lama ke NCR baru
        if ($ncrLama) {
            $ncrLama->update([
                'ncr_baru' => $ncr->nomor_ncr,
            ]);
        }

        $this->kirimNotifikasiPerluDitanggapi($ncr);

        return redirect()->route('ncr.show', $request->input('nomor_ncr'))->with('pesan', 'Data NCR berhasil disimpan');
    }

    // Form ubah NCR
    public function edit($slug)
    {
        $ncr = $this->modelNCR->GetNCR($slug);

        if (!$ncr) {
            abort(404, 'Data NCR ' . $slug . ' Tidak Ditemukan');
        }

        if ((int) $ncr->user_id !== (int) Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah NCR ini.');
        }

        $data = [
            'pengguna' => $this->modelPengguna->all(),
            'proyek' => $this->modelProyek->all(),
            'temuan' => $this->modelTemuan->all(),
            'unitKerja' => UnitKerja::where('keterangan', 1)->orderBy('nama_unit')->get(),
            'ncr' => $ncr,
        ];

        return view('ncr.edit', $data);
    }

    // Simpan perubahan NCR
    public function update(Request $request, $id)
    {
        $ncr = $this->modelNCR->where('nomor_ncr', $id)->firstOrFail();

        if ((int) $ncr->user_id !== (int) Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk memperbarui NCR ini.');
        }

        $request->validate([
            'nama_proses' => 'required|string|max:255',
            'kode_proyek' => 'required',
            'status_temuan' => 'required',
            'acuan_periksa' => 'nullable|string',
            'surat_jalan' => 'nullable|string|max:255',
            'uraian' => 'nullable|string',
            'uraian_masalah' => 'nullable|string',
            'penanggung_jawab' => 'nullable',
            'tgl_target' => 'nullable|date',
            'disposisi_inspektor' => 'nullable|in:internal,eksternal',
            'doc_pendukung' => 'nullable|string|max:255',
            'unit_tujuan' => 'nullable|string|max:255',
            'unit_kerja_id' => 'nullable|exists:unit_kerja,id',
            'up_file' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
        ]);

        $pathGambar = $ncr->up_file;

        if ($request->hasFile('up_file')) {
            if (!empty($ncr->up_file) && Storage::disk('public')->exists($ncr->up_file)) {
                Storage::disk('public')->delete($ncr->up_file);
            }

            $pathGambar = $request->file('up_file')->store('ncr', 'public');
        }

        $unitKerja = UnitKerja::find($request->input('unit_kerja_id'));

        $ncr->update([
            'nama_proses' => strip_tags($request->input('nama_proses')),
            'kode_proyek' => $request->input('kode_proyek'),
            'status_temuan' => strip_tags($request->input('status_temuan')),
            'acuan_periksa' => strip_tags($request->input('acuan_periksa')),
            'surat_jalan' => strip_tags($request->input('surat_jalan')),
            'uraian' => strip_tags($request->input('uraian')),
            'uraian_masalah' => strip_tags($request->input('uraian_masalah')),
            'penanggung_jawab' => $request->input('penanggung_jawab'),
            'tgl_target' => $request->input('tgl_target'),
            'disposisi_inspektor' => $request->input('disposisi_inspektor'),
            'doc_pendukung' => strip_tags($request->input('doc_pendukung')),
            'unit_tujuan' => $unitKerja?->nama_unit,
            'unit_kerja_id' => $unitKerja?->id,
            'up_file' => $pathGambar,
        ]);

        // dd($ncr->up_file);

        return redirect()->route('ncr.show', $ncr->nomor_ncr)->with('pesan', 'Data NCR berhasil diperbarui!');
    }

    // Form tanggapi NCR
    public function tanggapi($slug)
    {
        $ncr = Ncr::with(['project', 'penanggungJawab', 'unitKerja'])
            ->where('nomor_ncr', $slug)
            ->first();

        if (!$ncr) {
            abort(404, 'Data NCR ' . $slug . ' Tidak Ditemukan');
        }

        $authUser = Auth::user();

        if (!$authUser) {
            return redirect()->route('login');
        }

        if (!$this->canTanggapiNcr($ncr, $authUser)) {
            abort(403, 'Anda tidak memiliki akses untuk menanggapi NCR ini.');
        }

        $data = [
            'pengguna' => $this->modelPengguna->all(),
            'proyek' => $this->modelProyek->all(),
            'temuan' => $this->modelTemuan->all(),
            'ncr' => $ncr,
        ];

        return view('ncr.tanggapi.form', $data);
    }

    // Simpan tanggapan NCR
    public function simpantanggapi(Request $request, $id)
    {
        $ncr = $this->modelNCR->where('nomor_ncr', $id)->firstOrFail();
        $authUser = Auth::user();

        if (!$authUser) {
            return redirect()->route('login');
        }

        if (!$this->canTanggapiNcr($ncr, $authUser)) {
            abort(403, 'Anda tidak memiliki akses untuk menanggapi NCR ini.');
        }

        $request->validate(
            [
                'up_filee' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
            ],
            [
                'up_filee.image' => 'Dokumen yang anda pilih bukan gambar!',
                'up_filee.mimes' => 'Dokumen yang anda pilih bukan gambar!',
                'up_filee.max' => 'Ukuran gambar terlalu besar!',
            ],
        );

        if (!$request->hasFile('up_filee')) {
            $namaGambar = $request->input('gambarLama');
        } else {
            $fileGambar = $request->file('up_filee');

            // simpan ke storage/app/public/ncr-tanggapi
            $namaGambar = $fileGambar->store('ncr-tanggapi', 'public');

            // hapus file lama kalau ada
            if (!empty($ncr->up_filee) && $ncr->up_filee !== 'gambar_default.png') {
                Storage::disk('public')->delete($ncr->up_filee);
            }
        }

        $ncr->update([
            'manager_tgp' => $request->input('manager_tgp'),
            'akar_masalah' => $request->input('akar_masalah'),
            'uraian_masalah' => $request->input('uraian_masalah'),
            'uraian_perbaikan' => $request->input('uraian_perbaikan'),
            'uraian_pencegahan' => $request->input('uraian_pencegahan'),
            'kategori_masalah' => $request->input('kategori_masalah'),
            'disposisi_unit' => $request->input('disposisi_unit'),
            'senior_manager' => $request->input('senior_manager'),
            'tgl_managers' => $request->input('tgl_manager'),
            'doc_lampiran' => $request->input('doc_lampiran'),
            'keterangan' => 'process',
            'up_filee' => $namaGambar,
            'manager_tgp_id' => Auth::id(),
        ]);

        $this->kirimNotifikasiPerluVerifikasi($ncr);

        return redirect()->route('ncr.show', $ncr->nomor_ncr)->with('pesan', 'Data NCR berhasil ditanggapi!');
    }

    // Form verifikasi NCR
    public function verifikasi($slug)
    {
        $ncr = Ncr::with(['project', 'penanggungJawab', 'unitKerja'])
            ->where('nomor_ncr', $slug)
            ->firstOrFail();

        if ($ncr->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk verifikasi NCR ini.');
        }

        $data = [
            'pengguna' => $this->modelPengguna->all(),
            'proyek' => $this->modelProyek->all(),
            'temuan' => $this->modelTemuan->all(),
            'ncr' => $ncr,
        ];

        return view('ncr.verifikasi_form', $data);
    }

    // Simpan verifikasi NCR
    public function simpanverifikasi(Request $request, $id)
    {
        $ncr = $this->modelNCR->where('nomor_ncr', $id)->firstOrFail();

        // Hanya pembuat NCR yang boleh verifikasi
        if ((int) $ncr->user_id !== (int) Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk verifikasi NCR ini.');
        }

        $validated = $request->validate(
            [
                'verifikasi_qc' => 'required|string',
                'tgl_verifikasi' => 'required|date',
                'hasil_verifikasi' => 'required|in:Efektif,Tidak Efektif',
                'keterangan' => 'required|in:open,close',
                'ncr_baru' => 'nullable|string|max:255',
            ],
            [
                'verifikasi_qc.required' => 'Penjelasan verifikasi wajib diisi.',
                'tgl_verifikasi.required' => 'Tanggal verifikasi wajib diisi.',
                'tgl_verifikasi.date' => 'Tanggal verifikasi tidak valid.',
                'hasil_verifikasi.required' => 'Hasil verifikasi wajib dipilih.',
                'hasil_verifikasi.in' => 'Hasil verifikasi tidak valid.',
                'ncr_baru.max' => 'NCR baru maksimal 255 karakter.',
                'keterangan.required' => 'Status NCR wajib dipilih.',
                'keterangan.in' => 'Status NCR harus open atau close.',
            ],
        );

        $ncr->update([
            'verifikasi_qc' => strip_tags($validated['verifikasi_qc']),
            'tgl_verifikasi' => $validated['tgl_verifikasi'],
            'hasil_verifikasi' => $validated['hasil_verifikasi'],
            'ncr_baru' => strip_tags($validated['ncr_baru'] ?? ''),
            'keterangan' => $validated['keterangan'],
        ]);

        return redirect()->route('ncr.show', $ncr->nomor_ncr)->with('pesan', 'Data NCR berhasil diverifikasi!');
    }

    // Hapus NCR
    public function destroy($id)
    {
        $ncr = $this->modelNCR->where('nomor_ncr', $id)->firstOrFail();

        if ((int) $ncr->user_id !== (int) Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus NCR ini.');
        }

        if (!empty($ncr->up_file) && $ncr->up_file !== 'gambar_default.png') {
            $file1 = public_path('gambar/ncr/' . $ncr->up_file);
            if (File::exists($file1)) {
                File::delete($file1);
            }
        }

        if (!empty($ncr->up_filee) && $ncr->up_filee !== 'gambar_default.png') {
            $file2 = public_path('gambar/ncr/' . $ncr->up_filee);
            if (File::exists($file2)) {
                File::delete($file2);
            }
        }

        $ncr->delete();

        return redirect()->route('ncr.index')->with('pesan', 'Data NCR berhasil dihapus!');
    }

    // Kembalikan status NCR menjadi open
    public function openncr(Request $request, $id)
    {
        $ncr = $this->modelNCR->findOrFail($id);

        $ncr->update([
            'keterangan' => $request->input('keterangan'),
        ]);

        return redirect()->route('ncr.show', $ncr->nomor_ncr)->with('pesan', 'Data NCR kembali ke Manager dengan Status OPEN!');
    }

    private function canTanggapiNcr(Ncr $ncr, User $user): bool
    {
        if ($user->level !== 'manager') {
            return false;
        }

        $unitKerjaIds = $user->unitKerja()->pluck('unit_kerja.id')->toArray();
        $unitKerjaNames = $user->unitKerja()->pluck('nama_unit')->toArray();

        if (!empty($unitKerjaIds) && !is_null($ncr->unit_kerja_id) && in_array($ncr->unit_kerja_id, $unitKerjaIds)) {
            return true;
        }

        if (!empty($unitKerjaNames) && !empty($ncr->unit_tujuan) && in_array($ncr->unit_tujuan, $unitKerjaNames)) {
            return true;
        }

        return false;
    }

    private function kirimNotifikasiPerluDitanggapi(Ncr $ncr): void
    {
        $recipients = collect();

        // 1. Penanggung jawab
        if ($ncr->penanggungJawab) {
            $recipients->push($ncr->penanggungJawab);
        }

        // 2. Manager berdasarkan unit kerja tujuan NCR
        $managerQuery = User::query()->where('level', 'manager');

        if (!is_null($ncr->unit_kerja_id)) {
            $managerQuery->whereHas('unitKerja', function ($q) use ($ncr) {
                $q->where('unit_kerja.id', $ncr->unit_kerja_id);
            });
        } elseif (!empty($ncr->unit_tujuan)) {
            $managerQuery->whereHas('unitKerja', function ($q) use ($ncr) {
                $q->where('nama_unit', $ncr->unit_tujuan);
            });
        } else {
            return;
        }

        $managers = $managerQuery->get();

        $recipients = $recipients->merge($managers)->unique('id');

        foreach ($recipients as $user) {
            $user->notify(new NcrPerluDitanggapiNotification($ncr));
        }
    }

    private function kirimNotifikasiPerluVerifikasi(Ncr $ncr): void
    {
        if (!$ncr->relationLoaded('user')) {
            $ncr->load('user');
        }

        if ($ncr->user) {
            $ncr->user->notify(new NcrPerluVerifikasiNotification($ncr));
        }
    }

    //Generate Report Excel
    public function exportReport(Request $request)
    {
        $request->validate([
            'tgl_awal' => 'nullable|date',
            'tgl_akhir' => 'nullable|date|after_or_equal:tgl_awal',
            'ket_ncr' => 'nullable|string',
        ]);

        $tglAwal = $request->tgl_awal;
        $tglAkhir = $request->tgl_akhir;
        $ketNCR = $request->ket_ncr;

        $filename = 'laporan_ncr_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(
            new NcrReportExport($tglAwal, $tglAkhir, $ketNCR),
            $filename
        );
    }
}
