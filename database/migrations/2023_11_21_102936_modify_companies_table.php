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
        Schema::table('companies', function (Blueprint $table) {
            $table->dropForeign('companies_talent_id_foreign');
            $table->dropColumn('talent_id');
            $table->dropColumn('salary');
            $table->dropColumn('expect');
            $table->dropColumn('experience');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->unsignedBigInteger('talent_id')->after('notices')->nullable();
            $table->foreign('talent_id')->references('id')->on('talents');
            $table->integer('salary');
            $table->integer('expect');
            $table->integer('experience');
        });

    }
};
