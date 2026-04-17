<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Project;
use App\Models\Temuan;
use App\Models\UnitKerja;

class Ncr extends Model
{
    protected $table = 'ncr';
    protected $primaryKey = 'nomor_ncr';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nomor_ncr',
        'user_id',
        'nama_proses',
        'kode_proyek',
        'status_temuan',
        'acuan_periksa',
        'kategori_masalah',
        'uraian_masalah',
        'uraian_perbaikan',
        'uraian_pencegahan',
        'penanggung_jawab',
        'disposisi_inspektor',
        'disposisi_unit',
        'senior_manager',
        'manager_tgp_id',
        'manager_qc_id',
        'verifikasi_qc',
        'tgl_masuk',
        'tgl_target',
        'tgl_managers',
        'tgl_verifikasi',
        'surat_jalan',
        'akar_masalah',
        'doc_pendukung',
        'doc_lampiran',
        'up_file',
        'up_filee',
        'keterangan',
        'uraian',
        'status_ncr',
        'unit_tujuan',
        'ncr_baru',
        'hasil_verifikasi',
        'unit_kerja_id',
        // ── Kolom tanda tangan digital (ditambahkan via migrasi) ──────────────
        'signed_at_open',    // Di-stamp saat NCR pertama kali dibuat (simpan)
        'signed_at_process', // Di-stamp saat PIC merespons pertama kali (simpantanggapi)
        'signed_at_close',   // Di-stamp saat NCR resmi ditutup (simpanverifikasi + keterangan=close)
    ];

    protected $casts = [
        'tgl_masuk'         => 'date',
        'tgl_target'        => 'date',
        'tgl_managers'      => 'date',
        'tgl_verifikasi'    => 'date',
        // ── Cast ke datetime agar Carbon bisa langsung dipakai di controller & blade
        'signed_at_open'    => 'datetime',
        'signed_at_process' => 'datetime',
        'signed_at_close'   => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function managerQc()
    {
        return $this->belongsTo(User::class, 'manager_qc_id');
    }

    public function managerTgp()
    {
        return $this->belongsTo(User::class, 'manager_tgp_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'kode_proyek', 'kode_proyek');
    }

    public function temuan()
    {
        return $this->belongsTo(Temuan::class, 'status_temuan', 'status_temuan');
    }

    public function penanggungJawab()
    {
        return $this->belongsTo(User::class, 'penanggung_jawab');
    }

    public function unitKerja()
    {
        return $this->belongsTo(UnitKerja::class, 'unit_kerja_id');
    }

    // Ambil semua data / satu data berdasarkan nomor_ncr
    public function GetNCR($nomorNcr = false)
    {
        if ($nomorNcr === false) {
            return self::orderBy('nomor_ncr', 'desc')->get();
        }

        return self::where('nomor_ncr', $nomorNcr)->first();
    }

    // Generate nomor NCR format Ymd + 4 digit
    public function CekNomor()
    {
        $tahun = now('Asia/Jakarta')->format('Y');

        $maxNomor = DB::table($this->table)
            ->selectRaw('MAX(RIGHT(nomor_ncr, 4)) as nomor_ncr')
            ->whereRaw('LEFT(nomor_ncr, 4) = ?', [$tahun])
            ->value('nomor_ncr');

        $kode = $maxNomor
            ? sprintf('%04s', ((int) $maxNomor) + 1)
            : '0001';

        return now('Asia/Jakarta')->format('Ymd') . $kode;
    }

    // Generate nomor urut NCR hanya 4 digit terakhir
    public function CekNomorUrut()
    {
        $tahun = now('Asia/Jakarta')->format('Y');

        $maxNomor = DB::table($this->table)
            ->selectRaw('MAX(RIGHT(nomor_ncr, 4)) as nomor_ncr')
            ->whereRaw('LEFT(nomor_ncr, 4) = ?', [$tahun])
            ->value('nomor_ncr');

        return $maxNomor
            ? sprintf('%04s', ((int) $maxNomor) + 1)
            : '0001';
    }

    public function LaporanNCR($tglAwal = null, $tglAkhir = null, $ketNCR = null)
    {
        $query = self::query();

        if ($tglAwal) {
            $query->whereDate('tgl_masuk', '>=', $tglAwal);
        }

        if ($tglAkhir) {
            $query->whereDate('tgl_masuk', '<=', $tglAkhir);
        }

        if (!empty($ketNCR) && $ketNCR !== 'all') {
            $query->where('keterangan', $ketNCR);
        }

        return $query->get();
    }

    public function GetNCROpenPerluNotifikasi()
    {
        $now = now('Asia/Jakarta')->toDateString();

        return DB::table($this->table)
            ->select($this->table . '.*', 'users.email', 'users.name as nama_pengguna')
            ->leftJoin('users', 'users.id', '=', $this->table . '.penanggung_jawab')
            ->where($this->table . '.keterangan', 'open')
            ->whereRaw('DATEDIFF(?, ' . $this->table . '.tgl_masuk) >= 7', [$now])
            ->where(function ($query) use ($now) {
                $query->whereNull($this->table . '.tgl_notif_terakhir')
                    ->orWhereRaw('DATEDIFF(?, ' . $this->table . '.tgl_notif_terakhir) >= 7', [$now]);
            })
            ->get();
    }

    public function UpdateTglNotif($nomorNcr)
    {
        return DB::table($this->table)
            ->where('nomor_ncr', $nomorNcr)
            ->update([
                'tgl_notif_terakhir' => now('Asia/Jakarta')->toDateString(),
            ]);
    }
}
