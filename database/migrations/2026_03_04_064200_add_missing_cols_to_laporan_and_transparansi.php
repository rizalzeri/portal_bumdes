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
        // laporan_keuangans: add tahun, bulan columns (controllers order by tahun/bulan)
        Schema::table('laporan_keuangans', function (Blueprint $table) {
            $table->string('tahun')->nullable()->after('bumdes_id');
            $table->string('bulan')->nullable()->after('tahun');
            $table->string('keterangan')->nullable()->after('file_url');
        });

        // transparansis: add tahun and tipe columns (controllers use tahun/tipe, table has year/type)
        Schema::table('transparansis', function (Blueprint $table) {
            $table->string('tahun')->nullable()->after('bumdes_id');
            $table->string('tipe')->nullable()->after('tahun');
            $table->text('keterangan')->nullable()->after('file_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_keuangans', function (Blueprint $table) {
            $table->dropColumn(['tahun', 'bulan', 'keterangan']);
        });
        Schema::table('transparansis', function (Blueprint $table) {
            $table->dropColumn(['tahun', 'tipe', 'keterangan']);
        });
    }
};
