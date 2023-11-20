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
        Schema::create('talent_position', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('talent_id')->nullable();
            $table->unsignedBigInteger('position_id')->nullable();
            $table->timestamps();

            // relationships
            $table->foreign('talent_id')->references('id')->on('talents')->onDelete('cascade');
            $table->foreign('position_id')->references('id')->on('positions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('talent_position');
    }
};
