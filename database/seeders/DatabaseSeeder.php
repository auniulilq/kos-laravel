<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Room;
use App\Models\Facility;
use App\Models\Category; // Tambahkan ini

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 1. Create Categories (WAJIB ADA DULU)
        $catStandard = Category::create(['name' => 'Standard', 'slug' => 'standard']);
        $catPremium = Category::create(['name' => 'Premium', 'slug' => 'premium']);
        $catVIP = Category::create(['name' => 'VIP', 'slug' => 'vip']);

        // 2. Create Admin
        User::create([
            'name' => 'Admin Kos',
            'email' => 'admin@kos.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'phone' => '081234567890',
        ]);

        // 3. Create Sample Users
        $budi = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'phone' => '081234567891',
            'address' => 'Jl. Contoh No. 123',
        ]);

        User::create([
            'name' => 'Ani Wijaya',
            'email' => 'ani@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'phone' => '081234567892',
            'address' => 'Jl. Sample No. 456',
        ]);

        // 4. Create Facilities
        $facilities = [
            ['name' => 'WiFi', 'icon' => 'wifi'],
            ['name' => 'AC', 'icon' => 'wind'],
            ['name' => 'Kamar Mandi Dalam', 'icon' => 'droplet'],
            ['name' => 'Kasur', 'icon' => 'bed'],
            ['name' => 'Lemari', 'icon' => 'archive'],
            ['name' => 'Meja Belajar', 'icon' => 'book'],
        ];

        foreach ($facilities as $facility) {
            Facility::create($facility);
        }

        // 5. Create Sample Rooms
        for ($i = 1; $i <= 10; $i++) {
            $roomNumber = str_pad($i, 3, '0', STR_PAD_LEFT);
            $type = $i <= 5 ? 'single' : ($i <= 8 ? 'double' : 'suite');
            
            // Logika Penentuan Kategori & Harga
            if ($type == 'single') {
                $price = 800000;
                $categoryId = $catStandard->id;
            } elseif ($type == 'double') {
                $price = 1200000;
                $categoryId = $catPremium->id;
            } else {
                $price = 1800000;
                $categoryId = $catVIP->id;
            }
            
            Room::create([
                'category_id' => $categoryId, // SEKARANG SUDAH ADA CATEGORY ID
                'room_number' => $roomNumber,
                'type' => $type,
                'price' => $price,
                'status' => $i <= 3 ? 'occupied' : ($i == 10 ? 'maintenance' : 'empty'),
                'facilities' => json_encode([1, 2, 3, 4, 5, 6]),
                'description' => "Kamar tipe $type dengan fasilitas lengkap",
                'user_id' => $i <= 3 ? $budi->id : null, 
            ]);
        }

        // Panggil seeder opsi layanan
        $this->call(ServiceOptionSeeder::class);

        // Tambahkan seeders untuk Booking dan Payment (10 data masing-masing)
        $this->call(BookingSeeder::class);
        $this->call(PaymentSeeder::class);
    }
}