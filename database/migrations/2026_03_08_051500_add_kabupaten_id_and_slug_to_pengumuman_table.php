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
        Schema::table('pengumuman', function (Blueprint $table) {
            if (!Schema::hasColumn('pengumuman', 'kabupaten_id')) {
                $table->foreignId('kabupaten_id')->nullable()->after('bumdes_id')->constrained('kabupatens')->onDelete('cascade');
            }
            if (!Schema::hasColumn('pengumuman', 'slug')) {
                $table->string('slug')->nullable()->unique()->after('title');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengumuman', function (Blueprint $table) {
            if (Schema::hasColumn('pengumuman', 'kabupaten_id')) {
                $table->dropForeign(['kabupaten_id']);
                $table->dropColumn('kabupaten_id');
            }
            if (Schema::hasColumn('pengumuman', 'slug')) {
                $table->dropColumn('slug');
            }
        });
    }
};
