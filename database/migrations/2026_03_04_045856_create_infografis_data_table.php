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
        Schema::create('infografis_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('province_id')->nullable()->constrained('provinces')->onDelete('cascade');
            $table->foreignId('kabupaten_id')->nullable()->constrained('kabupatens')->onDelete('cascade');
            $table->integer('total_bumdes')->default(0);
            $table->integer('bumdes_aktif')->default(0);
            $table->decimal('total_aset', 20, 2)->default(0);
            $table->decimal('total_pendapatan', 20, 2)->default(0);
            $table->integer('total_unit_usaha')->default(0);
            $table->string('year');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('infografis_data');
    }
};
