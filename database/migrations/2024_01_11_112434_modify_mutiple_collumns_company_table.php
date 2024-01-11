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
            $table->string("crm_id")->nullable();
            $table->string("account_no")->nullable();
            $table->string("website")->nullable();
            $table->string("email")->nullable();
            $table->string("phone")->nullable();
            $table->renameColumn("notices","description");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn("crm_id");
            $table->dropColumn("account_no");
            $table->dropColumn("website");
            $table->dropColumn("email");
            $table->dropColumn("phone");
            $table->renameColumn("description","notices");
        });
    }
};
