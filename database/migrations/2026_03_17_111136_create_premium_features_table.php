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
        Schema::create('premium_features', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('name');
            $table->string('category')->default('Umum'); // Profil, Mitra, Keuangan, dsb
            $table->text('description')->nullable();
            
            // Konfigurasi Premium
            $table->boolean('is_premium')->default(false); // Apakah fitur ini di-lock untuk premium?
            
            // Limit gratis jika ada (misal: hanya boleh tambah 3 mitra)
            $table->integer('free_limit')->nullable();

            $table->string('fallback_action')->default('hide'); // hide, readonly
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('premium_features');
    }
};
