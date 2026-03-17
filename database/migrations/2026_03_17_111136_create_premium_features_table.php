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
        Schema::create('premium_features', function (Blueprint $table) {
            $table->id();
            
            $table->string('module'); // profil, personalia, unit_usaha, produk, dll.
            $table->string('action'); // create, read, update, delete
            
            $table->boolean('is_premium')->default(false);
            $table->integer('free_limit')->nullable();

            $table->string('fallback_action')->default('hide'); 
            
            $table->timestamps();
            
            $table->unique(['module', 'action']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('premium_features');
    }
};
