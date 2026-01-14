<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Standard'],
            ['name' => 'Premium'],
            ['name' => 'VIP'],
        ];

        foreach ($categories as $cat) {
            // Idempotent: hindari duplikasi nama/slug
            Category::firstOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($cat['name'])],
                ['name' => $cat['name']]
            );
        }
    }
}