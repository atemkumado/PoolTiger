<?php

use App\Enums\EnglishLevel;
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
        Schema::table('talents', function (Blueprint $table) {
            //
            $table->dropColumn('skill_id');
            $table->dropColumn("english");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('talents', function (Blueprint $table) {
            $table->unsignedBigInteger('skill_id')->nullable();
            $table->integer('english')->nullable();
            //
        });
    }
};
