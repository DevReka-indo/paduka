<?php

namespace Database\Seeders;

use App\Models\QcFacilityCategory;
use Illuminate\Database\Seeder;

class QcFacilityCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Alat Ukur',
                'slug' => 'alat-ukur',
                'sort_order' => 1,
            ],
            [
                'name' => 'Alat Uji',
                'slug' => 'alat-uji',
                'sort_order' => 2,
            ],
            [
                'name' => 'Alat Kalibrasi',
                'slug' => 'alat-kalibrasi',
                'sort_order' => 3,
            ],
            [
                'name' => 'Alat Elektrik',
                'slug' => 'alat-elektrik',
                'sort_order' => 4,
            ],
            [
                'name' => 'Alat Mekanik',
                'slug' => 'alat-mekanik',
                'sort_order' => 5,
            ],
            [
                'name' => 'Alat Force',
                'slug' => 'alat-force',
                'sort_order' => 6,
            ],
            [
                'name' => 'Alat Geometri',
                'slug' => 'alat-geometri',
                'sort_order' => 7,
            ],
        ];

        foreach ($categories as $category) {
            QcFacilityCategory::query()->updateOrCreate(
                [
                    'slug' => $category['slug'],
                ],
                [
                    'name' => $category['name'],
                    'description' => null,
                    'sort_order' => $category['sort_order'],
                    'is_active' => true,
                ]
            );
        }
    }
}
