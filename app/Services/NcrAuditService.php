<?php

namespace App\Services;

use App\Models\Ncr;
use App\Models\NcrChangeLog;
use App\Models\Project;
use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class NcrAuditService
{
    protected array $fieldLabels = [
        'nama_proses'         => 'Nama Proses',
        'kode_proyek'         => 'Proyek',
        'status_temuan'       => 'Status Temuan',
        'acuan_periksa'       => 'Acuan Periksa',
        'surat_jalan'         => 'Surat Jalan',
        'uraian'              => 'Uraian',
        'uraian_masalah'      => 'Uraian Masalah',
        'kategori_masalah'    => 'Kategori Masalah',
        'penanggung_jawab'    => 'Penanggung Jawab',
        'tgl_target'          => 'Tanggal Target',
        'disposisi_inspektor' => 'Disposisi Inspektor',
        'doc_pendukung'       => 'Dokumen Pendukung',
        'unit_tujuan'         => 'Unit Tujuan',
        'unit_kerja_id'       => 'Unit Kerja',
        'up_file'             => 'Lampiran NCR',
    ];

    protected array $ignoredFields = [
        'updated_at',
        'created_at',
    ];

    public function logUpdate(Ncr $ncr, array $oldData, array $newData): void
    {
        $changes = [];

        foreach ($newData as $field => $newValue) {
            if (in_array($field, $this->ignoredFields, true)) {
                continue;
            }

            if (!array_key_exists($field, $this->fieldLabels)) {
                continue;
            }

            $oldValue = $oldData[$field] ?? null;

            if ($this->valuesAreDifferent($oldValue, $newValue, $field)) {
                $changes[$field] = [
                    'label' => $this->fieldLabels[$field],
                    'old'   => $oldValue,
                    'new'   => $newValue,
                ];

                $formatted = $this->formatFieldValue($field, $oldValue, $newValue);
                $changes[$field] = array_merge($changes[$field], $formatted);
            }
        }

        if (empty($changes)) {
            return;
        }

        $nextRevisionIndex = $this->getNextRevisionIndex($ncr->nomor_ncr);
        $revision = $this->makeRevisionLabel($nextRevisionIndex);

        NcrChangeLog::create([
            'nomor_ncr'      => $ncr->nomor_ncr,
            'user_id'        => Auth::id(),
            'revision'       => $revision,
            'revision_index' => $nextRevisionIndex,
            'action'         => 'updated',
            'changes'        => $changes,
        ]);
    }

    protected function getNextRevisionIndex(string $nomorNcr): int
    {
        $last = NcrChangeLog::where('nomor_ncr', $nomorNcr)->max('revision_index');

        return $last ? $last + 1 : 1;
    }

    protected function makeRevisionLabel(int $index): string
    {
        return 'Rev ' . $this->numberToAlphabet($index);
    }

    protected function numberToAlphabet(int $number): string
    {
        $result = '';

        while ($number > 0) {
            $number--;
            $result = chr(65 + ($number % 26)) . $result;
            $number = intdiv($number, 26);
        }

        return $result;
    }

    protected function valuesAreDifferent($oldValue, $newValue,  ?string $field = null): bool
    {
        if ($oldValue === null && $newValue === '') {
            return false;
        }

        if ($oldValue === '' && $newValue === null) {
            return false;
        }

        if (in_array($field, ['tgl_target'], true)) {
            $oldDate = $oldValue ? \Carbon\Carbon::parse($oldValue)->format('Y-m-d') : null;
            $newDate = $newValue ? \Carbon\Carbon::parse($newValue)->format('Y-m-d') : null;

            return $oldDate !== $newDate;
        }

        return (string) $oldValue !== (string) $newValue;
    }

    protected function formatFieldValue(string $field, $oldValue, $newValue): array
    {
        $result = [];

        if (in_array($field, ['tgl_target'], true)) {
            $result['old_label'] = $oldValue ? \Carbon\Carbon::parse($oldValue)->translatedFormat('d F Y') : '-';
            $result['new_label'] = $newValue ? \Carbon\Carbon::parse($newValue)->translatedFormat('d F Y') : '-';
        }

        if ($field === 'kode_proyek') {
            $oldProject = $oldValue ? Project::where('kode_proyek', $oldValue)->first() : null;
            $newProject = $newValue ? Project::where('kode_proyek', $newValue)->first() : null;

            $result['old_label'] = $oldProject?->nama_proyek;
            $result['new_label'] = $newProject?->nama_proyek;
        }

        if ($field === 'penanggung_jawab') {
            $oldUser = $oldValue ? User::find($oldValue) : null;
            $newUser = $newValue ? User::find($newValue) : null;

            $result['old_label'] = $oldUser?->name;
            $result['new_label'] = $newUser?->name;
        }

        if ($field === 'unit_kerja_id') {
            $oldUnit = $oldValue ? UnitKerja::find($oldValue) : null;
            $newUnit = $newValue ? UnitKerja::find($newValue) : null;

            $result['old_label'] = $oldUnit?->nama_unit;
            $result['new_label'] = $newUnit?->nama_unit;
        }

        if ($field === 'up_file') {
            $result['old_label'] = $oldValue ? basename($oldValue) : null;
            $result['new_label'] = $newValue ? basename($newValue) : null;
        }

        return $result;
    }
}
