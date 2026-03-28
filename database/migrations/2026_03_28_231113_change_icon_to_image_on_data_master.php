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
        Schema::table('unit_usaha_options', function (Blueprint $table) {
            $table->string('image')->nullable()->after('name');
        });
        Schema::table('produk_bumdes_options', function (Blueprint $table) {
            $table->string('image')->nullable()->after('name');
        });
        Schema::table('produk_ketapang_options', function (Blueprint $table) {
            $table->string('image')->nullable()->after('name');
        });
        Schema::table('mitra_options', function (Blueprint $table) {
            $table->string('image')->nullable()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('unit_usaha_options', function (Blueprint $table) {
            $table->dropColumn('image');
        });
        Schema::table('produk_bumdes_options', function (Blueprint $table) {
            $table->dropColumn('image');
        });
        Schema::table('produk_ketapang_options', function (Blueprint $table) {
            $table->dropColumn('image');
        });
        Schema::table('mitra_options', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
};
