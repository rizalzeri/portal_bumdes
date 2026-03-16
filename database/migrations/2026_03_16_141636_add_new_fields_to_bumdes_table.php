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
        Schema::table('bumdes', function (Blueprint $table) {
            $table->date('musdes_terakhir')->nullable();
            $table->enum('laporan_dinas_status', ['sudah', 'belum'])->default('belum');
            $table->string('laporan_dinas_link')->nullable();
            $table->date('audit_internal_terakhir')->nullable();
            $table->string('pemeringkatan')->nullable(); // Maju, Berkembang, Perintis, Pemula
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bumdes', function (Blueprint $table) {
            $table->dropColumn(['musdes_terakhir', 'laporan_dinas_status', 'laporan_dinas_link', 'audit_internal_terakhir', 'pemeringkatan']);
        });
    }
};
