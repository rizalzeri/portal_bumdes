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
        Schema::create('laporan_keuangans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bumdes_id')->constrained('bumdes')->onDelete('cascade');
            $table->string('year');
            $table->decimal('total_aset', 20, 2)->default(0);
            $table->decimal('pendapatan', 20, 2)->default(0);
            $table->decimal('pengeluaran', 20, 2)->default(0);
            $table->decimal('laba_rugi', 20, 2)->default(0);
            $table->string('file_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_keuangans');
    }
};
