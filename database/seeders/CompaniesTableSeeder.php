<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;

class CompaniesTableSeeder extends Seeder
{
    public function run()
    {
       Company::insert([
    ['company_name' => 'サントリー', 'street_address' => '福岡市南区', 'representative_name' => '田中 太郎', 'created_at' => now(), 'updated_at' => now()],
    ['company_name' => '伊藤園', 'street_address' => '福岡市東区', 'representative_name' => '佐藤 花子', 'created_at' => now(), 'updated_at' => now()],
    ['company_name' => 'キリン', 'street_address' => '福岡市西区', 'representative_name' => '田中 次郎', 'created_at' => now(), 'updated_at' => now()],
    ['company_name' => 'アサヒ', 'street_address' => '福岡市博多区', 'representative_name' => '山田 花子', 'created_at' => now(), 'updated_at' => now()],
    ['company_name' => 'コカ・コーラ', 'street_address' => '福岡市中央区', 'representative_name' => '田中 三郎', 'created_at' => now(), 'updated_at' => now()],
]);
    }
    
}