<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pricing_configs', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. "Paket Premium"
            $table->integer('months'); // 1, 3, 6, 12
            $table->decimal('base_price_per_month', 15, 2)->default(10000); // price per month in IDR
            $table->boolean('is_active')->default(true);
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pricing_configs');
    }
};
