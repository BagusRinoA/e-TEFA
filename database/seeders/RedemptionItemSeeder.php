<?php

namespace Database\Seeders;

use App\Models\RedemptionItem;
use Illuminate\Database\Seeder;

class RedemptionItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                'name' => 'Voucher Belanja Rp 50.000',
                'description' => 'Voucher belanja senilai Rp 50.000 yang dapat digunakan untuk pembelian produk apapun di toko kami.',
                'points_cost' => 100,
                'stock' => 50,
                'max_redemption_per_user' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Voucher Belanja Rp 100.000',
                'description' => 'Voucher belanja senilai Rp 100.000 yang dapat digunakan untuk pembelian produk apapun di toko kami.',
                'points_cost' => 200,
                'stock' => 30,
                'max_redemption_per_user' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Gratis Ongkir',
                'description' => 'Kupon gratis ongkir untuk pengiriman ke seluruh Indonesia tanpa minimal pembelian.',
                'points_cost' => 50,
                'stock' => 100,
                'max_redemption_per_user' => 10,
                'is_active' => true,
            ],
            [
                'name' => 'Premium Member 1 Bulan',
                'description' => 'Membership premium selama 1 bulan dengan benefit diskon ekstra dan layanan prioritas.',
                'points_cost' => 150,
                'stock' => 20,
                'max_redemption_per_user' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Mystery Box',
                'description' => 'Kotak kejutan berisi produk pilihan senilai lebih dari harga poin yang digunakan.',
                'points_cost' => 250,
                'stock' => 15,
                'max_redemption_per_user' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Cashback Rp 25.000',
                'description' => 'Dapatkan cashback langsung sebesar Rp 25.000 ke rekening Anda.',
                'points_cost' => 80,
                'stock' => 75,
                'max_redemption_per_user' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($items as $item) {
            RedemptionItem::create($item);
        }
    }
}
