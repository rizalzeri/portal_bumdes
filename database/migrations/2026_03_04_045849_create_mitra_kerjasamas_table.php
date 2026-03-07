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
        Schema::create('mitra_kerjasamas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bumdes_id')->nullable()->constrained('bumdes')->onDelete('cascade');
            $table->string('name');
            $table->string('logo')->nullable();
            $table->text('description')->nullable();
            $table->enum('type', ['bumdes', 'portal']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mitra_kerjasamas');
    }
};
