<?php

namespace App\Http\Requests;

use App\Models\QcProductFinalMechanicalDailyCheck;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QcProductFinalMechanicalDailyCheckRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reporting_year' => [
                'required',
                'integer',
                'min:2020',
                'max:2100',
            ],

            'reporting_month' => [
                'required',
                'integer',
                'between:1,12',
            ],

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
                'nullable',
                'string',
                'max:100',
            ],

            'inspection_gate' => [
                'nullable',
                'string',
                'max:150',
            ],

            'final_assembly_product_name' => [
                'required',
                'string',
                'max:255',
            ],

            'sub_part_assembly_name' => [
                'nullable',
                'string',
            ],

            'oil_description' => [
                'nullable',
                'string',
            ],

            'batch_reference' => [
                'nullable',
                'string',
                'max:150',
            ],

            'vt_qty' => [
                'nullable',
                'integer',
                'min:0',
                'max:4294967295',
            ],

            'dm_qty' => [
                'nullable',
                'integer',
                'min:0',
                'max:4294967295',
            ],

            'wg_qty' => [
                'nullable',
                'integer',
                'min:0',
                'max:4294967295',
            ],

            'pt_qty' => [
                'nullable',
                'integer',
                'min:0',
                'max:4294967295',
            ],

            'cp_qty' => [
                'nullable',
                'integer',
                'min:0',
                'max:4294967295',
            ],

            'ft_qty' => [
                'nullable',
                'integer',
                'min:0',
                'max:4294967295',
            ],

            'qty_ok' => [
                'nullable',
                'integer',
                'min:0',
                'max:4294967295',
            ],

            'qty_nok' => [
                'nullable',
                'integer',
                'min:0',
                'max:4294967295',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

            'closing_oil_date' => [
                'nullable',
                'date',
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
                        QcProductFinalMechanicalDailyCheck::resultOptions()
                    )
                ),
            ],

            'inspector' => [
                'nullable',
                'string',
                'max:150',
            ],

            'status_is' => [
                'nullable',
                'string',
                'max:100',
            ],

            'cycle_time_minutes' => [
                'nullable',
                'numeric',
                'min:0',
                'max:99999999.99',
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'reporting_year' =>
                'tahun pelaporan',

            'reporting_month' =>
                'bulan pelaporan',

            'check_date' =>
                'tanggal pengecekan',

            'project_id' =>
                'project',

            'project_name' =>
                'nama project',

            'document_check' =>
                'dokumen pengecekan',

            'inspection_gate' =>
                'inspection gate',

            'final_assembly_product_name' =>
                'final assy product name',

            'sub_part_assembly_name' =>
                'sub part assy name',

            'oil_description' =>
                'OIL',

            'batch_reference' =>
                'TS / Batch',

            'vt_qty' =>
                'VT',

            'dm_qty' =>
                'DM',

            'wg_qty' =>
                'WG',

            'pt_qty' =>
                'PT',

            'cp_qty' =>
                'CP',

            'ft_qty' =>
                'FT',

            'qty_ok' =>
                'Qty OK',

            'qty_nok' =>
                'Qty NOK',

            'closing_oil_date' =>
                'tanggal closing OIL',

            'ncr_number' =>
                'nomor NCR',

            'result' =>
                'result',

            'inspector' =>
                'inspector',

            'status_is' =>
                'status IS',

            'cycle_time_minutes' =>
                'cycle time',
        ];
    }
}
