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
        Schema::table('mitra_kerjasamas', function (Blueprint $table) {
            $table->foreignId('mitra_option_id')->nullable()->constrained('mitra_options')->onDelete('set null');
            $table->boolean('is_active')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mitra_kerjasamas', function (Blueprint $table) {
            $table->dropForeign(['mitra_option_id']);
            $table->dropColumn('mitra_option_id');
            $table->dropColumn('is_active');
        });
    }
};
