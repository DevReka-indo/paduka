<?php

namespace App\Http\Requests;

use App\Models\QcProductElectricalDailyCheck;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QcProductElectricalDailyCheckRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            /*
             * Informasi pemeriksaan
             */
            'check_date' => [
                'required',
                'date',
            ],

            'project_id' => [
                'nullable',
                'integer',
                'exists:projects,id',
            ],

            /*
             * Wajib ketika project_id tidak dipilih.
             * Jika project_id dipilih, nama proyek diisi oleh controller.
             */
            'project_name' => [
                'nullable',
                'string',
                'max:255',
                'required_without:project_id',
            ],

            'document_check' => [
                'required',
                'string',
                'max:50',
            ],

            'inspection_gate' => [
                'required',
                'string',
                'max:100',
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

            'batch_reference' => [
                'nullable',
                'string',
                'max:255',
            ],

            'oil_description' => [
                'nullable',
                'string',
            ],

            /*
             * Jumlah temuan
             */
            'visual_qty' => $this->quantityRules(),
            'skun_qty' => $this->quantityRules(),
            'cramping_qty' => $this->quantityRules(),
            'marking_qty' => $this->quantityRules(),
            'belltest_qty' => $this->quantityRules(),
            'function_qty' => $this->quantityRules(),

            /*
             * Hasil produk dan kabel
             */
            'product_ok_qty' => $this->quantityRules(),
            'product_nok_qty' => $this->quantityRules(),
            'cable_ok_qty' => $this->quantityRules(),
            'cable_nok_qty' => $this->quantityRules(),

            /*
             * Penyelesaian
             */
            'remarks' => [
                'nullable',
                'string',
            ],

            'closing_oil_date' => [
                'nullable',
                'date',
                'after_or_equal:check_date',
            ],

            'status_is' => [
                'required',
                'string',
                'max:100',
            ],

            'result' => [
                'required',
                Rule::in(array_keys(
                    QcProductElectricalDailyCheck::resultOptions()
                )),
            ],

            'inspector' => [
                'required',
                'string',
                'max:255',
            ],

            'cycle_time_minutes' => [
                'nullable',
                'numeric',
                'min:0',
                'max:99999999.99',
            ],

            /*
             * NCR opsional
             */
            'ncr_number' => [
                'nullable',
                'string',
                'max:255',
                'required_with:ncr_category',
            ],

            'ncr_category' => [
                'nullable',
                Rule::in(array_keys(
                    QcProductElectricalDailyCheck::ncrCategoryOptions()
                )),
                'required_with:ncr_number',
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
            'inspection_gate' => 'inspection gate',
            'product_name' => 'nama produk',
            'check_category' => 'kategori pemeriksaan',
            'batch_reference' => 'TS / batch / car',
            'oil_description' => 'uraian OIL',

            'visual_qty' => 'jumlah visual',
            'skun_qty' => 'jumlah skun',
            'cramping_qty' => 'jumlah cramping',
            'marking_qty' => 'jumlah marking',
            'belltest_qty' => 'jumlah belltest',
            'function_qty' => 'jumlah function',

            'product_ok_qty' => 'jumlah produk OK',
            'product_nok_qty' => 'jumlah produk NOK',
            'cable_ok_qty' => 'jumlah kabel OK',
            'cable_nok_qty' => 'jumlah kabel NOK',

            'closing_oil_date' => 'tanggal closing OIL',
            'status_is' => 'status IS',
            'result' => 'hasil pemeriksaan',
            'inspector' => 'inspector',
            'cycle_time_minutes' => 'cycle time',
            'ncr_number' => 'nomor NCR',
            'ncr_category' => 'kategori NCR',
            'oil_link' => 'tautan OIL',
        ];
    }

    public function messages(): array
    {
        return [
            'project_name.required_without' =>
                'Pilih proyek dari master atau isi nama proyek lainnya.',

            'closing_oil_date.after_or_equal' =>
                'Tanggal closing OIL tidak boleh lebih awal dari tanggal pemeriksaan.',

            'ncr_category.required_with' =>
                'Kategori NCR wajib dipilih ketika nomor NCR diisi.',

            'ncr_number.required_with' =>
                'Nomor NCR wajib diisi ketika kategori NCR dipilih.',
        ];
    }

    private function quantityRules(): array
    {
        return [
            'nullable',
            'integer',
            'min:0',
            'max:4294967295',
        ];
    }
}
