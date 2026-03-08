<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = [
            'transparansis',
            'artikels',
            'pengumuman',
            'katalog_produks',
            'mitra_kerjasamas',
            'galeris',
            'materi_templates'
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->boolean('is_featured')->default(false)->after('id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'transparansis',
            'artikels',
            'pengumuman',
            'katalog_produks',
            'mitra_kerjasamas',
            'galeris',
            'materi_templates'
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn('is_featured');
            });
        }
    }
};
