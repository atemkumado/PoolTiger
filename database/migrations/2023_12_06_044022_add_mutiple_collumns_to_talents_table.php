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
            $table->string('linkedin')->nullable()->after('english');
            $table->string('facebook')->nullable()->after('linkedin');
            $table->string('github')->nullable()->after('facebook');
            $table->string('portfolio')->nullable()->after('github');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('talents', function (Blueprint $table) {
            $table->dropColumn("linkedin");
            $table->dropColumn("facebook");
            $table->dropColumn("github");
            $table->dropColumn("portfolio");

        });
    }
};
