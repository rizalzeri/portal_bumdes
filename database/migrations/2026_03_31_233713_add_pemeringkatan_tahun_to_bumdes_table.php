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
            $table->string('pemeringkatan_tahun')->nullable()->after('pemeringkatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bumdes', function (Blueprint $table) {
            $table->dropColumn('pemeringkatan_tahun');
        });
    }
};
