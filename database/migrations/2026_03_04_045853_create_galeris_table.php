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
        Schema::create('galeris', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bumdes_id')->nullable()->constrained('bumdes')->onDelete('cascade');
            $table->string('title');
            $table->string('image');
            $table->text('description')->nullable();
            $table->date('event_date')->nullable();
            $table->enum('type', ['bumdes', 'portal']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('galeris');
    }
};
