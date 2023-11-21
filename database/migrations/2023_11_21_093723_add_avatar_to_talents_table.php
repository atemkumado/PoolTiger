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
        Schema::table('talents', function (Blueprint $table) {
            //
            $table->string('avatarName')->after('ward_id')->nullable();
            $table->string('avatarUrl')->after('avatarName')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('talents', function (Blueprint $table) {
            //
            $table->dropColumn("avatarUrl");
            $table->dropColumn("avatarName");
        });
    }
};
