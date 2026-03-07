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
            if (!Schema::hasColumn('pengumuman', 'is_global')) {
                $table->boolean('is_global')->default(false)->after('type');
            }
        });

        Schema::table('artikels', function (Blueprint $table) {
            if (!Schema::hasColumn('artikels', 'is_global')) {
                $table->boolean('is_global')->default(false)->after('category');
            }
            if (!Schema::hasColumn('artikels', 'views')) {
                $table->integer('views')->default(0)->after('is_global');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengumuman', function (Blueprint $table) {
            if (Schema::hasColumn('pengumuman', 'is_global')) {
                $table->dropColumn('is_global');
            }
        });

        Schema::table('artikels', function (Blueprint $table) {
            if (Schema::hasColumn('artikels', 'is_global')) {
                $table->dropColumn('is_global');
            }
            if (Schema::hasColumn('artikels', 'views')) {
                $table->dropColumn('views');
            }
        });
    }
};
