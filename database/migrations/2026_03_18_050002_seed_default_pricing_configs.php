<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Migrations\Migration as MigrationBase;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('pricing_configs')->insert([
            ['name' => 'Paket 1 Bulan', 'months' => 1,  'base_price_per_month' => 10000, 'is_active' => true, 'description' => 'Akses premium selama 1 bulan',  'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Paket 3 Bulan', 'months' => 3,  'base_price_per_month' => 10000, 'is_active' => true, 'description' => 'Hemat 0% - Akses premium 3 bulan', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Paket 6 Bulan', 'months' => 6,  'base_price_per_month' => 9000,  'is_active' => true, 'description' => 'Hemat 10% - Akses premium 6 bulan', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Paket 1 Tahun', 'months' => 12, 'base_price_per_month' => 8000,  'is_active' => true, 'description' => 'Hemat 20% - Akses premium 1 tahun', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        DB::table('pricing_configs')->whereIn('months', [1, 3, 6, 12])->delete();
    }
};
