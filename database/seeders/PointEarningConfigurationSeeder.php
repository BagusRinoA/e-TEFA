<?php

namespace Database\Seeders;

use App\Models\PointEarningConfiguration;
use Illuminate\Database\Seeder;

class PointEarningConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $configs = [
            [
                'min_purchase_amount' => 0,
                'max_purchase_amount' => 100000,
                'points_earned' => 5,
                'description' => 'Pembelian 0 - Rp 100.000',
                'is_active' => true,
            ],
            [
                'min_purchase_amount' => 100000,
                'max_purchase_amount' => 500000,
                'points_earned' => 15,
                'description' => 'Pembelian Rp 100.000 - Rp 500.000',
                'is_active' => true,
            ],
            [
                'min_purchase_amount' => 500000,
                'max_purchase_amount' => 1000000,
                'points_earned' => 30,
                'description' => 'Pembelian Rp 500.000 - Rp 1.000.000',
                'is_active' => true,
            ],
            [
                'min_purchase_amount' => 1000000,
                'max_purchase_amount' => null,
                'points_earned' => 50,
                'description' => 'Pembelian di atas Rp 1.000.000',
                'is_active' => true,
            ],
        ];

        foreach ($configs as $config) {
            PointEarningConfiguration::create($config);
        }
    }
}
