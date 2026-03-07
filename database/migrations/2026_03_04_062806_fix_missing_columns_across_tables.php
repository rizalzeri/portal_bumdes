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
            $table->string('status')->default('active')->nullable();
            $table->string('desa')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('klasifikasi')->nullable();
            
            $table->text('visi_misi')->nullable();
            $table->text('sejarah')->nullable();
            $table->string('badan_hukum')->nullable();
            $table->string('nomor_sertifikat')->nullable();
            $table->string('website')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
        });

        Schema::table('unit_usaha_aktifs', function (Blueprint $table) {
            $table->string('status')->default('active')->nullable();
            $table->string('name')->nullable();
            $table->string('sektor')->nullable();
            $table->string('tahun_berdiri')->nullable();
        });

        Schema::table('personils', function (Blueprint $table) {
            $table->string('role')->nullable();
            $table->string('phone')->nullable();
        });

        Schema::table('katalog_produks', function (Blueprint $table) {
            $table->string('name')->nullable();
            $table->string('category')->nullable();
            $table->string('link_pembelian')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bumdes', function (Blueprint $table) {
            $table->dropColumn([
                'status', 'desa', 'kecamatan', 'klasifikasi', 'visi_misi', 'sejarah', 
                'badan_hukum', 'nomor_sertifikat', 'website', 'facebook', 'instagram', 
                'latitude', 'longitude'
            ]);
        });

        Schema::table('unit_usaha_aktifs', function (Blueprint $table) {
            $table->dropColumn(['status', 'name', 'sektor', 'tahun_berdiri']);
        });

        Schema::table('personils', function (Blueprint $table) {
            $table->dropColumn(['role', 'phone']);
        });

        Schema::table('katalog_produks', function (Blueprint $table) {
            $table->dropColumn(['name', 'category', 'link_pembelian']);
        });
    }
};
