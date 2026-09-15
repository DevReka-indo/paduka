<?php

namespace App\Http\Requests;

use App\Models\QcFinalElectricalNcr;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QcFinalElectricalNcrRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'daily_check_id' => [
                'nullable',
                'integer',
                'exists:qc_final_electrical_checks,id',
            ],

            'ncr_number' => [
                'required',
                'string',
                'max:100',
            ],

            'project_id' => [
                'nullable',
                'integer',
                'exists:projects,id',
            ],

            'project_name' => [
                'nullable',
                'required_without_all:project_id,daily_check_id',
                'string',
                'max:255',
            ],

            'issued_date' => [
                'required',
                'date',
            ],

            'product_name' => [
                'required',
                'string',
                'max:255',
            ],

            'nonconformity_location' => [
                'nullable',
                'string',
                'max:255',
            ],

            'target_unit' => [
                'nullable',
                'string',
                'max:255',
            ],

            'nonconformity_description' => [
                'required',
                'string',
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

            'specification_qty' => [
                'nullable',
                'integer',
                'min:0',
                'max:4294967295',
            ],

            'dimension_qty' => [
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

            'component_status' => [
                'required',
                Rule::in(
                    array_keys(
                        QcFinalElectricalNcr::componentStatusOptions()
                    )
                ),
            ],

            'inspector' => [
                'nullable',
                'string',
                'max:150',
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
            'daily_check_id' =>
                'Daily Check',

            'ncr_number' =>
                'nomor NCR',

            'project_id' =>
                'project',

            'project_name' =>
                'nama project',

            'issued_date' =>
                'tanggal terbit',

            'product_name' =>
                'nama produk / proses',

            'nonconformity_location' =>
                'lokasi ketidaksesuaian',

            'target_unit' =>
                'unit yang dituju',

            'nonconformity_description' =>
                'uraian ketidaksesuaian',

            'visual_qty' =>
                'temuan visual',

            'completeness_qty' =>
                'temuan kelengkapan',

            'specification_qty' =>
                'temuan spesifikasi',

            'dimension_qty' =>
                'temuan dimensi',

            'function_qty' =>
                'temuan fungsi',

            'component_status' =>
                'status komponen',

            'inspector' =>
                'inspector',

            'cycle_time_minutes' =>
                'cycle time check',
        ];
    }
}
