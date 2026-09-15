<?php

namespace Database\Seeders;

use App\Models\WorkIndicator;
use Illuminate\Database\Seeder;

class WorkIndicatorSeeder extends Seeder
{
    public function run(): void
    {
        $indicators = [
            [
                'type' => WorkIndicator::TYPE_PROGRAM_KERJA,
                'input_period' => WorkIndicator::PERIOD_MONTHLY,
                'name' => 'Pemantauan dan pengawasan kualitas produk',
                'description' => null,
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'type' => WorkIndicator::TYPE_PROGRAM_KERJA,
                'input_period' => WorkIndicator::PERIOD_MONTHLY,
                'name' => 'Memastikan produk dan layanan selalu memenuhi harapan pelanggan tanpa adanya keluhan pelanggan',
                'description' => null,
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'type' => WorkIndicator::TYPE_PROGRAM_KERJA,
                'input_period' => WorkIndicator::PERIOD_MONTHLY,
                'name' => 'Pencapaian kepuasan pelanggan setiap Catur Wulan',
                'description' => null,
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'type' => WorkIndicator::TYPE_PROGRAM_KERJA,
                'input_period' => WorkIndicator::PERIOD_MONTHLY,
                'name' => 'Pembentukan Kalibrasi & Laboratorium Uji',
                'description' => null,
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'type' => WorkIndicator::TYPE_KPI,
                'input_period' => WorkIndicator::PERIOD_MONTHLY,
                'name' => 'Product Conformity',
                'description' => null,
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'type' => WorkIndicator::TYPE_KPI,
                'input_period' => WorkIndicator::PERIOD_MONTHLY,
                'name' => 'Penanganan Customer Complaint (Customer Non INKA)',
                'description' => null,
                'sort_order' => 2,
                'is_active' => true,
            ],
        ];

        foreach ($indicators as $indicator) {
            WorkIndicator::query()->updateOrCreate(
                [
                    'type' => $indicator['type'],
                    'name' => $indicator['name'],
                ],
                [
                    'input_period' => $indicator['input_period'],
                    'description' => $indicator['description'],
                    'sort_order' => $indicator['sort_order'],
                    'is_active' => $indicator['is_active'],
                ]
            );
        }
    }
}
