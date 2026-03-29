<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('langganans', function (Blueprint $table) {
            // Kabupaten_id (nullable, karena bisa untuk bumdes ATAU kabupaten)
            $table->unsignedBigInteger('kabupaten_id')->nullable()->after('bumdes_id');

            // Foreign key constraint
            $table->foreign('kabupaten_id')->references('id')->on('kabupatens')->nullOnDelete();

            // bumdes_id harus nullable juga (untuk langganan kabupaten tanpa bumdes)
            $table->unsignedBigInteger('pricing_config_id')->nullable()->after('kabupaten_id');
            $table->foreign('pricing_config_id')->references('id')->on('pricing_configs')->nullOnDelete();

            // Payment method (transfer, tunai, dll)
            $table->string('payment_method')->nullable()->after('status');
        });

        // bumdes_id perlu jadi nullable agar admin kabupaten bisa daftar tanpa bumdes_id
        Schema::table('langganans', function (Blueprint $table) {
            $table->unsignedBigInteger('bumdes_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('langganans', function (Blueprint $table) {
            $table->dropForeign(['kabupaten_id']);
            $table->dropForeign(['pricing_config_id']);
            $table->dropColumn(['kabupaten_id', 'pricing_config_id', 'payment_method']);
        });
    }
};
