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
            $table->unsignedBigInteger('company_id')->after('english')->nullable();
            $table->foreign('company_id')->references('id')->on('companies');
            $table->integer('salary')->after('company_id')->nullable();
            $table->integer('expect')->after('salary')->nullable();
            $table->integer('experience')->after('expect')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('talents', function (Blueprint $table) {
            $table->dropForeign('talents_company_id_foreign');
            $table->dropColumn('company_id');
            $table->dropColumn('salary');
            $table->dropColumn('expect');
            $table->dropColumn('experience');
        });
    }
};
