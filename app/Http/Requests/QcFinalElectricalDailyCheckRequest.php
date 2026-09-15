<?php

namespace App\Http\Requests;

use App\Models\QcFinalElectricalDailyCheck;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QcFinalElectricalDailyCheckRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'check_date' => [
                'required',
                'date',
            ],

            'project_id' => [
                'nullable',
                'integer',
                'exists:projects,id',
            ],

            'project_name' => [
                'nullable',
                'required_without:project_id',
                'string',
                'max:255',
            ],

            'document_check' => [
                'required',
                'string',
                'max:100',
            ],

            'inspection_gate' => [
                'required',
                'string',
                'max:150',
            ],

            'product_name' => [
                'required',
                'string',
                'max:255',
            ],

            'check_category' => [
                'required',
                'string',
                'max:100',
            ],

            'oil_description' => [
                'nullable',
                'string',
            ],

            'serial_number' => [
                'nullable',
                'string',
                'max:150',
            ],

            'car_reference' => [
                'nullable',
                'string',
                'max:150',
            ],

            'batch_reference' => [
                'nullable',
                'string',
                'max:150',
            ],

            'visual_qty' => [
                'nullable',
                'integer',
                'min:0',
                'max:4294967295',
            ],

            'completeness_qty' => [
                'nullable',
                'integer',
                'min:0',
                'max:4294967295',
            ],

            'belltest_qty' => [
                'nullable',
                'integer',
                'min:0',
                'max:4294967295',
            ],

            'function_qty' => [
                'nullable',
                'integer',
                'min:0',
                'max:4294967295',
            ],

            'torque_qty' => [
                'nullable',
                'integer',
                'min:0',
                'max:4294967295',
            ],

            'closing_oil_date' => [
                'nullable',
                'date',
                'after_or_equal:check_date',
            ],

            'oil_count' => [
                'nullable',
                'integer',
                'min:0',
                'max:4294967295',
            ],

            'ncr_number' => [
                'nullable',
                'string',
                'max:100',
            ],

            'result' => [
                'required',
                Rule::in(
                    array_keys(
                        QcFinalElectricalDailyCheck::resultOptions()
                    )
                ),
            ],

            'inspector' => [
                'required',
                'string',
                'max:150',
            ],

            'status_is' => [
                'nullable',
                'string',
                'max:100',
            ],

            'oil_link' => [
                'nullable',
                'url',
                'max:2048',
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'check_date' => 'tanggal pemeriksaan',
            'project_id' => 'proyek',
            'project_name' => 'nama proyek',
            'document_check' => 'dokumen pemeriksaan',
            'inspection_gate' => 'lokasi pemeriksaan',
            'product_name' => 'nama produk',
            'check_category' => 'kategori pemeriksaan',
            'oil_description' => 'OIL',
            'serial_number' => 'serial number',
            'car_reference' => 'car',
            'batch_reference' => 'TS / batch',
            'visual_qty' => 'temuan visual',
            'completeness_qty' => 'temuan kelengkapan',
            'belltest_qty' => 'temuan belltest',
            'function_qty' => 'temuan fungsi',
            'torque_qty' => 'temuan torsi',
            'closing_oil_date' => 'tanggal closing OIL',
            'oil_count' => 'jumlah OIL',
            'ncr_number' => 'nomor NCR',
            'result' => 'hasil pemeriksaan',
            'inspector' => 'inspektor',
            'status_is' => 'status IS',
            'oil_link' => 'link OIL',
        ];
    }
}
