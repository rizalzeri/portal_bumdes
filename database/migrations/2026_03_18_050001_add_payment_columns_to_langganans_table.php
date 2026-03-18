<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('langganans', function (Blueprint $table) {
            $table->string('payment_token')->nullable()->after('package_name');
            $table->string('order_id')->nullable()->after('payment_token');
            $table->decimal('amount', 15, 2)->default(0)->after('order_id');
            $table->integer('duration_months')->default(1)->after('amount');
        });
    }

    public function down(): void
    {
        Schema::table('langganans', function (Blueprint $table) {
            $table->dropColumn(['payment_token', 'order_id', 'amount', 'duration_months']);
        });
    }
};
