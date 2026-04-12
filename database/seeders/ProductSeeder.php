<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Roti Tawar Gandum',
                'sku' => 'RT-GND-01',
                'category' => 'Bakery',
                'description' => 'Roti tawar gandum sehat',
                'critical_stock' => 10,
                'expiry_days_threshold' => 3,
                'price' => 15000,
            ],
            [
                'name' => 'Susu Segar 1L',
                'sku' => 'SS-1L-01',
                'category' => 'Dairy',
                'description' => 'Susu murni segar kemasan 1 Liter',
                'critical_stock' => 5,
                'expiry_days_threshold' => 7,
                'price' => 20000,
            ],
            [
                'name' => 'Keju Cheddar Block',
                'sku' => 'CH-BLK-01',
                'category' => 'Dairy',
                'description' => 'Keju cheddar blok 250gr',
                'critical_stock' => 15,
                'expiry_days_threshold' => 30,
                'price' => 25000,
            ]
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['sku' => $product['sku']], // Jangan buat baru kalau SKU sudah ada
                $product
            );
        }
    }
}
