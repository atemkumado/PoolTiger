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
        Schema::table('talent_skill', function (Blueprint $table) {
            $table->unique(['talent_id','skill_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('talent_skill', function (Blueprint $table) {
            $table->dropForeign('`talent_skill_talent_id_skill_id_unique`');
            $table->dropUnique(['talent_id','skill_id']);
        });
    }
};
