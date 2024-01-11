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
            $table->string("crm_id",21)->nullable();
            $table->boolean("is_talent_pool")->nullable();
            $table->dropUnique(['email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('talents', function (Blueprint $table) {
            $table->dropColumn("crm_id");
            $table->dropColumn("is_talent_pool");
            $table->unique(['email']);
        });
    }
};
