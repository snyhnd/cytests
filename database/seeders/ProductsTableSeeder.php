<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductsTableSeeder extends Seeder
{
    public function run()
    {
        Product::insert([
            [
                'product_name' => 'コーラ',
                'company_id' => 1,
                'price' => 120,
                'stock' => 100,
                'comment' => '炭酸飲料の定番',
                'img_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_name' => 'お茶',
                'company_id' => 2,
                'price' => 100,
                'stock' => 200,
                'comment' => '健康に良い緑茶',
                'img_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_name' => '水',
                'company_id' => 1,
                'price' => 80,
                'stock' => 300,
                'comment' => '天然水',
                'img_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
