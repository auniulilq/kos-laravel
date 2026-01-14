<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServiceOption;

class ServiceOptionSeeder extends Seeder
{
    public function run(): void
    {
        // Laundry per kg
        ServiceOption::updateOrCreate(
            ['service_type' => 'laundry', 'name' => 'Laundry per kg'],
            ['pricing_type' => 'per_unit', 'unit_name' => 'kg', 'price' => 10000, 'min_qty' => 1, 'max_qty' => 20, 'is_active' => true]
        );

        // Cuci selimut
        ServiceOption::updateOrCreate(
            ['service_type' => 'blanket', 'name' => 'Cuci Selimut'],
            ['pricing_type' => 'fixed', 'unit_name' => null, 'price' => 15000, 'min_qty' => 1, 'max_qty' => null, 'is_active' => true]
        );

        // Perbaikan - harga ditentukan admin (quote)
        ServiceOption::updateOrCreate(
            ['service_type' => 'repair', 'name' => 'Perbaikan (Konfirmasi Harga)'],
            ['pricing_type' => 'quote', 'unit_name' => null, 'price' => null, 'min_qty' => 1, 'max_qty' => null, 'is_active' => true]
        );
    }
}