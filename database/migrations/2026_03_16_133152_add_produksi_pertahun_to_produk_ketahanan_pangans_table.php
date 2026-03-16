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
        Schema::table('produk_ketahanan_pangans', function (Blueprint $table) {
            $table->string('produksi_pertahun')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produk_ketahanan_pangans', function (Blueprint $table) {
            $table->dropColumn('produksi_pertahun');
        });
    }
};
