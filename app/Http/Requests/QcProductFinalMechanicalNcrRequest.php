<?php

namespace App\Http\Requests;

use App\Models\QcProductFinalMechanicalNcr;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QcProductFinalMechanicalNcrRequest extends FormRequest
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
                'exists:qc_product_final_mechanical_checks,id',
            ],

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
                'required_without:project_id',
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
            ],

            'dimension_qty' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'function_qty' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'component_status' => [
                'required',
                Rule::in(
                    array_keys(
                        QcProductFinalMechanicalNcr
                            ::componentStatusOptions()
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

            'reporting_year' =>
                'tahun pelaporan',

            'reporting_month' =>
                'bulan pelaporan',

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
                'Visual',

            'dimension_qty' =>
                'Dimensi',

            'function_qty' =>
                'Fungsi',

            'component_status' =>
                'status komponen',

            'inspector' =>
                'inspector',

            'cycle_time_minutes' =>
                'cycle time',
        ];
    }
}
