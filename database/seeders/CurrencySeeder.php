<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencies = [
            ['name' => 'US Dollar', 'code' => 'USD', 'sign' => '$'],
            ['name' => 'Euro', 'code' => 'EUR', 'sign' => '€'],
            ['name' => 'British Pound', 'code' => 'GBP', 'sign' => '£'],
            ['name' => 'Japanese Yen', 'code' => 'JPY', 'sign' => '¥'],
            ['name' => 'Swiss Franc', 'code' => 'CHF', 'sign' => 'CHF'],
            ['name' => 'Canadian Dollar', 'code' => 'CAD', 'sign' => 'C$'],
            ['name' => 'Australian Dollar', 'code' => 'AUD', 'sign' => 'A$'],
            ['name' => 'Chinese Yuan', 'code' => 'CNY', 'sign' => '¥'],
            ['name' => 'Swedish Krona', 'code' => 'SEK', 'sign' => 'kr'],
            ['name' => 'Norwegian Krone', 'code' => 'NOK', 'sign' => 'kr'],
            ['name' => 'Danish Krone', 'code' => 'DKK', 'sign' => 'kr'],
            ['name' => 'Singapore Dollar', 'code' => 'SGD', 'sign' => 'S$'],
            ['name' => 'Hong Kong Dollar', 'code' => 'HKD', 'sign' => 'HK$'],
            ['name' => 'Indian Rupee', 'code' => 'INR', 'sign' => '₹'],
            ['name' => 'South Korean Won', 'code' => 'KRW', 'sign' => '₩'],
            ['name' => 'Brazilian Real', 'code' => 'BRL', 'sign' => 'R$'],
            ['name' => 'South African Rand', 'code' => 'ZAR', 'sign' => 'R'],
            ['name' => 'Mexican Peso', 'code' => 'MXN', 'sign' => '$'],
            ['name' => 'New Zealand Dollar', 'code' => 'NZD', 'sign' => 'NZ$'],
            ['name' => 'Polish Zloty', 'code' => 'PLN', 'sign' => 'zł'],
        ];

        foreach ($currencies as $currency) {
            DB::table('currencies')->insert([
                'id' => Str::uuid(),
                'name' => $currency['name'],
                'code' => $currency['code'],
                'sign' => $currency['sign'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
